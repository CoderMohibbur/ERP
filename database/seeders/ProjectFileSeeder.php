<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProjectFile;
use App\Models\Project;
use Faker\Factory as Faker;

class ProjectFileSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $project = Project::first();

        foreach (range(1, 3) as $i) {
            ProjectFile::create([
                'project_id' => $project->id,
                'file_path' => 'project_files/sample_' . $i . '.pdf',
                'file_type' => 'pdf', // ✅ ফিল্ড অনুযায়ী ইনপুট
            ]);
        }
    }
}
