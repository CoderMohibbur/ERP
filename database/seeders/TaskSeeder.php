<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\Project;
use App\Models\Employee;
use Faker\Factory as Faker;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $project = Project::first();
        $employee = Employee::first();

        foreach (range(1, 10) as $i) {
            Task::create([
                'project_id' => $project->id,
                'title' => $faker->sentence,
                'priority' => $faker->randomElement(['low', 'medium', 'high']),
                'assigned_to' => $employee->id,
                'progress' => rand(0, 100),
                'due_date' => now()->addDays(rand(5, 30)),
            ]);
        }
    }
}
