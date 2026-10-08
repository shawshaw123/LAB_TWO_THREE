<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCourseOfferingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $merge = [];
        if ($this->has('max_capacity') && ! $this->has('capacity')) {
            $merge['capacity'] = $this->max_capacity;
        }
        if ($this->has('status') && is_string($this->status)) {
            $merge['status'] = strtoupper($this->status);
        }
        if (! empty($merge)) {
            $this->merge($merge);
        }
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'academic_term_id' => ['required', 'integer', 'exists:academic_terms,id'],
            'instructor_id' => ['required', 'integer', 'exists:users,id'],
            'section' => ['required', 'string', 'max:50'],
            'schedule' => ['required', 'string', 'max:100'],
            'room' => ['nullable', 'string', 'max:100'],
            'capacity' => ['required', 'integer', 'min:1', 'max:300'],
            'status' => ['nullable', 'string', 'in:OPEN,CLOSED,CANCELLED'],
        ];
    }
}
