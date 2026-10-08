<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Concerns\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Http\Resources\EnrollmentResource;
use App\Http\Resources\GradeResource;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StudentController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource with search, filtering, sorting, and pagination.
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Student::class);

        $query = Student::query()->with('program');

        // If instructor, strictly scope to students enrolled in course offerings taught by this instructor
        if ($request->user()->isInstructor()) {
            $query->whereHas('enrollments.courseOffering', fn ($q) => $q->where('instructor_id', $request->user()->id));
        }

        // Search
        if ($request->filled('search')) {
            $query->search($request->input('search'));
        }

        // Filters
        if ($request->filled('program_id')) {
            $query->where('program_id', $request->input('program_id'));
        }
        if ($request->filled('year_level')) {
            $query->where('year_level', $request->input('year_level'));
        }
        if ($request->filled('status')) {
            $query->where('status', strtoupper($request->input('status')));
        }

        // Sorting
        $allowedSorts = ['id', 'student_number', 'first_name', 'last_name', 'year_level', 'created_at'];
        $sortBy = in_array($request->input('sort'), $allowedSorts, true) ? $request->input('sort') : 'id';
        $direction = strtolower($request->input('order', 'asc')) === 'desc' ? 'desc' : 'asc';
        $query->orderBy($sortBy, $direction);

        // Pagination
        $perPage = max(1, min((int) $request->input('per_page', 15), 100));
        $paginated = $query->paginate($perPage);

        $meta = [
            'current_page' => $paginated->currentPage(),
            'per_page' => $paginated->perPage(),
            'total' => $paginated->total(),
            'last_page' => $paginated->lastPage(),
            'from' => $paginated->firstItem(),
            'to' => $paginated->lastItem(),
        ];

        return $this->success(
            StudentResource::collection($paginated->items()),
            'Students retrieved successfully.',
            Response::HTTP_OK,
            $meta
        );
    }

    /**
     * Store a newly created student in storage.
     */
    public function store(StoreStudentRequest $request): JsonResponse
    {
        $this->authorize('create', Student::class);

        $student = Student::create($request->validated());
        $student->load('program');

        return $this->success(
            new StudentResource($student),
            'Student created successfully.',
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified student (object-level authorization enforced).
     */
    public function show(Student $student): JsonResponse
    {
        $this->authorize('view', $student);

        $student->load('program');

        return $this->success(
            new StudentResource($student),
            'Student retrieved successfully.'
        );
    }

    /**
     * Update the specified student in storage.
     */
    public function update(UpdateStudentRequest $request, Student $student): JsonResponse
    {
        $this->authorize('update', $student);

        $student->update($request->validated());
        $student->load('program');

        return $this->success(
            new StudentResource($student),
            'Student updated successfully.'
        );
    }

    /**
     * Remove the specified student from storage.
     */
    public function destroy(Student $student): JsonResponse
    {
        $this->authorize('delete', $student);

        $student->delete();

        return $this->success(null, 'Student deleted successfully.');
    }

    /**
     * Retrieve all enrollments for a specific student.
     */
    public function enrollments(Student $student): JsonResponse
    {
        $this->authorize('view', $student);

        $enrollments = $student->enrollments()
            ->with(['courseOffering.course', 'courseOffering.academicTerm', 'grade'])
            ->get();

        return $this->success(
            EnrollmentResource::collection($enrollments),
            'Student enrollments retrieved successfully.'
        );
    }

    /**
     * Retrieve all grades for a specific student.
     */
    public function grades(Student $student): JsonResponse
    {
        $this->authorize('view', $student);

        $grades = $student->grades()
            ->with(['enrollment.courseOffering.course', 'enrollment.courseOffering.academicTerm'])
            ->get();

        return $this->success(
            GradeResource::collection($grades),
            'Student grades retrieved successfully.'
        );
    }

    /**
     * Retrieve aggregated student academic record grouped by academic term.
     */
    public function academicRecord(Student $student): JsonResponse
    {
        $this->authorize('viewAcademicRecord', $student);

        $enrollments = $student->enrollments()
            ->with(['courseOffering.course', 'courseOffering.academicTerm', 'grade'])
            ->get();

        // Group by academic term
        $grouped = $enrollments->groupBy(function ($enrollment) {
            $term = $enrollment->courseOffering->academicTerm;

            return $term ? "{$term->academic_year} - {$term->term}" : 'Unknown Term';
        });

        $termRecords = [];
        $totalEarnedUnits = 0.0;
        $totalGradePoints = 0.0;
        $gradedUnits = 0.0;

        foreach ($grouped as $termKey => $termEnrollments) {
            $courses = [];
            $termUnits = 0.0;
            $termWeightedGrade = 0.0;
            $termGradedUnits = 0.0;

            foreach ($termEnrollments as $enr) {
                $course = $enr->courseOffering->course;
                $grade = $enr->grade;
                $units = (float) ($course ? $course->units : 0);
                $finalGrade = $grade ? (float) $grade->final_grade : null;
                $remarks = $grade ? $grade->remarks : ($enr->status === 'ENROLLED' ? 'IN PROGRESS' : $enr->status);

                if ($finalGrade !== null && $finalGrade > 0) {
                    $termWeightedGrade += ($finalGrade * $units);
                    $termGradedUnits += $units;
                    $totalGradePoints += ($finalGrade * $units);
                    $gradedUnits += $units;
                }

                if ($remarks === 'PASSED') {
                    $totalEarnedUnits += $units;
                }
                $termUnits += $units;

                $courses[] = [
                    'enrollment_id' => $enr->id,
                    'course_code' => $course?->course_code,
                    'course_title' => $course?->course_title,
                    'section' => $enr->courseOffering->section,
                    'units' => $units,
                    'midterm_grade' => $grade?->midterm_grade ? (float) $grade->midterm_grade : null,
                    'final_grade' => $finalGrade,
                    'remarks' => $remarks,
                ];
            }

            $termGpa = $termGradedUnits > 0 ? round($termWeightedGrade / $termGradedUnits, 2) : null;

            $termRecords[] = [
                'term' => $termKey,
                'total_units' => $termUnits,
                'term_gwa' => $termGpa,
                'courses' => $courses,
            ];
        }

        $cumulativeGwa = $gradedUnits > 0 ? round($totalGradePoints / $gradedUnits, 2) : null;

        $student->load('program');

        return $this->success([
            'student' => [
                'id' => $student->id,
                'student_number' => $student->student_number,
                'name' => $student->full_name,
                'program' => $student->program?->code.' - '.$student->program?->name,
                'year_level' => $student->year_level,
                'status' => $student->status,
            ],
            'summary' => [
                'total_units_earned' => $totalEarnedUnits,
                'cumulative_gwa' => $cumulativeGwa,
                'terms_completed' => count($termRecords),
            ],
            'academic_terms' => $termRecords,
        ], 'Student academic record retrieved successfully.');
    }
}
