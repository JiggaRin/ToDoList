<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TasksController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['prefix' => 'task'], function () {
    Route::get('/getAll', [TasksController::class, 'getAll']);
    Route::post('/create', [TasksController::class, 'create']);
    Route::put('/update/{id}', [TasksController::class, 'update']);
    Route::put('/updateStatus/{id}', [TasksController::class, 'updateStatus']);
    Route::post('/search', [TasksController::class, 'filter']);
    Route::delete('/delete/{id}', [TasksController::class, 'delete']);
});
