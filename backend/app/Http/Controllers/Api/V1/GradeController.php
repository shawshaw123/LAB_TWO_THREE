<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Concerns\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGradeRequest;
use App\Http\Requests\UpdateGradeRequest;
use App\Http\Resources\GradeResource;
use App\Models\Enrollment;
use App\Models\Grade;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GradeController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of grades.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $query = Grade::query()->with([
            'enrollment.student.program',
            'enrollment.courseOffering.course',
            'enrollment.courseOffering.academicTerm',
        ]);

        if ($user->isStudent()) {
            $studentId = $user->student?->id;
            $query->whereHas('enrollment', fn ($q) => $q->where('student_id', $studentId));
        } elseif ($user->isInstructor()) {
            $query->whereHas('enrollment.courseOffering', fn ($q) => $q->where('instructor_id', $user->id));
        }

        if ($request->filled('remarks')) {
            $query->where('remarks', strtoupper($request->input('remarks')));
        }

        $perPage = max(1, min((int) $request->input('per_page', 20), 100));
        $paginated = $query->latest('id')->paginate($perPage);

        $meta = [
            'current_page' => $paginated->currentPage(),
            'per_page' => $paginated->perPage(),
            'total' => $paginated->total(),
            'last_page' => $paginated->lastPage(),
        ];

        return $this->success(
            GradeResource::collection($paginated->items()),
            'Grades retrieved successfully.',
            Response::HTTP_OK,
            $meta
        );
    }

    /**
     * Store a newly created grade in storage.
     */
    public function store(StoreGradeRequest $request): JsonResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        $enrollment = Enrollment::with('courseOffering')->findOrFail($validated['enrollment_id']);

        // Check authorization: if instructor, must be the offering's instructor
        if ($user->isInstructor() && $enrollment->courseOffering->instructor_id !== $user->id) {
            return $this->error('You are not authorized to encode grades for this course offering.', Response::HTTP_FORBIDDEN);
        }

        if ($user->isStudent()) {
            return $this->error('Students are not authorized to encode grades.', Response::HTTP_FORBIDDEN);
        }

        // Auto-calculate remarks if not supplied
        if (empty($validated['remarks']) && isset($validated['final_grade'])) {
            $validated['remarks'] = ((float) $validated['final_grade'] <= 3.00) ? 'PASSED' : 'FAILED';
        }

        $grade = Grade::updateOrCreate(
            ['enrollment_id' => $validated['enrollment_id']],
            $validated
        );
        $grade->load(['enrollment.student', 'enrollment.courseOffering.course']);

        return $this->success(
            new GradeResource($grade),
            'Grade encoded successfully.',
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified grade.
     */
    public function show(Grade $grade): JsonResponse
    {
        $this->authorize('view', $grade);

        $grade->load([
            'enrollment.student.program',
            'enrollment.courseOffering.course',
            'enrollment.courseOffering.academicTerm',
        ]);

        return $this->success(
            new GradeResource($grade),
            'Grade retrieved successfully.'
        );
    }

    /**
     * Update the specified grade in storage.
     */
    public function update(UpdateGradeRequest $request, Grade $grade): JsonResponse
    {
        $this->authorize('update', $grade);

        $validated = $request->validated();

        if (isset($validated['final_grade']) && empty($validated['remarks'])) {
            $validated['remarks'] = ((float) $validated['final_grade'] <= 3.00) ? 'PASSED' : 'FAILED';
        }

        $grade->update($validated);
        $grade->load([
            'enrollment.student',
            'enrollment.courseOffering.course',
            'enrollment.courseOffering.academicTerm',
        ]);

        return $this->success(
            new GradeResource($grade),
            'Grade updated successfully.'
        );
    }
}
