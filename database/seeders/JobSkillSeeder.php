<?php

namespace Database\Seeders;

use App\Models\JobPostSkill;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobSkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JobPostSkill::factory()->count(5)->create();
    }
}
