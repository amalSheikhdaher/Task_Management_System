<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'task_id' => $this->task_id,
            'title' => $this->title,
            'description' => $this->description,
            'priority' => $this->priority,
            'due_date' => $this->due_date,  // Accessor automatically formats the date
            'status' => $this->status,
            'assigned_to' => $this->assigned_to,
            'created_on' => $this->created_on->format('d-m-Y H:i'),
            'updated_on' => $this->updated_on->format('d-m-Y H:i'),
        ];
    }
}
