<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\RegisterController;

// Public Pages
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/services', [PageController::class, 'services'])->name('services');
Route::get('/resources', [PageController::class, 'resources'])->name('resources');
Route::get('/events', [PageController::class, 'events'])->name('events');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');
Route::middleware('guest')->group(function () {
    Route::get('/register/{stage?}', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register/{stage}', [RegisterController::class, 'processStep'])->name('register.step');
});
// Protected Routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->group(function () {
    // Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    // Route::get('/admin/pending-approvals', [AdminController::class, 'pendingApprovals'])->name('admin.approvals');
});
