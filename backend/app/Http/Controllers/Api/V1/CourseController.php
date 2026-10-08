<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Concerns\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CourseController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of courses with optional search and filtering.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Course::query()->withCount('courseOfferings');

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(function ($q) use ($term) {
                $q->where('course_code', 'like', "%{$term}%")
                    ->orWhere('course_title', 'like', "%{$term}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', strtoupper($request->input('status')));
        }

        $perPage = max(1, min((int) $request->input('per_page', 20), 100));
        $paginated = $query->orderBy('course_code')->paginate($perPage);

        $meta = [
            'current_page' => $paginated->currentPage(),
            'per_page' => $paginated->perPage(),
            'total' => $paginated->total(),
            'last_page' => $paginated->lastPage(),
        ];

        return $this->success(
            CourseResource::collection($paginated->items()),
            'Courses retrieved successfully.',
            Response::HTTP_OK,
            $meta
        );
    }

    /**
     * Store a newly created course in storage.
     */
    public function store(StoreCourseRequest $request): JsonResponse
    {
        $course = Course::create($request->validated());

        return $this->success(
            new CourseResource($course),
            'Course created successfully.',
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified course.
     */
    public function show(Course $course): JsonResponse
    {
        $course->loadCount('courseOfferings');

        return $this->success(
            new CourseResource($course),
            'Course retrieved successfully.'
        );
    }

    /**
     * Update the specified course in storage.
     */
    public function update(UpdateCourseRequest $request, Course $course): JsonResponse
    {
        $course->update($request->validated());

        return $this->success(
            new CourseResource($course),
            'Course updated successfully.'
        );
    }

    /**
     * Remove the specified course from storage.
     */
    public function destroy(Course $course): JsonResponse
    {
        if ($course->courseOfferings()->exists()) {
            return $this->error('Cannot delete course with active course offerings.', Response::HTTP_CONFLICT);
        }

        $course->delete();

        return $this->success(null, 'Course deleted successfully.');
    }
}
