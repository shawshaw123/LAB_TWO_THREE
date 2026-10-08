<?php

namespace Tests\Feature;

use App\Models\Program;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CollectionTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private Program $program1;

    private Program $program2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->admin()->create();
        $this->program1 = Program::factory()->create(['code' => 'BSIT']);
        $this->program2 = Program::factory()->create(['code' => 'BSCS']);

        Student::factory()->create([
            'student_number' => '2026-00001',
            'first_name' => 'Alice',
            'last_name' => 'Alonzo',
            'program_id' => $this->program1->id,
            'year_level' => 1,
            'status' => 'ACTIVE',
        ]);

        Student::factory()->create([
            'student_number' => '2026-00002',
            'first_name' => 'Bob',
            'last_name' => 'Bernardo',
            'program_id' => $this->program1->id,
            'year_level' => 2,
            'status' => 'INACTIVE',
        ]);

        Student::factory()->create([
            'student_number' => '2026-00003',
            'first_name' => 'Charlie',
            'last_name' => 'Cruz',
            'program_id' => $this->program2->id,
            'year_level' => 3,
            'status' => 'ACTIVE',
        ]);
    }

    public function test_search_filters_collection_by_keyword(): void
    {
        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/v1/students?search=Alice');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.first_name', 'Alice');
    }

    public function test_filtering_by_program_and_status(): void
    {
        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson("/api/v1/students?program_id={$this->program1->id}&status=ACTIVE");

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.first_name', 'Alice');
    }

    public function test_sorting_by_last_name_descending(): void
    {
        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/v1/students?sort=last_name&order=desc');

        $response->assertStatus(200)
            ->assertJsonPath('data.0.last_name', 'Cruz')
            ->assertJsonPath('data.2.last_name', 'Alonzo');
    }

    public function test_pagination_metadata_structure_and_limits(): void
    {
        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/v1/students?page=1&per_page=2');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'success',
                'message',
                'data',
                'meta' => [
                    'current_page',
                    'per_page',
                    'total',
                    'last_page',
                    'from',
                    'to',
                ],
            ])
            ->assertJsonPath('meta.total', 3)
            ->assertJsonPath('meta.per_page', 2)
            ->assertJsonPath('meta.last_page', 2);
    }
}
