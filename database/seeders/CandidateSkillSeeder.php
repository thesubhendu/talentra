<?php

namespace Database\Seeders;

use App\Models\CandidateSkill;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CandidateSkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CandidateSkill::factory()->count(5)->create();
    }
}
