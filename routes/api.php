<?php

//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Authentication routes
Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login'); // Route to login a user
    Route::post('register', 'register'); // Route to register a new user
    Route::post('logout', 'logout'); // Route to logout a user
    Route::post('refresh', 'refresh'); // Route to refresh the authentication token
});

// Task routes with authentication middleware
Route::apiResource('tasks', TaskController::class)->middleware('auth:api');
Route::controller(TaskController::class)->group(function () {
    Route::post('/tasks/{task}/assign', 'assignTask'); // Route to assign a task to a user
    Route::put('tasks/{task}/status','updateStatus'); // Route to update the status of a task
    Route::get('/task','trashed'); // Route to get trashed (deleted) tasks
    Route::post('/tasks/{task}/restore','restoreTask'); // Route to restore a previously deleted task
    Route::delete('/tasks/force-delete/{task}', 'forceDelete'); // Route to force delete a task (permanently)
})->middleware('auth:api');

// User routes with authentication middleware
Route::apiResource('users', UserController::class)->middleware('auth:api');
Route::controller(UserController::class)->group(function () {
    Route::get('/user','trashed'); // Route to get trashed (deleted) users
    Route::post('/users/{user}/restore','restoreUser'); // Route to restore a previously deleted user
    Route::delete('/users/force-delete/{user}', 'forceDelete'); // Route to force delete a user (permanently)
})->middleware('auth:api');
