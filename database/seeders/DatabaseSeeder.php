<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'remember_token' => null,
        ]);
         $this->call([
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
        // RolesAndPermissionsSeeder::class,
        // ServiceTypeSeeder::class,
        // TaskTemplateSeeder::class,
    ]);

    }
}
