<?php

namespace Database\Factories;

use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Program>
 */
class ProgramFactory extends Factory
{
    protected $model = Program::class;

    public function definition(): array
    {
        $code = strtoupper(fake()->unique()->bothify('BS??'));

        return [
            'code' => $code,
            'name' => 'Bachelor of Science in '.fake()->words(2, true),
            'description' => fake()->paragraph(),
            'status' => 'ACTIVE',
        ];
    }
}
