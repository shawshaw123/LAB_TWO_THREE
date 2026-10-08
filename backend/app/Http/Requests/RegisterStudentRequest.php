<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterStudentRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email', 'unique:students,email'],
            'password' => ['required', 'string', 'min:8'],
            'student_number' => ['nullable', 'string', 'max:50', 'unique:students,student_number'],
            'program_id' => ['required', 'integer', 'exists:programs,id'],
            'year_level' => ['nullable', 'integer', 'between:1,5'],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'contact_number' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.unique' => 'An account with this email address already exists.',
            'student_number.unique' => 'This student number is already registered.',
            'program_id.exists' => 'The selected degree program is invalid.',
        ];
    }
}
