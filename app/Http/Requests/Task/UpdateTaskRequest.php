<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Implement your authorization logic for updating tasks
        $task = $this->route('task'); // Get the task from the route parameter
        $user = $this->user(); // Get the currently authenticated user

        
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
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'priority' => 'sometimes|in:low,medium,high',
            'due_date' => 'sometimes|nullable|date_format:d-m-Y H:i',
            'status' => 'sometimes|nullable|string|in:pending,in-progress,completed',
            'assigned_to' => 'sometimes|nullable|exists:users,id',
        ];
    }
}
