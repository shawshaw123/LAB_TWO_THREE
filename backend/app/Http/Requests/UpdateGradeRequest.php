<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateGradeRequest extends FormRequest
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
            'midterm_grade' => ['sometimes', 'nullable', 'numeric', 'between:1.00,5.00'],
            'final_grade' => ['sometimes', 'nullable', 'numeric', 'between:1.00,5.00'],
            'remarks' => ['sometimes', 'nullable', 'string', 'in:PASSED,FAILED,INCOMPLETE,DROPPED'],
        ];
    }

    public function messages(): array
    {
        return [
            'midterm_grade.between' => 'Midterm grade must be between 1.00 (highest) and 5.00 (failing).',
            'final_grade.between' => 'Final grade must be between 1.00 (highest) and 5.00 (failing).',
        ];
    }
}
