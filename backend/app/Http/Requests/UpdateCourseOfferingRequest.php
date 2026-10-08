<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseOfferingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'course_id' => ['sometimes', 'required', 'integer', 'exists:courses,id'],
            'academic_term_id' => ['sometimes', 'required', 'integer', 'exists:academic_terms,id'],
            'instructor_id' => ['sometimes', 'required', 'integer', 'exists:users,id'],
            'section' => ['sometimes', 'required', 'string', 'max:50'],
            'schedule' => ['sometimes', 'required', 'string', 'max:100'],
            'room' => ['nullable', 'string', 'max:100'],
            'capacity' => ['sometimes', 'required', 'integer', 'min:1', 'max:300'],
            'status' => ['sometimes', 'required', 'string', 'in:OPEN,CLOSED,CANCELLED'],
        ];
    }
}
