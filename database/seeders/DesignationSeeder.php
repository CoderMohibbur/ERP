<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Designation;
use Faker\Factory as Faker;

class DesignationSeeder extends Seeder {
    public function run(): void {
        $faker = Faker::create();
        foreach (range(1, 5) as $i) {
            Designation::create([ 'name' => $faker->jobTitle ]);
        }
    }
}