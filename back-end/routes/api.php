<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\TodoController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/todo', [TodoController::class, 'index']);
Route::post('/todo', [TodoController::class, 'store']);
Route::get('/todo/{id}', [TodoController::class, 'show']);
Route::post('/todo/{id}', [TodoController::class, 'update']);
Route::delete('/todo/{id}', [TodoController::class, 'destroy']);
Route::get('/todo/completed/{id}', [TodoController::class, 'markComplete']);
Route::get('/todo/important/{id}', [TodoController::class, 'markImportant']);
Route::get('/todo/unimportant/{id}', [TodoController::class, 'markUnimportant']);