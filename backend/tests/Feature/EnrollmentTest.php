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

class EnrollmentTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private Student $student;

    private CourseOffering $offering;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->admin()->create();
        $program = Program::factory()->create();
        $this->student = Student::factory()->create(['program_id' => $program->id]);

        $course = Course::factory()->create();
        $term = AcademicTerm::factory()->create();
        $instructor = User::factory()->instructor()->create();

        $this->offering = CourseOffering::factory()->create([
            'course_id' => $course->id,
            'academic_term_id' => $term->id,
            'instructor_id' => $instructor->id,
            'capacity' => 30,
            'status' => 'OPEN',
        ]);
    }

    public function test_admin_can_enroll_student_in_course_offering(): void
    {
        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/v1/enrollments', [
                'student_id' => $this->student->id,
                'course_offering_id' => $this->offering->id,
                'enrollment_date' => now()->toDateString(),
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'student_id' => $this->student->id,
                    'course_offering_id' => $this->offering->id,
                    'status' => 'ENROLLED',
                ],
            ]);

        $this->assertDatabaseHas('enrollments', [
            'student_id' => $this->student->id,
            'course_offering_id' => $this->offering->id,
        ]);
    }

    public function test_duplicate_enrollment_in_same_offering_is_prevented(): void
    {
        Enrollment::create([
            'student_id' => $this->student->id,
            'course_offering_id' => $this->offering->id,
            'enrollment_date' => now()->toDateString(),
            'status' => 'ENROLLED',
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/v1/enrollments', [
                'student_id' => $this->student->id,
                'course_offering_id' => $this->offering->id,
            ]);

        // Caught by validation rule or 409 conflict
        $this->assertTrue(in_array($response->status(), [409, 422], true));
    }

    public function test_enrollment_fails_with_invalid_references(): void
    {
        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/v1/enrollments', [
                'student_id' => 99999,
                'course_offering_id' => 88888,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['student_id', 'course_offering_id']);
    }
}
