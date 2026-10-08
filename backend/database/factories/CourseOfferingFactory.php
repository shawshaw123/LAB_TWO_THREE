<?php

namespace Database\Factories;

use App\Models\AcademicTerm;
use App\Models\Course;
use App\Models\CourseOffering;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CourseOffering>
 */
class CourseOfferingFactory extends Factory
{
    protected $model = CourseOffering::class;

    public function definition(): array
    {
        return [
            'course_id' => Course::factory(),
            'academic_term_id' => AcademicTerm::factory(),
            'instructor_id' => User::factory()->instructor(),
            'section' => fake()->randomElement(['BSIT-1A', 'BSIT-2A', 'BSIT-3A', 'BSCS-1B', 'BSCS-2B', 'BSCS-3A']),
            'schedule' => fake()->randomElement(['MWF 08:00-09:30', 'TTH 10:00-11:30', 'MWF 13:00-14:30', 'TTH 14:00-15:30', 'SAT 09:00-12:00']),
            'room' => fake()->randomElement(['Room 101', 'Room 205', 'Lab 301', 'Lab 302', 'Online']),
            'capacity' => fake()->numberBetween(30, 50),
            'status' => 'OPEN',
        ];
    }
}
