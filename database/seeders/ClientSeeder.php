<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\User;
use Faker\Factory as Faker;

class ClientSeeder extends Seeder {
    public function run(): void {
        $faker = Faker::create();
        $user = User::first();

        foreach (range(1, 5) as $i) {
            Client::create([
                'name' => $faker->company,
                'email' => $faker->companyEmail,
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'created_by' => $user->id,
            ]);
        }
    }
}
