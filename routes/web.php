<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\LoanController;
use App\Http\Controllers\Admin\SavingController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\EntranceFeeController;
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
Route::any('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');
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

Route::middleware(['auth', 'admin_sign'])->group(function () {
    Route::get('/member/dashboard', [MemberDashboardController::class, 'index'])->name('member.dashboard');
    Route::get('/member/profile', [MemberProfileController::class, 'show'])->name('member.profile');
    Route::put('/member/profile', [MemberProfileController::class, 'update'])->name('member.profile.update');
    Route::get('/member/savings', [MemberSavingsController::class, 'index'])->name('member.savings');
    Route::get('/member/transactions', [MemberTransactionController::class, 'index'])->name('member.transactions');
    Route::get('/member/documents', [MemberDocumentController::class, 'index'])->name('member.documents');
    Route::get('/member/notifications', [MemberNotificationController::class, 'index'])->name('member.notifications');
});

// Admin Routes
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');


    Route::get('/members', [MemberController::class, 'index'])->name('members');
    Route::get('/members/{member}', [MemberController::class, 'show'])->name('members.show');
    Route::patch('/members/{member}/approve', [MemberController::class, 'approve'])->name('members.approve');
    Route::patch('/members/{member}/reject', [MemberController::class, 'reject'])->name('members.reject');
    Route::patch('/members/{member}/suspend', [MemberController::class, 'suspend'])->name('members.suspend');
    Route::patch('/members/{member}/activate', [MemberController::class, 'activate'])->name('members.activate');
    Route::delete('/members/{member}', [MemberController::class, 'destroy'])->name('members.destroy');
    Route::get('/members/{member}/pdf', [MemberController::class, 'downloadPDF'])->name('members.pdf');
    Route::get('/members/{member}/edit', [MemberController::class, 'edit'])->name('members.edit');
    Route::put('/members/{member}', [MemberController::class, 'update'])->name('members.update');

    Route::get('/member/entrance-fees', [EntranceFeeController::class, 'index'])->name('entrance-fees');



    Route::get('/loans', [LoanController::class, 'index'])->name('loans');
    Route::get('/savings', [SavingController::class, 'index'])->name('savings');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings');
    Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile');
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


use Illuminate\Support\Facades\Mail;

Route::get('/test-email', function () {
    try {
        Mail::raw('This is a test email.', function ($message) {
            $message->to('recipient@example.com') // Replace with a valid recipient
                ->subject('Test Email');
        });

        return 'Email sent successfully!';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

