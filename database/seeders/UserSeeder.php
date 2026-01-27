<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Users table must exist
        if (!Schema::hasTable('users')) {
            $this->command?->warn('Skipping UserSeeder: users table not found.');
            return;
        }

        // If spatie tables not present, create users only (still safe)
        $hasRolesTable = Schema::hasTable('roles');
        $hasModelHasRolesTable = Schema::hasTable('model_has_roles');
        $hasPermissionsTable = Schema::hasTable('permissions');
        $hasRoleHasPermissionsTable = Schema::hasTable('role_has_permissions');

        $password = bcrypt('password'); // testing only

        // 1) Create Users (idempotent)
        $owner = User::firstOrCreate(
            ['email' => 'owner@test.com'],
            ['name' => 'Owner', 'password' => $password]
        );

        $laravelLead = User::firstOrCreate(
            ['email' => 'laravellead@test.com'],
            ['name' => 'Laravel Lead', 'password' => $password]
        );

        $wpLead = User::firstOrCreate(
            ['email' => 'wplead@test.com'],
            ['name' => 'WP Lead', 'password' => $password]
        );

        $accounts = User::firstOrCreate(
            ['email' => 'accounts@test.com'],
            ['name' => 'Accounts', 'password' => $password]
        );

        // If Spatie tables are missing, stop here safely.
        if (!$hasRolesTable || !$hasModelHasRolesTable) {
            $this->command?->warn('Spatie role tables not found. Users created without roles.');
            return;
        }

        // 2) Create Roles (idempotent)
        // Use fully qualified classes to avoid hard failure if package missing
        $roleClass = \Spatie\Permission\Models\Role::class;

        /** @var \Spatie\Permission\Models\Role $ownerRole */
        $ownerRole = $roleClass::firstOrCreate(['name' => 'Owner'], ['guard_name' => 'web']);
        $laravelLeadRole = $roleClass::firstOrCreate(['name' => 'LaravelLead'], ['guard_name' => 'web']);
        $wpLeadRole = $roleClass::firstOrCreate(['name' => 'WPLead'], ['guard_name' => 'web']);
        $accountsRole = $roleClass::firstOrCreate(['name' => 'Accounts'], ['guard_name' => 'web']);

        // 3) Assign Roles to users (idempotent)
        // syncRoles is safe and prevents duplicates
        $owner->syncRoles([$ownerRole]);
        $laravelLead->syncRoles([$laravelLeadRole]);
        $wpLead->syncRoles([$wpLeadRole]);
        $accounts->syncRoles([$accountsRole]);

        // 4) Make Owner role "all access" by assigning all permissions if available
        if ($hasPermissionsTable && $hasRoleHasPermissionsTable) {
            $permissionClass = \Spatie\Permission\Models\Permission::class;
            $allPermissions = $permissionClass::query()
                ->where('guard_name', 'web')
                ->pluck('name')
                ->all();

            if (!empty($allPermissions)) {
                $ownerRole->syncPermissions($allPermissions);
                $this->command?->info('Owner role synced with all permissions.');
            } else {
                $this->command?->warn('No permissions found to assign to Owner role (permissions table empty).');
            }
        } else {
            $this->command?->warn('Permission tables not found. Owner role created but permissions not assigned.');
        }

        $this->command?->info('UserSeeder completed: Owner/LaravelLead/WPLead/Accounts created.');
    }
}
