<?php

namespace Tests\Feature;

use App\Models\AcademicTerm;
use App\Models\Course;
use App\Models\CourseOffering;
use App\Models\Enrollment;
use App\Models\Program;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GradeTest extends TestCase
{
    use RefreshDatabase;

    private User $instructor1;

    private User $instructor2;

    private Enrollment $enrollment;

    protected function setUp(): void
    {
        parent::setUp();

        $this->instructor1 = User::factory()->instructor()->create();
        $this->instructor2 = User::factory()->instructor()->create();

        $program = Program::factory()->create();
        $student = Student::factory()->create(['program_id' => $program->id]);
        $course = Course::factory()->create();
        $term = AcademicTerm::factory()->create();

        $offering = CourseOffering::factory()->create([
            'course_id' => $course->id,
            'academic_term_id' => $term->id,
            'instructor_id' => $this->instructor1->id,
        ]);

        $this->enrollment = Enrollment::factory()->create([
            'student_id' => $student->id,
            'course_offering_id' => $offering->id,
        ]);
    }

    public function test_authorized_instructor_can_encode_grade(): void
    {
        $response = $this->actingAs($this->instructor1, 'sanctum')
            ->postJson('/api/v1/grades', [
                'enrollment_id' => $this->enrollment->id,
                'midterm_grade' => 1.50,
                'final_grade' => 1.75,
                'remarks' => 'PASSED',
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'enrollment_id' => $this->enrollment->id,
                    'midterm_grade' => 1.50,
                    'final_grade' => 1.75,
                    'remarks' => 'PASSED',
                ],
            ]);

        $this->assertDatabaseHas('grades', [
            'enrollment_id' => $this->enrollment->id,
            'final_grade' => 1.75,
        ]);
    }

    public function test_unauthorized_instructor_cannot_encode_grade(): void
    {
        $response = $this->actingAs($this->instructor2, 'sanctum')
            ->postJson('/api/v1/grades', [
                'enrollment_id' => $this->enrollment->id,
                'midterm_grade' => 1.50,
                'final_grade' => 1.75,
            ]);

        $response->assertStatus(403);
    }

    public function test_invalid_grade_range_is_rejected(): void
    {
        $response = $this->actingAs($this->instructor1, 'sanctum')
            ->postJson('/api/v1/grades', [
                'enrollment_id' => $this->enrollment->id,
                'midterm_grade' => 9.50, // Beyond 5.00
                'final_grade' => 0.50,  // Below 1.00
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['midterm_grade', 'final_grade']);
    }

    public function test_grade_creation_fails_with_invalid_enrollment_id(): void
    {
        $response = $this->actingAs($this->instructor1, 'sanctum')
            ->postJson('/api/v1/grades', [
                'enrollment_id' => 99999,
                'midterm_grade' => 2.00,
                'final_grade' => 2.00,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['enrollment_id']);
    }
}
