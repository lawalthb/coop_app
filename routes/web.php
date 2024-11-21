<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberDashboardController;
use App\Http\Controllers\MemberDocumentController;
use App\Http\Controllers\MemberNotificationController;
use App\Http\Controllers\MemberProfileController;
use App\Http\Controllers\MemberSavingsController;
use App\Http\Controllers\MemberTransactionController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;


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
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::get('/register/{stage}', [RegisterController::class, 'showRegistrationForm'])->name('register.show');
    Route::post('/register/{stage}', [RegisterController::class, 'processStep'])->name('register.step');
    Route::get('/states/{state}/lgas', function ($state) {
        return \App\Models\LGA::where('state_id', $state)
            ->where('status', 'active')
            ->get(['id', 'name']);
    });
    Route::get('/faculties/{faculty}/departments', function ($faculty) {
        return \App\Models\Department::where('faculty_id', $faculty)
            ->where('status', 'active')
            ->get(['id', 'name']);
    });

});

// Protected Routes

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/member/dashboard', [MemberDashboardController::class, 'index'])->name('member.dashboard');
    Route::get('/member/profile', [MemberProfileController::class, 'show'])->name('member.profile');
    Route::put('/member/profile', [MemberProfileController::class, 'update'])->name('member.profile.update');
    Route::get('/member/savings', [MemberSavingsController::class, 'index'])->name('member.savings');
    Route::get('/member/transactions', [MemberTransactionController::class, 'index'])->name('member.transactions');
    Route::get('/member/documents', [MemberDocumentController::class, 'index'])->name('member.documents');
    Route::get('/member/notifications', [MemberNotificationController::class, 'index'])->name('member.notifications');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->group(function () {
    // Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    // Route::get('/admin/pending-approvals', [AdminController::class, 'pendingApprovals'])->name('admin.approvals');
});




// Password Reset Routes
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->middleware('guest')
    ->name('password.email');

Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])
    ->name('password.reset');

Route::post('password/reset', [ResetPasswordController::class, 'reset'])
    ->name('password.update');

