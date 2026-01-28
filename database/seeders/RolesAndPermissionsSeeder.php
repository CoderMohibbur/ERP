<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ If Spatie tables are missing, do not crash seeding.
        if (
            !Schema::hasTable('roles') ||
            !Schema::hasTable('permissions') ||
            !Schema::hasTable('role_has_permissions') ||
            !Schema::hasTable('model_has_roles') ||
            !Schema::hasTable('model_has_permissions')
        ) {
            $this->command?->warn('Skipping RolesAndPermissionsSeeder: Spatie Permission tables not found. Run migrations first.');
            return;
        }

        // Clear cached roles/permissions (safe)
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $guard = (string) config('auth.defaults.guard', 'web');

        $permissionNames = $this->buildAllPermissions();

        DB::transaction(function () use ($permissionNames, $guard) {
            // 1) Create permissions (idempotent)
            foreach ($permissionNames as $name) {
                Permission::findOrCreate($name, $guard);
            }

            // 2) Create roles (idempotent) — per spec (+ optional)
            $roles = ['Owner', 'LaravelLead', 'WPLead', 'Accounts', 'Support'];
            foreach ($roles as $roleName) {
                Role::findOrCreate($roleName, $guard);
            }

            // 3) Assign permissions to roles
            $all = Permission::query()
                ->where('guard_name', $guard)
                ->pluck('name')
                ->all();

            // Owner: all permissions
            $this->role($guard, 'Owner')?->syncPermissions($all);

            // LaravelLead: CRM + Delivery + Time + Renewals + Team dashboard + Read-only finance
            $laravelLeadPatterns = [
                'lead.*', 'deal.*', 'activity.*',
                'project.*', 'task.*',
                'timelog.*',
                'service.*', 'renewal.*',
                'dashboard.team',

                'client.*',
                'project_file.*', 'project_note.*',

                // Finance read-only
                'invoice.index', 'invoice.list', 'invoice.viewAny', 'invoice.view', 'invoice.show',
                'payment.index', 'payment.list', 'payment.viewAny', 'payment.view', 'payment.show',
                'expense.index', 'expense.list', 'expense.viewAny', 'expense.view', 'expense.show',

                'report.*',
            ];
            $this->role($guard, 'LaravelLead')?->syncPermissions(
                $this->resolveByPatterns($laravelLeadPatterns, $all)
            );

            // WPLead: CRM + Delivery + Time + Team dashboard (+ client read)
            $wpLeadPatterns = [
                'lead.*', 'deal.*', 'activity.*',
                'project.*', 'task.*',
                'timelog.*',
                'dashboard.team',

                'client.index', 'client.list', 'client.viewAny', 'client.view', 'client.show',
                'project_file.*', 'project_note.*',
            ];
            $this->role($guard, 'WPLead')?->syncPermissions(
                $this->resolveByPatterns($wpLeadPatterns, $all)
            );

            // Accounts: Finance full + client read + dashboard.team
            $accountsPatterns = [
                'invoice.*', 'payment.*', 'expense.*',
                'payment_method.*', 'tax_rule.*',
                'invoice_item.*', 'invoice_item_field.*',
                'report.*',
                'client.index', 'client.list', 'client.viewAny', 'client.view', 'client.show',
                'dashboard.team',
            ];
            $this->role($guard, 'Accounts')?->syncPermissions(
                $this->resolveByPatterns($accountsPatterns, $all)
            );

            // Support: CRM limited + Activities + Client read + dashboard.team (no delete)
            $supportPatterns = [
                'lead.index', 'lead.list', 'lead.viewAny', 'lead.view', 'lead.show',
                'lead.create', 'lead.store', 'lead.update', 'lead.edit',

                'deal.index', 'deal.list', 'deal.viewAny', 'deal.view', 'deal.show',
                'deal.updateStage', 'deal.stage', 'deal.stage.update',

                'activity.*',

                'client.index', 'client.list', 'client.viewAny', 'client.view', 'client.show',

                'service.index', 'service.list', 'service.viewAny', 'service.view', 'service.show',
                'renewal.index', 'renewal.list', 'renewal.viewAny', 'renewal.view', 'renewal.show',

                'dashboard.team',
            ];
            $this->role($guard, 'Support')?->syncPermissions(
                $this->resolveByPatterns($supportPatterns, $all)
            );
        });

        // Optional: assign Owner role to first user if possible (safe)
        $this->assignOwnerToFirstUserIfPossible($guard);

        // Clear cache again
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->command?->info('RolesAndPermissionsSeeder completed successfully.');
    }

    private function role(string $guard, string $name): ?Role
    {
        return Role::query()
            ->where('guard_name', $guard)
            ->where('name', $name)
            ->first();
    }

    private function buildAllPermissions(): array
    {
        // Spec modules (Minimum ERP)
        $minimumModules = [
            'lead', 'deal', 'activity',
            'project', 'task',
            'timelog',
            'invoice', 'payment', 'expense',
            'service', 'renewal',
        ];

        // Existing base modules in this ERP
        $existingBaseModules = [
            'client', 'client_contact', 'client_note',
            'department', 'designation', 'employee',
            'attendance', 'attendance_setting',
            'project_file', 'project_note',
            'payment_method',
            'invoice_item', 'invoice_item_field',
            'tax_rule',
            'employee_history', 'employee_document', 'skill', 'employee_skill',
            'shift', 'employee_shift',
            'employee_dependent', 'employee_resignation', 'employee_disciplinary_action',
            'discount_type',
        ];

        // System/admin modules
        $systemModules = [
            'user', 'role', 'permission', 'setting',
            'report',
        ];

        $modules = array_values(array_unique(array_merge($minimumModules, $existingBaseModules, $systemModules)));

        $permissions = [];

        foreach ($modules as $module) {
            $permissions = array_merge($permissions, $this->crudPermissions($module));
        }

        // Dashboard (spec)
        $permissions[] = 'dashboard.*';
        $permissions[] = 'dashboard.owner';
        $permissions[] = 'dashboard.team';

        // Special actions
        $permissions = array_merge($permissions, [
            'deal.updateStage',
            'deal.stage',
            'deal.stage.update',
            'deal.markWon',
            'deal.markLost',

            'task.timer.start',
            'task.timer.stop',
            'task.timer.pause',
            'task.timer.resume',

            'timelog.start',
            'timelog.stop',
            'timelog.pause',
            'timelog.resume',

            'service.renewals.generateInvoice',
            'service.renewal.generateInvoice',
            'renewal.generateInvoice',
        ]);

        $permissions = array_values(array_unique(array_filter($permissions)));
        sort($permissions);

        return $permissions;
    }

    private function crudPermissions(string $module): array
    {
        return [
            "{$module}.*",

            "{$module}.index",
            "{$module}.list",
            "{$module}.viewAny",
            "{$module}.view",
            "{$module}.show",

            "{$module}.create",
            "{$module}.store",
            "{$module}.edit",
            "{$module}.update",
            "{$module}.destroy",
            "{$module}.delete",

            "{$module}.restore",
            "{$module}.forceDelete",

            "{$module}.export",
            "{$module}.import",
        ];
    }

    private function resolveByPatterns(array $patterns, array $allPermissions): array
    {
        $out = [];

        foreach ($patterns as $pattern) {
            foreach ($allPermissions as $perm) {
                if (Str::is($pattern, $perm)) {
                    $out[] = $perm;
                }
            }
        }

        return array_values(array_unique($out));
    }

    private function assignOwnerToFirstUserIfPossible(string $guard): void
    {
        $userModel = config('auth.providers.users.model', \App\Models\User::class);

        if (!is_string($userModel) || !class_exists($userModel)) {
            return;
        }

        if (!is_subclass_of($userModel, Model::class)) {
            return;
        }

        /** @var \Illuminate\Database\Eloquent\Model|null $user */
        $user = $userModel::query()->orderBy('id')->first();
        if (!$user) {
            return;
        }

        if (!method_exists($user, 'assignRole') || !method_exists($user, 'hasRole')) {
            return;
        }

        try {
            if (!$user->hasRole('Owner')) {
                $user->assignRole('Owner');
            }
        } catch (\Throwable $e) {
            // Never break seeding
            return;
        }
    }
}
