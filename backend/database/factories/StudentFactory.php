<?php

namespace Database\Factories;

use App\Models\Program;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Student>
 */
class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        $year = fake()->numberBetween(2022, 2026);
        $num = fake()->unique()->numberBetween(10000, 99999);

        $firstNames = [
            'Juan', 'Jose', 'Andres', 'Emilio', 'Antonio', 'Apolinario', 'Francisco', 'Manuel', 'Ramon',
            'Diosdado', 'Ferdinand', 'Corazon', 'Benigno', 'Rodrigo', 'Danilo', 'Eduardo', 'Angelo',
            'Christian', 'Joshua', 'Mark', 'Paolo', 'Miguel', 'Rafael', 'Gabriel', 'Daniel', 'Carlo',
            'Jericho', 'Alden', 'Dingdong', 'Maria', 'Clara', 'Gabriela', 'Melchora', 'Teresa', 'Leonor',
            'Juana', 'Angelica', 'Nicole', 'Bea', 'Angel', 'Kathryn', 'Nadine', 'Liza', 'Julia', 'Marian',
        ];

        $lastNames = [
            'Santos', 'Reyes', 'Cruz', 'Bautista', 'Ocampo', 'Garcia', 'Mendoza', 'Torres', 'Tomas',
            'Andrada', 'Del Rosario', 'Flores', 'Morales', 'Castillo', 'Villanueva', 'Ramos', 'Castro',
            'Rivera', 'Aquino', 'Navarro', 'Salazar', 'Mercado', 'Dela Cruz', 'De Leon', 'Gonzales',
            'Lopez', 'Hernandez', 'Perez', 'Sanchez', 'Ramirez', 'Alvarez', 'Gomez', 'Diaz', 'Fernandez',
        ];

        return [
            'user_id' => null,
            'student_number' => "{$year}-{$num}",
            'first_name' => fake()->randomElement($firstNames),
            'middle_name' => fake()->randomElement($lastNames),
            'last_name' => fake()->randomElement($lastNames),
            'suffix' => fake()->randomElement([null, null, null, 'Jr.', 'III']),
            'birth_date' => fake()->dateTimeBetween('-25 years', '-17 years')->format('Y-m-d'),
            'email' => fake()->unique()->safeEmail(),
            'contact_number' => '+63 9'.str_pad((string) fake()->numberBetween(10000000, 99999999), 8, '0', STR_PAD_LEFT),
            'address' => fake()->streetAddress().', Metro Manila, Philippines',
            'program_id' => Program::factory(),
            'year_level' => fake()->numberBetween(1, 4),
            'status' => 'ACTIVE',
        ];
    }
}
