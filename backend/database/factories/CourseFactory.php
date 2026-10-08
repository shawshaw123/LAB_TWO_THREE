<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Course>
 */
class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        return [
            'course_code' => strtoupper(fake()->unique()->bothify('CS###')),
            'course_title' => ucwords(fake()->words(3, true)),
            'description' => fake()->sentence(),
            'units' => fake()->randomElement([2.0, 3.0, 4.0, 5.0]),
            'status' => 'ACTIVE',
        ];
    }
}
