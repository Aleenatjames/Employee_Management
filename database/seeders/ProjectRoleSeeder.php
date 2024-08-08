<?php

namespace Database\Seeders;

use App\Models\ProjectRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProjectRole::create([
            'code' => 'PM',
            'name' => 'Project Manager',
        ]);

        ProjectRole::create([
            'code' => 'DEV',
            'name' => 'Developer',
        ]);

        ProjectRole::create([
            'code' => 'QA',
            'name' => 'Test Manager',
        ]);
    }
    
}
