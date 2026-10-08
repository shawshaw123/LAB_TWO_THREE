<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAcademicTermRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $merge = [];
        if ($this->has('semester') && ! $this->has('term')) {
            $sem = strtolower($this->semester);
            if (str_contains($sem, '1')) {
                $merge['term'] = '1st Semester';
            } elseif (str_contains($sem, '2')) {
                $merge['term'] = '2nd Semester';
            } elseif (str_contains($sem, 'mid')) {
                $merge['term'] = 'Midyear';
            } else {
                $merge['term'] = 'Summer';
            }
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
            'academic_year' => ['required', 'string', 'regex:/^\d{4}-\d{4}$/'],
            'term' => ['required', 'string', 'in:1st Semester,2nd Semester,Summer,Midyear'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'status' => ['nullable', 'string', 'in:ACTIVE,INACTIVE'],
        ];
    }
}
