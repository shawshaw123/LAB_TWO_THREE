<?php

namespace Database\Factories;

use App\Models\Enrollment;
use App\Models\Grade;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Grade>
 */
class GradeFactory extends Factory
{
    protected $model = Grade::class;

    public function definition(): array
    {
        $midterm = fake()->randomFloat(2, 1.0, 3.5);
        $final = fake()->randomFloat(2, 1.0, 3.5);
        $remarks = ($final <= 3.00) ? 'PASSED' : 'FAILED';

        return [
            'enrollment_id' => Enrollment::factory(),
            'midterm_grade' => $midterm,
            'final_grade' => $final,
            'remarks' => $remarks,
        ];
    }
}
