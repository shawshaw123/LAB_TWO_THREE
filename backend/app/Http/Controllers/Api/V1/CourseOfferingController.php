<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Concerns\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseOfferingRequest;
use App\Http\Requests\UpdateCourseOfferingRequest;
use App\Http\Resources\CourseOfferingResource;
use App\Http\Resources\StudentResource;
use App\Models\CourseOffering;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CourseOfferingController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of course offerings with filtering.
     */
    public function index(Request $request): JsonResponse
    {
        $query = CourseOffering::query()
            ->with(['course', 'academicTerm', 'instructor'])
            ->withCount('enrollments');

        $user = $request->user();

        // If instructor, by default or on filter can view their assigned offerings
        if ($user->isInstructor() && ! $request->has('all')) {
            $query->where('instructor_id', $user->id);
        }

        if ($request->filled('academic_term_id')) {
            $query->where('academic_term_id', $request->input('academic_term_id'));
        }
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->input('course_id'));
        }
        if ($request->filled('instructor_id')) {
            $query->where('instructor_id', $request->input('instructor_id'));
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
            CourseOfferingResource::collection($paginated->items()),
            'Course offerings retrieved successfully.',
            Response::HTTP_OK,
            $meta
        );
    }

    /**
     * Store a newly created course offering.
     */
    public function store(StoreCourseOfferingRequest $request): JsonResponse
    {
        $offering = CourseOffering::create($request->validated());
        $offering->load(['course', 'academicTerm', 'instructor']);

        return $this->success(
            new CourseOfferingResource($offering),
            'Course offering created successfully.',
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified course offering.
     */
    public function show(CourseOffering $courseOffering): JsonResponse
    {
        $courseOffering->load(['course', 'academicTerm', 'instructor'])->loadCount('enrollments');

        return $this->success(
            new CourseOfferingResource($courseOffering),
            'Course offering retrieved successfully.'
        );
    }

    /**
     * Update the specified course offering.
     */
    public function update(UpdateCourseOfferingRequest $request, CourseOffering $courseOffering): JsonResponse
    {
        $courseOffering->update($request->validated());
        $courseOffering->load(['course', 'academicTerm', 'instructor']);

        return $this->success(
            new CourseOfferingResource($courseOffering),
            'Course offering updated successfully.'
        );
    }

    /**
     * Remove the specified course offering.
     */
    public function destroy(CourseOffering $courseOffering): JsonResponse
    {
        if ($courseOffering->enrollments()->exists()) {
            return $this->error('Cannot delete course offering with enrolled students.', Response::HTTP_CONFLICT);
        }

        $courseOffering->delete();

        return $this->success(null, 'Course offering deleted successfully.');
    }

    /**
     * Display all students enrolled in this course offering.
     * Endpoint: GET /api/v1/course-offerings/{id}/students
     */
    public function students(Request $request, CourseOffering $courseOffering): JsonResponse
    {
        $user = $request->user();

        // Enforce instructor object-level authorization
        if ($user->isInstructor() && $courseOffering->instructor_id !== $user->id) {
            return $this->error('You are not authorized to view students of this course offering.', Response::HTTP_FORBIDDEN);
        }

        if ($user->isStudent()) {
            return $this->error('Students are not authorized to view class rosters.', Response::HTTP_FORBIDDEN);
        }

        $students = $courseOffering->enrollments()
            ->with(['student.program'])
            ->get()
            ->pluck('student')
            ->filter();

        return $this->success(
            StudentResource::collection($students),
            'Course offering students retrieved successfully.'
        );
    }
}
