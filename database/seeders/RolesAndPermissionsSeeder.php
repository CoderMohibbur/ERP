<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Clear cached roles/permissions
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $guard = config('auth.defaults.guard', 'web');

        $permissionNames = $this->buildAllPermissions();

        DB::transaction(function () use ($permissionNames, $guard) {
            // 1) Create permissions (idempotent)
            foreach ($permissionNames as $name) {
                Permission::findOrCreate($name, $guard);
            }

            // 2) Create roles (idempotent) — per spec
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
                // Minimum ERP scopes (wildcard supported + exact permission also exists)
                'lead.*', 'deal.*', 'activity.*',
                'project.*', 'task.*',
                'timelog.*',
                'service.*', 'renewal.*',
                'dashboard.team',

                // Existing modules (project base modules)
                'client.*',
                'project_file.*', 'project_note.*',

                // Finance: read-only
                'invoice.index', 'invoice.list', 'invoice.viewAny', 'invoice.view', 'invoice.show',
                'payment.index', 'payment.list', 'payment.viewAny', 'payment.view', 'payment.show',
                'expense.index', 'expense.list', 'expense.viewAny', 'expense.view', 'expense.show',

                // Reports (read)
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
                // Leads
                'lead.index', 'lead.list', 'lead.viewAny', 'lead.view', 'lead.show',
                'lead.create', 'lead.store', 'lead.update', 'lead.edit',

                // Deals (can move stage + view)
                'deal.index', 'deal.list', 'deal.viewAny', 'deal.view', 'deal.show',
                'deal.updateStage', 'deal.stage', 'deal.stage.update',

                // Activities full (log calls/whatsapp etc.)
                'activity.*',

                // Client read
                'client.index', 'client.list', 'client.viewAny', 'client.view', 'client.show',

                // Renewals read (support can see due list)
                'service.index', 'service.list', 'service.viewAny', 'service.view', 'service.show',
                'renewal.index', 'renewal.list', 'renewal.viewAny', 'renewal.view', 'renewal.show',

                'dashboard.team',
            ];
            $this->role($guard, 'Support')?->syncPermissions(
                $this->resolveByPatterns($supportPatterns, $all)
            );
        });

        // Optional: assign Owner role to first user if user exists and has Spatie trait methods.
        $this->assignOwnerToFirstUserIfPossible($guard);

        // Clear cache again
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    private function role(string $guard, string $name): ?Role
    {
        return Role::query()
            ->where('guard_name', $guard)
            ->where('name', $name)
            ->first();
    }

    /**
     * Build ALL permissions needed:
     * - Spec minimum permissions (lead.* etc) + dashboard.owner/team
     * - Granular CRUD permissions per module (index/show/create/update/delete/restore/forceDelete etc)
     * - Existing modules permissions (Clients/Projects/Tasks/Invoices/Payments/Employees/Attendance…)
     */
    private function buildAllPermissions(): array
    {
        // Modules from spec (Minimum ERP)
        $minimumModules = [
            'lead', 'deal', 'activity',
            'project', 'task',
            'timelog',
            'invoice', 'payment', 'expense',
            'service', 'renewal',
        ];

        // Existing modules in this ERP base (spec says these exist)
        $existingBaseModules = [
            'client', 'client_contact', 'client_note',
            'department', 'designation', 'employee',
            'attendance', 'attendance_setting',
            'project_file', 'project_note',
            'payment_method',
            'invoice_item', 'invoice_item_field',
            'tax_rule',
            // HR add-ons from migrations list
            'employee_history', 'employee_document', 'skill', 'employee_skill',
            'shift', 'employee_shift',
            'employee_dependent', 'employee_resignation', 'employee_disciplinary_action',
            // other possible admin/support areas
            'discount_type',
        ];

        // Admin/system modules (Owner-only typically)
        $systemModules = [
            'user', 'role', 'permission', 'setting',
            'report',
        ];

        $modules = array_values(array_unique(array_merge($minimumModules, $existingBaseModules, $systemModules)));

        $permissions = [];

        // CRUD-like permissions (create BOTH wildcard and granular so you can use whichever in middleware/checks)
        foreach ($modules as $module) {
            $permissions = array_merge($permissions, $this->crudPermissions($module));
        }

        // Dashboard permissions (spec)
        $permissions[] = 'dashboard.*';
        $permissions[] = 'dashboard.owner';
        $permissions[] = 'dashboard.team';

        // Special actions (commonly used in controllers)
        $permissions = array_merge($permissions, [
            // Deals
            'deal.updateStage',
            'deal.stage',
            'deal.stage.update',
            'deal.markWon',
            'deal.markLost',

            // Tasks / Timelog timers (spec mentions start/stop; pause/resume idea also included)
            'task.timer.start',
            'task.timer.stop',
            'task.timer.pause',
            'task.timer.resume',

            'timelog.start',
            'timelog.stop',
            'timelog.pause',
            'timelog.resume',

            // Renewals/invoice generation
            'service.renewals.generateInvoice',
            'service.renewal.generateInvoice',
            'renewal.generateInvoice',
        ]);

        // Make unique + sorted for stable seeding
        $permissions = array_values(array_unique(array_filter($permissions)));

        sort($permissions);

        return $permissions;
    }

    private function crudPermissions(string $module): array
    {
        // Wildcard (as spec shows lead.* etc) + granular permissions
        return [
            "{$module}.*",

            // Common index/list/view
            "{$module}.index",
            "{$module}.list",
            "{$module}.viewAny",
            "{$module}.view",
            "{$module}.show",

            // Create/update/delete
            "{$module}.create",
            "{$module}.store",
            "{$module}.edit",
            "{$module}.update",
            "{$module}.destroy",
            "{$module}.delete",

            // Optional lifecycle
            "{$module}.restore",
            "{$module}.forceDelete",

            // Extra common actions
            "{$module}.export",
            "{$module}.import",
        ];
    }

    /**
     * Resolve given patterns into real permissions list.
     * Supports patterns like: lead.*, invoice.view, etc.
     */
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

        // Ensure it's an Eloquent model
        if (!is_subclass_of($userModel, Model::class)) {
            return;
        }

        /** @var \Illuminate\Database\Eloquent\Model|null $user */
        $user = $userModel::query()->orderBy('id')->first();
        if (!$user) {
            return;
        }

        // Only if Spatie trait methods exist
        if (!method_exists($user, 'assignRole') || !method_exists($user, 'hasRole')) {
            return;
        }

        try {
            if (!$user->hasRole('Owner', $guard)) {
                $user->assignRole('Owner');
            }
        } catch (\Throwable $e) {
            // Keep seeder "no error" — don't break seeding if user model isn't fully ready
            return;
        }
    }
}
    