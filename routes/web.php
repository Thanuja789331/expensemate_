<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CategoryManagementController;
use App\Http\Controllers\Admin\UserManagementController;

// Public home page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authenticated user routes
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Expenses
    Route::resource('expenses', ExpenseController::class);

});

// Admin only routes
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Admin dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('dashboard');

    // Category management
    Route::get('/categories', [CategoryManagementController::class, 'index'])
        ->name('categories.index');
    Route::post('/categories', [CategoryManagementController::class, 'store'])
        ->name('categories.store');
    Route::put('/categories/{category}', [CategoryManagementController::class, 'update'])
        ->name('categories.update');
    Route::delete('/categories/{category}', [CategoryManagementController::class, 'destroy'])
        ->name('categories.destroy');

    // User management
    Route::get('/users', [UserManagementController::class, 'index'])
        ->name('users.index');
    Route::patch('/users/{user}/toggle-active', [UserManagementController::class, 'toggleActive'])
        ->name('users.toggle');
    Route::patch('/users/{user}/change-role', [UserManagementController::class, 'changeRole'])
        ->name('users.role');

});
