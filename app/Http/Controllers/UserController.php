<?php
namespace App\Http\Controllers;

use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    // The service class that handles user-related business logic
    protected UserService $userService;

    /**
     * UserController constructor.
     * 
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        // Middleware to check permissions for various actions
        $this->middleware('permission:index-user', ['only' => ['index']]);
        $this->middleware('permission:show-user', ['only' => ['show']]);
        $this->middleware('permission:create-user', ['only' => ['store']]);
        $this->middleware('permission:update-user', ['only' => ['update']]);
        $this->middleware('permission:delete-user', ['only' => ['destroy']]);
        $this->middleware('permission:trashed-user', ['only' => ['trashed']]);
        $this->middleware('permission:restore-user', ['only' => ['restoreUser']]);
        $this->middleware('permission:forceDelete-user', ['only' => ['forceDelete']]);

        // Inject the UserService instance
        $this->userService = $userService;
    }

    /**
     * Retrieve all users.
     * 
     * @return JsonResponse
     */
    public function index()
    {
        $users = $this->userService->getAllUsers();
        return response()->json([
            'status' => 'success',
            'message' => 'All users fetched successfully',
            'data' => UserResource::collection($users),
        ], 200);
    }

    /**
     * Create a new user.
     * 
     * @param StoreUserRequest $request
     * @return JsonResponse
     */
    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->createUser($request->validated());
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'data' => new UserResource($user),
        ], 201);
    }
	
    /**
     * Retrieve a specific user by ID.
     * 
     * @param User $user
     * @return JsonResponse
     */
	public function show(User $user)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'User retrieved successfully',
            'data' => new UserResource($user),
        ], 200);
    }

    /**
     * Update an existing user.
     * 
     * @param UpdateUserRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $this->userService->updateUser($user, $request->validated());
        return response()->json([
            'status' => 'success',
            'message' => 'User updated successfully',
            'data' => new UserResource($data),
        ], 200);
    }

    /**
     * Soft delete a user.
     * 
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user)
    {
        $this->userService->deleteUser($user);
        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully',
            'data' => null,
        ], 200);
    }

    /**
     * Retrieve all soft-deleted users.
     * 
     * @return JsonResponse
     */
    public function trashed ()
    {
        $user = User::onlyTrashed()->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Users display successfully', 
            'user' => UserResource::collection($user)
        ], 200);
    }

    /**
     * Restore a soft-deleted user by ID.
     * 
     * @param int $userId
     * @return JsonResponse
     */
    public function restoreUser($userId)
    {
        $user = User::withTrashed()->findOrFail($userId);
        $user->restore();

        return response()->json([
            'status' => 'success',
            'message' => 'User restored successfully',
            'user' => new UserResource($user)
        ], 200);
    }

    /**
     * Permanently delete a user by ID.
     * 
     * @param int $userId
     * @return JsonResponse
     */
    public function forceDelete($userId)
    {
        $user = User::withTrashed()->find($userId);

        if ($user) {
            $user->forceDelete();  // Permanently deletes the user
            return response()->json([
                'status' => 'success',
                'message' => 'User permanently deleted',
                'user' => null
            ], 200);
        }
        return response()->json([
            'message' => 'User not found'
        ], 404);
    }
}
