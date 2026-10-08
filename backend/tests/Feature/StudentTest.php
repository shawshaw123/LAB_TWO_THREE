<?php

namespace Tests\Feature;

use App\Models\Program;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private Program $program;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
        $this->program = Program::factory()->create();
    }

    public function test_admin_can_create_a_valid_student(): void
    {
        $payload = [
            'student_number' => '2026-00001',
            'first_name' => 'Maria',
            'middle_name' => 'Santos',
            'last_name' => 'Clara',
            'suffix' => null,
            'birth_date' => '2004-05-15',
            'email' => 'maria.clara@example.com',
            'contact_number' => '+63 912 345 6789',
            'address' => 'Manila, Philippines',
            'program_id' => $this->program->id,
            'year_level' => 1,
            'status' => 'ACTIVE',
        ];

        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/v1/students', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'student_number' => '2026-00001',
                    'first_name' => 'Maria',
                    'last_name' => 'Clara',
                ],
            ]);

        $this->assertDatabaseHas('students', ['student_number' => '2026-00001']);
    }

    public function test_validation_fails_on_duplicate_student_number(): void
    {
        Student::factory()->create([
            'student_number' => '2026-00001',
            'program_id' => $this->program->id,
        ]);

        $payload = [
            'student_number' => '2026-00001',
            'first_name' => 'Another',
            'last_name' => 'Student',
            'birth_date' => '2004-01-01',
            'email' => 'unique.email@example.com',
            'program_id' => $this->program->id,
            'year_level' => 1,
        ];

        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/v1/students', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['student_number']);
    }

    public function test_validation_fails_on_invalid_email(): void
    {
        $payload = [
            'student_number' => '2026-00002',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'birth_date' => '2004-01-01',
            'email' => 'not-a-valid-email',
            'program_id' => $this->program->id,
            'year_level' => 1,
        ];

        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/v1/students', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_admin_can_retrieve_student_by_id(): void
    {
        $student = Student::factory()->create(['program_id' => $this->program->id]);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson("/api/v1/students/{$student->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $student->id,
                    'student_number' => $student->student_number,
                ],
            ]);
    }

    public function test_admin_can_update_student(): void
    {
        $student = Student::factory()->create(['program_id' => $this->program->id]);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->putJson("/api/v1/students/{$student->id}", [
                'first_name' => 'UpdatedName',
                'year_level' => 3,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'first_name' => 'UpdatedName',
                    'year_level' => 3,
                ],
            ]);

        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'first_name' => 'UpdatedName',
            'year_level' => 3,
        ]);
    }

    public function test_retrieving_non_existent_student_returns_404(): void
    {
        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/v1/students/99999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Requested resource does not exist.',
            ]);
    }
}
