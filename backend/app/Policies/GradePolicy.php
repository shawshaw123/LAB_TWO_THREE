<?php

namespace App\Policies;

use App\Models\Grade;
use App\Models\User;

class GradePolicy
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
     * Determine whether the user can view any grades.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isRegistrar();
    }

    /**
     * Determine whether the user can view the grade.
     */
    public function view(User $user, Grade $grade): bool
    {
        if ($user->isStudent()) {
            return $grade->enrollment->student->user_id === $user->id ||
                   $grade->enrollment->student->email === $user->email;
        }

        if ($user->isInstructor()) {
            return $grade->enrollment->courseOffering->instructor_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create grades.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isRegistrar() || $user->isInstructor();
    }

    /**
     * Determine whether the user can update the grade.
     */
    public function update(User $user, Grade $grade): bool
    {
        if ($user->isInstructor()) {
            return $grade->enrollment->courseOffering->instructor_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the grade.
     */
    public function delete(User $user, Grade $grade): bool
    {
        return $user->isAdmin() || $user->isRegistrar();
    }
}
