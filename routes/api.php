<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

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

// Authentication
Route::post("/register", [UserController::class, "store"]);
Route::post("/login", [UserController::class, "login"]);

// Protection Routes
Route::middleware("auth:sanctum")->group(function () {
    Route::get("/task", [TaskController::class, "index"]);
    Route::post("/task", [TaskController::class, "store"]);
    Route::put("/task/edit/{id}", [TaskController::class, "update"]);
    Route::delete("/task/delete/{id}", [TaskController::class, "destroy"]);
    Route::post("/task/is_completed/{id}", [TaskController::class, "is_completed"]);
});

Route::get('/send-test-email', function () {
    Mail::raw('This is a test email sent via Mailgun!', function ($message) {
        $message->to('test@example.com')
                ->subject('Test Email via Mailgun');
    });

    return 'Test email sent!';
});