<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\ProjectNote;
use App\Models\Project;
use App\Models\User;
use Faker\Factory as Faker;

class ProjectNoteSeeder extends Seeder {
    public function run(): void {
        $faker = Faker::create();
        $project = Project::first();
        $user = User::first();

        foreach (range(1, 5) as $i) {
            ProjectNote::create([
                'project_id' => $project->id,
                'note' => $faker->sentence(10),
                'created_by' => $user->id,
            ]);
        }
    }
}