<?php
namespace App\Services;

use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\Task;
use Illuminate\Http\Request;


class TaskService
{
	/**
     * Retrieve all tasks with optional filters.
     *
     * @param Request $request The HTTP request containing filter parameters.
     * @return \Illuminate\Database\Eloquent\Collection The collection of tasks.
     * @throws Exception If an error occurs while retrieving tasks.
     */
    public function getAllTasks(Request $request)
    {
		try{
			$query = Task::with('user');
			// Apply filters if present in the request
			if ($request->has('priority')) {
				$query->priority($request->input('priority'));
			}
			if ($request->has('status')) {
				$query->status($request->input('status'));
			}
			return $query->get();
		}catch(Exception $e ){
            Log::error($e);
			throw new Exception('Failed to retrieve tasks: ' . $e->getMessage());
        }	
    }

	/**
     * Store a new task in the database.
     *
     * @param array $data An array containing task data.
     * @return Task The created task model.
     * @throws Exception If an error occurs while creating the task.
     */
    public function storeTask(array $data)
    {
		try{
			$task = Task::create([
				'title'=> $data['title'],
				'description'=> $data['description'],
				'priority'=> $data['priority'],
				'due_date'=> $data['due_date'],
				'status'=> $data['status'],
				'assigned_to'=> $data['assigned_to'] 
			]);
			return $task;
		}catch(Exception $e ){
            Log::error($e);
			throw new Exception('Failed to created task: ' . $e->getMessage());
        }	
    }
	
	/**
     * Retrieve a specific task along with its associated user.
     *
     * @param Task $task The task model to retrieve.
     * @return Task The task model with the user relation loaded.
     * @throws Exception If an error occurs while retrieving the task.
     */
	public function showTask(Task $task)
    {
		try{
			return $task->load('user');
		}catch(Exception $e ){
            Log::error($e);
			throw new Exception('Failed to retrieve task: ' . $e->getMessage());
        }	
	}

	/**
     * Update an existing task with new data.
     *
     * @param Task $task The task model to update.
     * @param array $data An array containing the updated task data.
     * @return bool True if the update was successful, false otherwise.
     * @throws Exception If an error occurs while updating the task.
     */
    public function updateTask(Task $task, array $data)
    {
		try{
			return $task->update([
				'title' => $data['title'] ?? $task->title,
				'description'=> $data['description'] ?? $task->description,
				'priority'=> $data['priority'] ?? $task->priority,
				'due_date'=> $data['due_date'] ?? $task->due_date,
				'status'=> $data['status'] ?? $task->status,
				'assigned_to'=> $data['assigned_to'] ?? $task->assigned_to,
			]);
		}catch(Exception $e ){
            Log::error($e);
			throw new Exception('Failed to updated task: ' . $e->getMessage());
        }
    }

	/**
     * Delete a specific task from the database.
     *
     * @param Task $task The task model to delete.
     * @return void
     * @throws Exception If an error occurs while deleting the task.
     */
    public function deleteTask(Task $task)
    {
		try{
			$task->delete();
		}catch(Exception $e ){
            Log::error($e);
			throw new Exception('Failed to deleted task: ' . $e->getMessage());
        }
    }
}