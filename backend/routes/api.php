<?php

use App\Http\Controllers\Api\V1\AcademicTermController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CourseController;
use App\Http\Controllers\Api\V1\CourseOfferingController;
use App\Http\Controllers\Api\V1\EnrollmentController;
use App\Http\Controllers\Api\V1\GradeController;
use App\Http\Controllers\Api\V1\ProgramController;
use App\Http\Controllers\Api\V1\StudentController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    // Authentication
    Route::post('/auth/login', [AuthController::class, 'login'])->name('api.v1.auth.login');
    Route::post('/auth/register', [AuthController::class, 'register'])->name('api.v1.auth.register');

    // Programs (Public index for registration catalog & reference)
    Route::get('/programs', [ProgramController::class, 'index'])->name('api.v1.programs.index');

    Route::middleware('auth:sanctum')->group(function (): void {
        // Authenticated Session
        Route::post('/auth/logout', [AuthController::class, 'logout'])->name('api.v1.auth.logout');
        Route::get('/auth/me', [AuthController::class, 'me'])->name('api.v1.auth.me');

        // Programs Detail & Admin Operations
        Route::get('/programs/{program}', [ProgramController::class, 'show'])->name('api.v1.programs.show');
        Route::middleware('role:admin,registrar')->group(function (): void {
            Route::post('/programs', [ProgramController::class, 'store'])->name('api.v1.programs.store');
            Route::match(['put', 'patch'], '/programs/{program}', [ProgramController::class, 'update'])->name('api.v1.programs.update');
            Route::delete('/programs/{program}', [ProgramController::class, 'destroy'])->name('api.v1.programs.destroy');
        });

        // Courses
        Route::get('/courses', [CourseController::class, 'index'])->name('api.v1.courses.index');
        Route::get('/courses/{course}', [CourseController::class, 'show'])->name('api.v1.courses.show');
        Route::middleware('role:admin,registrar')->group(function (): void {
            Route::post('/courses', [CourseController::class, 'store'])->name('api.v1.courses.store');
            Route::match(['put', 'patch'], '/courses/{course}', [CourseController::class, 'update'])->name('api.v1.courses.update');
            Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('api.v1.courses.destroy');
        });

        // Academic Terms
        Route::get('/academic-terms', [AcademicTermController::class, 'index'])->name('api.v1.academic-terms.index');
        Route::get('/academic-terms/{academicTerm}', [AcademicTermController::class, 'show'])->name('api.v1.academic-terms.show');
        Route::middleware('role:admin,registrar')->group(function (): void {
            Route::post('/academic-terms', [AcademicTermController::class, 'store'])->name('api.v1.academic-terms.store');
            Route::match(['put', 'patch'], '/academic-terms/{academicTerm}', [AcademicTermController::class, 'update'])->name('api.v1.academic-terms.update');
            Route::delete('/academic-terms/{academicTerm}', [AcademicTermController::class, 'destroy'])->name('api.v1.academic-terms.destroy');
        });

        // Students & Domain Operations
        Route::get('/students', [StudentController::class, 'index'])->name('api.v1.students.index');
        Route::get('/students/{student}', [StudentController::class, 'show'])->name('api.v1.students.show');
        Route::get('/students/{student}/enrollments', [StudentController::class, 'enrollments'])->name('api.v1.students.enrollments');
        Route::get('/students/{student}/grades', [StudentController::class, 'grades'])->name('api.v1.students.grades');
        Route::get('/students/{student}/academic-record', [StudentController::class, 'academicRecord'])->name('api.v1.students.academic-record');

        Route::middleware('role:admin,registrar')->group(function (): void {
            Route::post('/students', [StudentController::class, 'store'])->name('api.v1.students.store');
            Route::match(['put', 'patch'], '/students/{student}', [StudentController::class, 'update'])->name('api.v1.students.update');
            Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('api.v1.students.destroy');
        });

        // Course Offerings
        Route::get('/course-offerings', [CourseOfferingController::class, 'index'])->name('api.v1.course-offerings.index');
        Route::get('/course-offerings/{courseOffering}', [CourseOfferingController::class, 'show'])->name('api.v1.course-offerings.show');
        Route::get('/course-offerings/{courseOffering}/students', [CourseOfferingController::class, 'students'])->name('api.v1.course-offerings.students');
        Route::middleware('role:admin,registrar')->group(function (): void {
            Route::post('/course-offerings', [CourseOfferingController::class, 'store'])->name('api.v1.course-offerings.store');
            Route::match(['put', 'patch'], '/course-offerings/{courseOffering}', [CourseOfferingController::class, 'update'])->name('api.v1.course-offerings.update');
            Route::delete('/course-offerings/{courseOffering}', [CourseOfferingController::class, 'destroy'])->name('api.v1.course-offerings.destroy');
        });

        // Enrollments
        Route::get('/enrollments', [EnrollmentController::class, 'index'])->name('api.v1.enrollments.index');
        Route::get('/enrollments/{enrollment}', [EnrollmentController::class, 'show'])->name('api.v1.enrollments.show');
        Route::middleware('role:admin,registrar')->group(function (): void {
            Route::post('/enrollments', [EnrollmentController::class, 'store'])->name('api.v1.enrollments.store');
            Route::patch('/enrollments/{enrollment}', [EnrollmentController::class, 'update'])->name('api.v1.enrollments.update');
            Route::delete('/enrollments/{enrollment}', [EnrollmentController::class, 'destroy'])->name('api.v1.enrollments.destroy');
        });

        // Grades
        Route::get('/grades', [GradeController::class, 'index'])->name('api.v1.grades.index');
        Route::get('/grades/{grade}', [GradeController::class, 'show'])->name('api.v1.grades.show');
        Route::middleware('role:admin,registrar,instructor')->group(function (): void {
            Route::post('/grades', [GradeController::class, 'store'])->name('api.v1.grades.store');
            Route::match(['put', 'patch'], '/grades/{grade}', [GradeController::class, 'update'])->name('api.v1.grades.update');
        });
    });
});
