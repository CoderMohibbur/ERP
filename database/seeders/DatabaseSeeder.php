<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Optional demo user (avoid duplicate on re-seed)
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name'              => 'Test User',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
                'remember_token'    => null,
            ]
        );

        $this->call([
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
            // DepartmentSeeder::class,
            // DesignationSeeder::class,
            // EmployeeSeeder::class,
            // ClientSeeder::class,
            // ClientContactSeeder::class,
            // ProjectSeeder::class,
            // TaskSeeder::class,
            // ProjectNoteSeeder::class,
            // ProjectFileSeeder::class,
            // InvoiceSeeder::class,
            // InvoiceItemSeeder::class,
            // PaymentMethodSeeder::class,
            // PaymentSeeder::class,
            // ServiceTypeSeeder::class,
            // TaskTemplateSeeder::class,
        ]);
    }
}
