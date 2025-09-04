<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\JobPost;
use App\Models\JobPostSkill;
use App\Models\Skill;

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
            'experience_level' => fake()->word(),
            'years_of_experience' => fake()->numberBetween(-10000, 10000),
            'is_required' => fake()->boolean(),
        ];
    }
}
