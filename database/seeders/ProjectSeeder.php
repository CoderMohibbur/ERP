<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Client;
use App\Models\User;
use Faker\Factory as Faker;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $client = Client::first();
        $user = User::first();

        foreach (range(1, 5) as $i) {
            Project::create([
                'title' => $faker->catchPhrase, // ✅ previously 'name'
                'client_id' => $client->id,
                'description' => $faker->paragraph,
                'status' => 'active',
                'deadline' => now()->addDays(rand(15, 60)), // ✅ instead of start_date & end_date
                'created_by' => $user->id,
            ]);
        }
    }
}
