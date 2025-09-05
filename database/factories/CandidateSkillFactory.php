<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Candidate;
use App\Models\CandidateSkill;
use App\Models\Skill;

class CandidateSkillFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CandidateSkill::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'candidate_id' => Candidate::factory(),
            'skill_id' => Skill::factory(),
            'experience_level' => fake()->word(),
            'years_of_experience' => fake()->numberBetween(0, 50),
        ];
    }
}
