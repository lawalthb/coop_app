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
use App\Http\Controllers\Admin\LoanRepaymentController;
use App\Http\Controllers\Admin\LoanTypeController;
use App\Http\Controllers\Admin\SavingTypeController;
use App\Http\Controllers\Admin\ShareController;
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
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;

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
    //Admin dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    //member management
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



    //entrance fee management
    Route::get('/member/entrance-fees', [EntranceFeeController::class, 'index'])->name('entrance-fees');
    Route::get('/member/entrance-fees/create', [EntranceFeeController::class, 'create'])->name('entrance-fees.create');
    Route::post('/member/entrance-fees', [EntranceFeeController::class, 'store'])->name('entrance-fees.store');
    Route::get('/member/entrance-fees/{entranceFee}/edit', [EntranceFeeController::class, 'edit'])->name('entrance-fees.edit');
    Route::put('/member/entrance-fees/{entranceFee}', [EntranceFeeController::class, 'update'])->name('entrance-fees.update');
    Route::delete('/member/entrance-fees/{entranceFee}', [EntranceFeeController::class, 'destroy'])->name('entrance-fees.destroy');

    // Admin Saving Types Routes
    Route::get('/saving-types', [SavingTypeController::class, 'index'])->name('saving-types.index');
    Route::get('/saving-types/create', [SavingTypeController::class, 'create'])->name('saving-types.create');
    Route::post('/saving-types', [SavingTypeController::class, 'store'])->name('saving-types.store');
    Route::get('/saving-types/{savingType}/edit', [SavingTypeController::class, 'edit'])->name('saving-types.edit');
    Route::put('/saving-types/{savingType}', [SavingTypeController::class, 'update'])->name('saving-types.update');

    //savings
    Route::get('/savings', [SavingController::class, 'index'])->name('savings');
    Route::get('/savings/create', [SavingController::class, 'create'])->name('savings.create');
    Route::post('/savings', [SavingController::class, 'store'])->name('savings.store');
    Route::get('/savings/bulk', [SavingController::class, 'bulkCreate'])->name('savings.bulk');
    Route::post('/savings/bulk', [SavingController::class, 'bulkStore'])->name('savings.bulk.store');
    Route::get('/savings/{saving}', [SavingController::class, 'show'])->name('savings.show');
    Route::get('/savings/{saving}/edit', [SavingController::class, 'edit'])->name('savings.edit');
    Route::put('/savings/{saving}', [SavingController::class, 'update'])->name('savings.update');
    Route::delete('/savings/{saving}', [SavingController::class, 'destroy'])->name('savings.destroy');


    //shares
    Route::get('/shares', [ShareController::class, 'index'])->name('shares.index');
    Route::get('/shares/create', [ShareController::class, 'create'])->name('shares.create');
    Route::post('/shares', [ShareController::class, 'store'])->name('shares.store');
    Route::get('/shares/{share}', [ShareController::class, 'show'])->name('shares.show');
    Route::get('/shares/transfer', [ShareController::class, 'transfer'])->name('shares.transfer');
    Route::post('/shares/transfer', [ShareController::class, 'processTransfer'])->name('shares.transfer.process');


    // Admin Loan Management Routes

    Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');
    Route::get('/loans/create', [LoanController::class, 'create'])->name('loans.create');
    Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');
    Route::get('/loans/{loan}', [LoanController::class, 'show'])->name('loans.show');
    Route::post('/loans/{loan}/approve', [LoanController::class, 'approve'])->name('loans.approve');
    Route::post('/loans/{loan}/reject', [LoanController::class, 'reject'])->name('loans.reject');
    // Admin Loan Types Routes
    Route::get('/loan-types', [LoanTypeController::class, 'index'])->name('loan-types.index');
    Route::get('/loan-types/create', [LoanTypeController::class, 'create'])->name('loan-types.create');
    Route::post('/loan-types', [LoanTypeController::class, 'store'])->name('loan-types.store');
    Route::get('/loan-types/{loanType}/edit', [LoanTypeController::class, 'edit'])->name('loan-types.edit');
    Route::put('/loan-types/{loanType}', [LoanTypeController::class, 'update'])->name('loan-types.update');

    //loan repayment
    Route::get('/loans/{loan}/repayments/create', [LoanRepaymentController::class, 'create'])->name('loans.repayments.create');
    Route::post('/loans/{loan}/repayments', [LoanRepaymentController::class, 'store'])->name('loans.repayments.store');


    //transactions
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::get('/transactions/export', [TransactionController::class, 'export'])->name('transactions.export');






    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings');

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


Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
});

Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put(
    '/profile',
    [ProfileController::class, 'update']
)->name('profile.update');
Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');



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
