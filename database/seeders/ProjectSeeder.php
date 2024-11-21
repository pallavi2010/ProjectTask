<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;


class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       
        $projects = [
            ['name' => 'Project One'],
            ['name' => 'Project Two'],
            ['name' => 'Project Three'],
            ['name' => 'Project Four'],
            ['name' => 'Project Five'],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }
    }
}
