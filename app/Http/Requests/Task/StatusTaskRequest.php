<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class StatusTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => 'required|string|in:pending,completed,in-progress',
        ];
    }

    /**
     * Get the custom validation messages for attributes.
     * 
     * This method provides custom error messages for the 'status' field.
     * 
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'status.required' => 'The status field is required.',
            'status.in' => 'The status must be one of: pending, completed, in-progress.'
        ];
    }
}
