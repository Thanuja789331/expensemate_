<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CategoryManagementController;
use App\Http\Controllers\Admin\UserManagementController;

// ── PUBLIC ROUTES ─────────────────────────────────────────
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
})->name('home');

// ── GOOGLE AUTH ROUTES ────────────────────────────────────
Route::get('/auth/google',
    [GoogleAuthController::class, 'redirect'])
    ->name('auth.google');

Route::get('/auth/google/callback',
    [GoogleAuthController::class, 'callback'])
    ->name('auth.google.callback');

// ── AUTHENTICATED USER ROUTES ─────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Summary
    Route::get('/summary', [DashboardController::class, 'summary'])
        ->name('summary');

    // Expenses CRUD
    Route::resource('expenses', ExpenseController::class);

    // 2FA Routes
    Route::get('/2fa/enable', [TwoFactorController::class, 'enable'])
        ->name('2fa.enable');
    Route::post('/2fa/confirm', [TwoFactorController::class, 'confirm'])
        ->name('2fa.confirm');
    Route::post('/2fa/disable', [TwoFactorController::class, 'disable'])
        ->name('2fa.disable');

});

// ── ADMIN ONLY ROUTES ─────────────────────────────────────
Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    // Admin Dashboard
    Route::get('/dashboard',
        [AdminDashboardController::class, 'index'])
        ->name('dashboard');

    // Category Management
    Route::get('/categories',
        [CategoryManagementController::class, 'index'])
        ->name('categories.index');
    Route::post('/categories',
        [CategoryManagementController::class, 'store'])
        ->name('categories.store');
    Route::put('/categories/{category}',
        [CategoryManagementController::class, 'update'])
        ->name('categories.update');
    Route::delete('/categories/{category}',
        [CategoryManagementController::class, 'destroy'])
        ->name('categories.destroy');

    // User Management
    Route::get('/users',
        [UserManagementController::class, 'index'])
        ->name('users.index');
    Route::patch('/users/{user}/toggle-active',
        [UserManagementController::class, 'toggleActive'])
        ->name('users.toggle');
    Route::patch('/users/{user}/change-role',
        [UserManagementController::class, 'changeRole'])
        ->name('users.role');

});
