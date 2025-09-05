<?php

namespace Database\Factories;

use App\Models\Skill;
use App\Models\JobPost;
use App\ExperienceLevel;
use Illuminate\Support\Str;
use App\Models\JobPostSkill;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobPostSkillFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = JobPostSkill::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'job_post_id' => JobPost::factory(),
            'skill_id' => Skill::factory(),
            'experience_level' => fake()->randomElement(ExperienceLevel::cases()),
            'years_of_experience' => fake()->numberBetween(0, 50),
            'is_required' => fake()->boolean(),
        ];
    }
}
