<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Department;
use Faker\Factory as Faker;

class DepartmentSeeder extends Seeder {
    public function run(): void {
        $faker = Faker::create();
        foreach (range(1, 5) as $i) {
            Department::create([ 'name' => $faker->company ]);
        }
    }
}