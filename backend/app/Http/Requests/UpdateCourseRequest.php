<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCourseRequest extends FormRequest
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
        $courseId = $this->route('id') ?? $this->route('course');

        return [
            'course_code' => ['sometimes', 'required', 'string', 'max:20', Rule::unique('courses', 'course_code')->ignore($courseId)],
            'course_title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'units' => ['sometimes', 'required', 'numeric', 'between:0.5,12.0'],
            'status' => ['sometimes', 'required', 'string', 'in:ACTIVE,INACTIVE'],
        ];
    }
}
