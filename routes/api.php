<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\SummaryController;

// Public API routes (no token needed)
Route::prefix('auth')->group(function () {
    Route::post('/login',  [AuthController::class, 'login']);
    Route::post('/register', function() {
        return response()->json([
            'message' => 'Please register via the web application.'
        ], 200);
    });
});

// Protected API routes (token required)
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me',      [AuthController::class, 'me']);

    // Expenses CRUD
    Route::get('/expenses',          [ExpenseController::class, 'index']);
    Route::post('/expenses',         [ExpenseController::class, 'store']);
    Route::get('/expenses/{id}',     [ExpenseController::class, 'show']);
    Route::put('/expenses/{id}',     [ExpenseController::class, 'update']);
    Route::delete('/expenses/{id}',  [ExpenseController::class, 'destroy']);

    // Summary
    Route::get('/summary', [SummaryController::class, 'index']);

});
