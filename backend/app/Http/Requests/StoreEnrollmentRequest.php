<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('status') && is_string($this->status)) {
            $this->merge([
                'status' => strtoupper($this->status),
            ]);
        }
    }

    /**
     * Duplicate enrollments are intentionally not validated here. The
     * controller detects them and responds with 409 Conflict, which is the
     * status documented in the OpenAPI specification.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'course_offering_id' => ['required', 'integer', 'exists:course_offerings,id'],
            'enrollment_date' => ['nullable', 'date'],
            'status' => ['nullable', 'string', 'in:ENROLLED,DROPPED,COMPLETED'],
        ];
    }
}
