<?php

namespace Database\Factories;

use App\Models\AcademicTerm;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AcademicTerm>
 */
class AcademicTermFactory extends Factory
{
    protected $model = AcademicTerm::class;

    public function definition(): array
    {
        $year = fake()->numberBetween(2024, 2028);
        $nextYear = $year + 1;

        return [
            'academic_year' => "{$year}-{$nextYear}",
            'term' => fake()->randomElement(['1st Semester', '2nd Semester', 'Summer']),
            'start_date' => now()->subMonths(3)->toDateString(),
            'end_date' => now()->addMonths(2)->toDateString(),
            'status' => 'ACTIVE',
        ];
    }
}
