<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Concerns\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAcademicTermRequest;
use App\Http\Requests\UpdateAcademicTermRequest;
use App\Http\Resources\AcademicTermResource;
use App\Models\AcademicTerm;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AcademicTermController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of academic terms.
     */
    public function index(Request $request): JsonResponse
    {
        $query = AcademicTerm::query();

        if ($request->filled('status')) {
            $query->where('status', strtoupper($request->input('status')));
        }
        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->input('academic_year'));
        }

        $perPage = max(1, min((int) $request->input('per_page', 20), 100));
        $paginated = $query->orderByDesc('academic_year')->orderBy('term')->paginate($perPage);

        $meta = [
            'current_page' => $paginated->currentPage(),
            'per_page' => $paginated->perPage(),
            'total' => $paginated->total(),
            'last_page' => $paginated->lastPage(),
        ];

        return $this->success(
            AcademicTermResource::collection($paginated->items()),
            'Academic terms retrieved successfully.',
            Response::HTTP_OK,
            $meta
        );
    }

    /**
     * Store a newly created academic term in storage.
     */
    public function store(StoreAcademicTermRequest $request): JsonResponse
    {
        $term = AcademicTerm::create($request->validated());

        return $this->success(
            new AcademicTermResource($term),
            'Academic term created successfully.',
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified academic term.
     */
    public function show(AcademicTerm $academicTerm): JsonResponse
    {
        return $this->success(
            new AcademicTermResource($academicTerm),
            'Academic term retrieved successfully.'
        );
    }

    /**
     * Update the specified academic term in storage.
     */
    public function update(UpdateAcademicTermRequest $request, AcademicTerm $academicTerm): JsonResponse
    {
        $academicTerm->update($request->validated());

        return $this->success(
            new AcademicTermResource($academicTerm),
            'Academic term updated successfully.'
        );
    }

    /**
     * Remove the specified academic term from storage.
     */
    public function destroy(AcademicTerm $academicTerm): JsonResponse
    {
        if ($academicTerm->courseOfferings()->exists()) {
            return $this->error('Cannot delete academic term with active course offerings.', Response::HTTP_CONFLICT);
        }

        $academicTerm->delete();

        return $this->success(null, 'Academic term deleted successfully.');
    }
}
