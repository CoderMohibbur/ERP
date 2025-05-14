<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Invoice;
use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use Faker\Factory as Faker;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $client = Client::first();
        $project = Project::first();
        $user = User::first();

        foreach (range(1, 5) as $i) {
            $total = $faker->randomFloat(2, 500, 2000);
            $paid = $faker->randomFloat(2, 0, $total);

            Invoice::create([
                'client_id' => $client->id,
                'project_id' => $project->id,
                'total_amount' => $total,
                'due_amount' => $total - $paid,
                'status' => 'unpaid',
                'created_by' => $user->id,
            ]);
        }
    }
}
