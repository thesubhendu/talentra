<?php

namespace Database\Factories;

use App\Models\Skill;
use App\ExperienceLevel;
use App\Models\Candidate;
use Illuminate\Support\Str;
use App\Models\CandidateSkill;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'experience_level' => fake()->randomElement(ExperienceLevel::cases()),
            'years_of_experience' => fake()->numberBetween(0, 50),
        ];
    }
}
