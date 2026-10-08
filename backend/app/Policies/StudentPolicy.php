<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;

class StudentPolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin() || $user->isRegistrar()) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isRegistrar() || $user->isInstructor();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Student $student): bool
    {
        if ($user->isStudent()) {
            return $student->user_id === $user->id || $student->email === $user->email;
        }

        if ($user->isInstructor()) {
            // Instructor can ONLY view students enrolled in their assigned course offerings
            return $student->enrollments()
                ->whereHas('courseOffering', fn ($q) => $q->where('instructor_id', $user->id))
                ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isRegistrar();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Student $student): bool
    {
        return $user->isAdmin() || $user->isRegistrar();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Student $student): bool
    {
        return $user->isAdmin() || $user->isRegistrar();
    }

    /**
     * Determine whether the user can view the student's academic record.
     */
    public function viewAcademicRecord(User $user, Student $student): bool
    {
        if ($user->isStudent()) {
            return $student->user_id === $user->id || $student->email === $user->email;
        }

        if ($user->isInstructor()) {
            // Instructor can ONLY view academic record of students enrolled in their course offerings
            return $student->enrollments()
                ->whereHas('courseOffering', fn ($q) => $q->where('instructor_id', $user->id))
                ->exists();
        }

        return false;
    }
}
