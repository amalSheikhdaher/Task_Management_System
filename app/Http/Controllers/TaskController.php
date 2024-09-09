<?php
namespace App\Http\Controllers;

use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Requests\Task\AssignTaskRequest;
use App\Http\Requests\Task\StatusTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Services\TaskService;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // The task service handles task-related operations.
    protected TaskService $taskService;

    /**
     * Create a new controller instance.
     *
     * @param TaskService $taskService
     */
    public function __construct(TaskService $taskService)
    {
        // Middleware to check permissions for different actions
        $this->middleware('permission:create-task', ['only' => ['store']]);
        $this->middleware('permission:update-task', ['only' => ['update']]);
        $this->middleware('permission:delete-task', ['only' => ['destroy']]);
        $this->middleware('permission:assign-task', ['only' => ['assignTask']]);
        $this->middleware('permission:status-task', ['only' => ['updateStatus']]);
        $this->middleware('permission:trashed-task', ['only' => ['trashed']]);
        $this->middleware('permission:restore-task', ['only' => ['restoreTask']]);
        $this->middleware('permission:forceDelete-task', ['only' => ['forceDelete']]);
        
        $this->taskService = $taskService;
    }

    /**
     * Get all tasks with optional filters.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $tasks = $this->taskService->getAllTasks($request);
        return response()->json([
            'status' => 'success',
            'message' => 'All tasks fetched successfully',
            'data' => TaskResource::collection($tasks),
        ], 200);
    }

    /**
     * Create a new task.
     *
     * @param StoreTaskRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreTaskRequest $request)
    {
        $task = $this->taskService->storeTask($request->validated());
        return response()->json([
            'status' => 'success',
            'message' => 'Task created successfully',
            'data' => new TaskResource($task),
        ], 201);
        
    }
	
	/**
     * Get details of a single task.
     *
     * @param Task $task
     * @return \Illuminate\Http\JsonResponse
     */
	public function show(Task $task)
    {
        // Use the service to get the task details
        $task = $this->taskService->showTask($task);
        return response()->json([
            'status' => 'success',
            'message' => 'Task retrieved successfully',
            'data' => new TaskResource($task),
        ], 200);
    }
	

    /**
     * Update an existing task.
     *
     * @param UpdateTaskRequest $request
     * @param Task $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->taskService->updateTask($task, $request->validated());
        return response()->json([
            'status' => 'success',
            'message' => 'Task updated successfully',
            'data' => new TaskResource($task),
        ], 200);
    }

    /**
     * Delete a task.
     *
     * @param Task $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Task $task)
    {
        $this->taskService->deleteTask($task);
        return response()->json([
            'status' => 'success',
            'message' => 'Task deleted successfully',
            'data' => null,
        ], 200);
    }

    /**
    * Assign a task to a specific user.
    *
    * @param AssignTaskRequest $request
    * @param int $taskId
    * @return \Illuminate\Http\JsonResponse
    */
    public function assignTask(AssignTaskRequest $request, $taskId)
    {
        // Find the task
        $task = Task::findOrFail($taskId);

        // Assign the task to the user
        $task->assigned_to = $request->input('assigned_to');
        $task->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Task assigned successfully', 
            'task' => $task
        ], 200);
    }

    /**
     * Update the status of a task.
     *
     * @param StatusTaskRequest $request
     * @param int $taskId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(StatusTaskRequest $request, $taskId)
    {
        // Find the task by its ID
        $task = Task::findOrFail($taskId);

        // Get the authenticated user ID
        $userId = Auth::id();

        // Check if the user is the one assigned to this task
        if ($task->assigned_to != $userId) {
            return response()->json([
                'message' => 'Unauthorized to update this task'
            ], 403);
        }

        // Update the task status
        $task->status = $request->input('status');
        $task->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Task status updated successfully', 
            'task' => new TaskResource($task)
        ], 200);
    }

    /**
     * Get all trashed (soft-deleted) tasks.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function trashed ()
    {
        $task = Task::with('user')->onlyTrashed()->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Tasks successfully', 
            'task' => TaskResource::collection($task)
        ], 200);
    }

    /**
     * Restore a soft-deleted task.
     *
     * @param int $taskId
     * @return \Illuminate\Http\JsonResponse
     */
    public function restoreTask($taskId)
    {
        $task = Task::withTrashed()->findOrFail($taskId);
        $task->restore();

        return response()->json([
            'status' => 'success',
            'message' => 'Task restored successfully',
            'task' => new TaskResource($task)
        ], 200);
    }

    /**
     * Permanently delete a task.
     *
     * @param int $taskId
     * @return \Illuminate\Http\JsonResponse
     */
    public function forceDelete($taskId)
    {
        $task = Task::withTrashed()->find($taskId);

        if ($task) {
            $task->forceDelete();  // Permanently deletes the task
            return response()->json([
                'status' => 'success',
                'message' => 'Task permanently deleted',
                'task' => null
            ], 200);
        }
        return response()->json([
            'message' => 'Task not found'
        ], 404);
    }
}
