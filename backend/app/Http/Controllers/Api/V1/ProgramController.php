<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Concerns\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProgramRequest;
use App\Http\Requests\UpdateProgramRequest;
use App\Http\Resources\ProgramResource;
use App\Models\Program;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProgramController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of programs with optional search and filtering.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Program::query()->withCount('students');

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(function ($q) use ($term) {
                $q->where('code', 'like', "%{$term}%")
                    ->orWhere('name', 'like', "%{$term}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', strtoupper($request->input('status')));
        }

        $perPage = max(1, min((int) $request->input('per_page', 20), 100));
        $paginated = $query->orderBy('code')->paginate($perPage);

        $meta = [
            'current_page' => $paginated->currentPage(),
            'per_page' => $paginated->perPage(),
            'total' => $paginated->total(),
            'last_page' => $paginated->lastPage(),
        ];

        return $this->success(
            ProgramResource::collection($paginated->items()),
            'Programs retrieved successfully.',
            Response::HTTP_OK,
            $meta
        );
    }

    /**
     * Store a newly created program in storage.
     */
    public function store(StoreProgramRequest $request): JsonResponse
    {
        $program = Program::create($request->validated());

        return $this->success(
            new ProgramResource($program),
            'Program created successfully.',
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified program.
     */
    public function show(Program $program): JsonResponse
    {
        $program->loadCount('students');

        return $this->success(
            new ProgramResource($program),
            'Program retrieved successfully.'
        );
    }

    /**
     * Update the specified program in storage.
     */
    public function update(UpdateProgramRequest $request, Program $program): JsonResponse
    {
        $program->update($request->validated());

        return $this->success(
            new ProgramResource($program),
            'Program updated successfully.'
        );
    }

    /**
     * Remove the specified program from storage.
     */
    public function destroy(Program $program): JsonResponse
    {
        if ($program->students()->exists()) {
            return $this->error('Cannot delete program with existing enrolled students.', Response::HTTP_CONFLICT);
        }

        $program->delete();

        return $this->success(null, 'Program deleted successfully.');
    }
}
