<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\JobPost;

class JobPostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = JobPost::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'description' => fake()->text(),
            'status' => fake()->word(),
            'employment_type' => fake()->word(),
            'salary_min' => fake()->numberBetween(0, 100000),
            'salary_max' => fake()->numberBetween(0, 100000),
            'posted_at' => fake()->dateTime(),
            'expires_at' => fake()->dateTime(),
            'location' => fake()->word(),
        ];
    }
}
