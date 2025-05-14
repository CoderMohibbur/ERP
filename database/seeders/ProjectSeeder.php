<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Client;
use App\Models\User;
use Faker\Factory as Faker;

class ProjectSeeder extends Seeder {
    public function run(): void {
        $faker = Faker::create();
        $client = Client::first();
        $user = User::first();

        foreach (range(1, 5) as $i) {
            Project::create([
                'client_id' => $client->id,
                'name' => $faker->bs,
                'description' => $faker->paragraph,
                'status' => 'active',
                'start_date' => now(),
                'end_date' => now()->addDays(30),
                'created_by' => $user->id,
            ]);
        }
    }
}