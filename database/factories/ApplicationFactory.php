<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Application;
use App\Models\Candidate;
use App\Models\JobPost;

class ApplicationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Application::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'job_post_id' => JobPost::factory(),
            'candidate_id' => Candidate::factory(),
            'status' => fake()->word(),
            'cover_letter' => fake()->text(),
            'resume_url' => fake()->word(),
        ];
    }
}
