<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Concerns\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterStudentRequest;
use App\Http\Resources\StudentResource;
use App\Http\Resources\UserResource;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    use ApiResponse;

    /**
     * Authenticate user and issue personal access token.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return $this->error('Invalid credentials provided.', Response::HTTP_UNPROCESSABLE_ENTITY, [
                'email' => ['The provided credentials do not match our records.'],
            ]);
        }

        if ($user->status !== 'ACTIVE') {
            return $this->error('User account is currently inactive.', Response::HTTP_FORBIDDEN);
        }

        // Revoke old tokens if any
        $token = $user->createToken('api-token', [$user->role])->plainTextToken;

        return $this->success([
            'user' => new UserResource($user),
            'token' => $token,
            'token_type' => 'Bearer',
        ], 'Authenticated successfully.');
    }

    /**
     * Register a new student account.
     */
    public function register(RegisterStudentRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'student',
                'status' => 'ACTIVE',
            ]);

            $parts = explode(' ', trim($validated['name']));
            $firstName = array_shift($parts);
            $lastName = count($parts) > 0 ? implode(' ', $parts) : $firstName;

            $studentNumber = $validated['student_number'] ?? null;
            if (empty($studentNumber)) {
                $maxId = (int) Student::max('id');
                $studentNumber = '2026-'.str_pad((string) ($maxId + 1001), 5, '0', STR_PAD_LEFT);
            }

            Student::create([
                'user_id' => $user->id,
                'student_number' => $studentNumber,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'birth_date' => $validated['birth_date'] ?? '2005-01-01',
                'email' => $validated['email'],
                'contact_number' => $validated['contact_number'] ?? null,
                'address' => $validated['address'] ?? null,
                'program_id' => $validated['program_id'],
                'year_level' => $validated['year_level'] ?? 1,
                'status' => 'ACTIVE',
            ]);

            return $user->load(['student.program']);
        });

        $token = $user->createToken('api-token', [$user->role])->plainTextToken;

        return $this->success([
            'user' => new UserResource($user),
            'student' => new StudentResource($user->student),
            'token' => $token,
            'token_type' => 'Bearer',
        ], 'Student account registered successfully.', Response::HTTP_CREATED);
    }

    /**
     * Invalidate current user token.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(null, 'Logged out successfully.');
    }

    /**
     * Retrieve the currently authenticated user profile.
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load(['student', 'courseOfferings']);

        $data = (new UserResource($user))->resolve();

        $student = $user->student ?? Student::where('email', $user->email)->first();

        if ($user->isStudent() && $student) {
            $data['student_profile'] = [
                'id' => $student->id,
                'student_number' => $student->student_number,
                'program_id' => $student->program_id,
                'year_level' => $student->year_level,
            ];
        }

        return $this->success($data, 'Current user profile retrieved successfully.');
    }
}
