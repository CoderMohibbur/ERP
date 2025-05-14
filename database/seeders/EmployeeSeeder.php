<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;
use App\Models\User;
use Faker\Factory as Faker;

class EmployeeSeeder extends Seeder {
    public function run(): void {
        $faker = Faker::create();
        $user = User::first();
        $dept = Department::first();
        $desig = Designation::first();

        foreach (range(1, 10) as $i) {
            Employee::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'department_id' => $dept->id,
                'designation_id' => $desig->id,
                'created_by' => $user->id,
            ]);
        }
    }
}