<?php

// ✅ Seeder: InvoiceSeeder
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Invoice;
use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use Faker\Factory as Faker;

class InvoiceSeeder extends Seeder {
    public function run(): void {
        $faker = Faker::create();
        $client = Client::first();
        $project = Project::first();
        $user = User::first();

        foreach (range(1, 5) as $i) {
            Invoice::create([
                'client_id' => $client->id,
                'project_id' => $project->id,
                'invoice_number' => 'INV-' . $faker->unique()->randomNumber(5),
                'issue_date' => now(),
                'due_date' => now()->addDays(15),
                'total_amount' => $faker->randomFloat(2, 500, 2000),
                'status' => 'unpaid',
                'created_by' => $user->id,
            ]);
        }
    }
}