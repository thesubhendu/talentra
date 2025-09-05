<?php

namespace Database\Factories;

use App\EmploymentType;
use App\Models\JobPost;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'employment_type' => fake()->randomElement(EmploymentType::cases()),
            'salary_min' => fake()->numberBetween(0, 100000),
            'salary_max' => fake()->numberBetween(0, 100000),
            'posted_at' => fake()->dateTime(),
            'expires_at' => fake()->dateTime(),
            'location' => fake()->word(),
        ];
    }
}
