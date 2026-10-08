<?php

namespace Tests\Feature;

use App\Models\AcademicTerm;
use App\Models\Course;
use App\Models\CourseOffering;
use App\Models\Program;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private Program $program;

    protected function setUp(): void
    {
        parent::setUp();
        $this->program = Program::factory()->create();
    }

    public function test_student_role_is_forbidden_from_creating_programs(): void
    {
        $studentUser = User::factory()->student()->create();

        $response = $this->actingAs($studentUser, 'sanctum')
            ->postJson('/api/v1/programs', [
                'code' => 'TEST',
                'name' => 'Unauthorized Program',
            ]);

        $response->assertStatus(403);
    }

    public function test_student_role_is_forbidden_from_creating_students(): void
    {
        $studentUser = User::factory()->student()->create();

        $response = $this->actingAs($studentUser, 'sanctum')
            ->postJson('/api/v1/students', [
                'student_number' => '2026-99999',
                'first_name' => 'Hack',
                'last_name' => 'Attempt',
                'birth_date' => '2004-01-01',
                'email' => 'hack@example.com',
                'program_id' => $this->program->id,
                'year_level' => 1,
            ]);

        $response->assertStatus(403);
    }

    public function test_object_level_authorization_student_cannot_view_another_students_record(): void
    {
        $user1 = User::factory()->student()->create(['email' => 'student1@example.com']);
        $student1 = Student::factory()->create(['user_id' => $user1->id, 'email' => 'student1@example.com', 'program_id' => $this->program->id]);

        $user2 = User::factory()->student()->create(['email' => 'student2@example.com']);
        $student2 = Student::factory()->create(['user_id' => $user2->id, 'email' => 'student2@example.com', 'program_id' => $this->program->id]);

        // Student 1 tries to access Student 2's profile
        $response = $this->actingAs($user1, 'sanctum')
            ->getJson("/api/v1/students/{$student2->id}");

        $response->assertStatus(403);

        // Student 1 tries to access Student 2's academic record
        $recordResponse = $this->actingAs($user1, 'sanctum')
            ->getJson("/api/v1/students/{$student2->id}/academic-record");

        $recordResponse->assertStatus(403);
    }

    public function test_object_level_authorization_student_can_view_own_record(): void
    {
        $user1 = User::factory()->student()->create(['email' => 'student1@example.com']);
        $student1 = Student::factory()->create(['user_id' => $user1->id, 'email' => 'student1@example.com', 'program_id' => $this->program->id]);

        // Student 1 views their own profile
        $response = $this->actingAs($user1, 'sanctum')
            ->getJson("/api/v1/students/{$student1->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $student1->id,
                    'student_number' => $student1->student_number,
                ],
            ]);

        // Student 1 views their own academic record
        $recordResponse = $this->actingAs($user1, 'sanctum')
            ->getJson("/api/v1/students/{$student1->id}/academic-record");

        $recordResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'student' => [
                        'id' => $student1->id,
                    ],
                ],
            ]);
    }

    public function test_instructor_cannot_view_students_of_another_instructors_offering(): void
    {
        $instructor1 = User::factory()->instructor()->create();
        $instructor2 = User::factory()->instructor()->create();

        $course = Course::factory()->create();
        $term = AcademicTerm::factory()->create();

        $offering = CourseOffering::factory()->create([
            'course_id' => $course->id,
            'academic_term_id' => $term->id,
            'instructor_id' => $instructor1->id,
        ]);

        // Instructor 2 attempts to view class roster of offering belonging to instructor 1
        $response = $this->actingAs($instructor2, 'sanctum')
            ->getJson("/api/v1/course-offerings/{$offering->id}/students");

        $response->assertStatus(403);

        // Instructor 1 views their own class roster
        $validResponse = $this->actingAs($instructor1, 'sanctum')
            ->getJson("/api/v1/course-offerings/{$offering->id}/students");

        $validResponse->assertStatus(200);
    }
}
