<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreGradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('remarks') && is_string($this->remarks)) {
            $rem = strtolower(trim($this->remarks));
            if (str_contains($rem, 'pass')) {
                $this->merge(['remarks' => 'PASSED']);
            } elseif (str_contains($rem, 'fail')) {
                $this->merge(['remarks' => 'FAILED']);
            } elseif (str_contains($rem, 'inc')) {
                $this->merge(['remarks' => 'INCOMPLETE']);
            } elseif (str_contains($rem, 'drop')) {
                $this->merge(['remarks' => 'DROPPED']);
            } else {
                $this->merge(['remarks' => strtoupper($this->remarks)]);
            }
        }
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'enrollment_id' => ['required', 'integer', 'exists:enrollments,id'],
            'midterm_grade' => ['nullable', 'numeric', 'between:1.00,5.00'],
            'final_grade' => ['nullable', 'numeric', 'between:1.00,5.00'],
            'remarks' => ['nullable', 'string', 'in:PASSED,FAILED,INCOMPLETE,DROPPED'],
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
