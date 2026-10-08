<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Concerns\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEnrollmentRequest;
use App\Http\Requests\UpdateEnrollmentRequest;
use App\Http\Resources\EnrollmentResource;
use App\Models\CourseOffering;
use App\Models\Enrollment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnrollmentController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of enrollments.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $query = Enrollment::query()->with(['student.program', 'courseOffering.course', 'courseOffering.academicTerm', 'grade']);

        if ($user->isStudent()) {
            $studentId = $user->student?->id;
            $query->where('student_id', $studentId);
        } elseif ($user->isInstructor()) {
            $query->whereHas('courseOffering', fn ($q) => $q->where('instructor_id', $user->id));
        }

        if ($request->filled('student_id') && ($user->isAdmin() || $user->isRegistrar())) {
            $query->where('student_id', $request->input('student_id'));
        }
        if ($request->filled('course_offering_id')) {
            $query->where('course_offering_id', $request->input('course_offering_id'));
        }
        if ($request->filled('status')) {
            $query->where('status', strtoupper($request->input('status')));
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
            EnrollmentResource::collection($paginated->items()),
            'Enrollments retrieved successfully.',
            Response::HTTP_OK,
            $meta
        );
    }

    /**
     * Store a newly created enrollment in storage.
     */
    public function store(StoreEnrollmentRequest $request): JsonResponse
    {
        $this->authorize('create', Enrollment::class);

        $validated = $request->validated();

        // Check if student is already enrolled (additional safeguard for 409 Conflict)
        $exists = Enrollment::where('student_id', $validated['student_id'])
            ->where('course_offering_id', $validated['course_offering_id'])
            ->exists();

        if ($exists) {
            return $this->error('The student is already enrolled in this course offering.', Response::HTTP_CONFLICT);
        }

        // Check capacity
        $offering = CourseOffering::findOrFail($validated['course_offering_id']);
        if ($offering->status === 'CLOSED') {
            return $this->error('The selected course offering is closed for enrollment.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $currentEnrolled = $offering->enrollments()->where('status', '!=', 'DROPPED')->count();
        if ($currentEnrolled >= $offering->capacity) {
            return $this->error('The selected course offering has reached full capacity.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $enrollment = Enrollment::create([
            'student_id' => $validated['student_id'],
            'course_offering_id' => $validated['course_offering_id'],
            'enrollment_date' => $validated['enrollment_date'] ?? now()->toDateString(),
            'status' => $validated['status'] ?? 'ENROLLED',
        ]);

        $enrollment->load(['student.program', 'courseOffering.course', 'courseOffering.academicTerm', 'grade']);

        return $this->success(
            new EnrollmentResource($enrollment),
            'Student enrolled successfully.',
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified enrollment.
     */
    public function show(Enrollment $enrollment): JsonResponse
    {
        $this->authorize('view', $enrollment);

        $enrollment->load(['student.program', 'courseOffering.course', 'courseOffering.academicTerm', 'grade']);

        return $this->success(
            new EnrollmentResource($enrollment),
            'Enrollment retrieved successfully.'
        );
    }

    /**
     * Update the specified enrollment status.
     */
    public function update(UpdateEnrollmentRequest $request, Enrollment $enrollment): JsonResponse
    {
        $this->authorize('update', $enrollment);

        $enrollment->update($request->validated());
        $enrollment->load(['student.program', 'courseOffering.course', 'courseOffering.academicTerm', 'grade']);

        return $this->success(
            new EnrollmentResource($enrollment),
            'Enrollment updated successfully.'
        );
    }

    /**
     * Remove the specified enrollment from storage.
     */
    public function destroy(Enrollment $enrollment): JsonResponse
    {
        $this->authorize('delete', $enrollment);

        $enrollment->delete();

        return $this->success(null, 'Enrollment removed successfully.');
    }
}
