<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\LoanController;
use App\Http\Controllers\Admin\SavingController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminProfileUpdateController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\CommodityController;
use App\Http\Controllers\Admin\CommoditySubscriptionController;
use App\Http\Controllers\Admin\EntranceFeeController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\LoanRepaymentController;
use App\Http\Controllers\Admin\LoanTypeController;
use App\Http\Controllers\Admin\ResourceController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SavingTypeController;
use App\Http\Controllers\Admin\ShareController;
use App\Http\Controllers\Admin\ShareTypeController;
use App\Http\Controllers\Admin\WithdrawalController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;

use App\Http\Controllers\Auth\RegisterController;

use App\Http\Controllers\MemberDashboardController;
use App\Http\Controllers\MemberDocumentController;
use App\Http\Controllers\MemberProfileController;
use App\Http\Controllers\Member\MemberTransactionController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Member\LoanCalculatorController;
use App\Http\Controllers\Member\MemberCommodityController;
use App\Http\Controllers\Member\MemberCommoditySubscriptionController;
use App\Http\Controllers\Member\MemberLoanController;
use App\Http\Controllers\Member\MemberSavingsController;
use App\Http\Controllers\Member\MemberShareController;
use App\Http\Controllers\Member\MemberWithdrawalController;
use App\Http\Controllers\Member\MemberResourceController;
use App\Http\Controllers\Member\SavingsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfileUpdateController;
use App\Http\Controllers\Member\MemberCommodityPaymentController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Admin\AdminCommodityPaymentController;
use App\Http\Controllers\Admin\AdminFinancialSummaryController;
use App\Http\Controllers\Admin\TransactionSummaryController;
use App\Http\Controllers\Member\MemberFinancialSummaryController;

// Public Pages
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/services', [PageController::class, 'services'])->name('services');
Route::get('/resources', [PageController::class, 'resources'])->name('resources');
Route::get('/events', [PageController::class, 'events'])->name('events');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/history', [PageController::class, 'history'])->name('history');

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
Route::get('/states/{state}/lgas', [RegisterController::class, 'getLgas'])->name('getLgas');

Route::get('/faculties/{faculty}/departments', [RegisterController::class, 'getDepartments'])->name('getDepartments');

// Protected Routes
//member routes
Route::middleware(['auth', 'admin_sign'])->group(function () {
    Route::get('/member/dashboard', [MemberDashboardController::class, 'index'])->name('member.dashboard');
    // Route::get('/member/profile', [MemberProfileController::class, 'show'])->name('member.profile');
    // Route::put('/member/profile', [MemberProfileController::class, 'update'])->name('member.profile.update');
    Route::get('/member/savings', [MemberSavingsController::class, 'index'])->name('member.savings');

    //shares
    Route::get('/member/shares', [MemberShareController::class, 'index'])->name('member.shares.index');

    Route::get('/member/shares/create', [MemberShareController::class, 'create'])->name('member.shares.create');

    Route::post('/member/shares', [MemberShareController::class, 'store'])->name('member.shares.store');

    Route::get('/member/loans', [MemberLoanController::class, 'index'])->name('member.loans.index');
    Route::get('/member/loans/create', [MemberLoanController::class, 'create'])->name('member.loans.create');
    Route::post('/member/loans', [MemberLoanController::class, 'store'])->name('member.loans.store');
    Route::get('/member/loans/{loan}', [MemberLoanController::class, 'show'])->name('member.loans.show');

    // Route::get('/member/withdrawals', [MemberWithdrawalController::class, 'index'])->name('member.withdrawals.index');
    // Route::get('/member/withdrawals/create', [MemberWithdrawalController::class, 'create'])->name('member.withdrawals.create');
    // Route::post('/member/withdrawals', [MemberWithdrawalController::class, 'store'])->name('member.withdrawals.store');




    //passbook routes
    Route::get('/member/transactions', [MemberTransactionController::class, 'index'])->name('member.transactions.index');
    Route::get('/member/transactions/{transaction}', [MemberTransactionController::class, 'show'])->name('member.transactions.show');



    Route::get('/member/loan-calculator', [LoanCalculatorController::class, 'index'])->name('member.loan-calculator');
    Route::post('/member/loan-calculator/check', [LoanCalculatorController::class, 'checkEligibility'])->name('member.loan-calculator.check');


    Route::get('/member/resources', [MemberResourceController::class, 'index'])->name('member.resources.index');
    Route::get('/member/resources/{resource}/download', [MemberResourceController::class, 'download'])->name('member.resources.download');

    Route::post('member/guarantor/{loan}/respond', [MemberLoanController::class, 'respondToGuarantorRequest'])
        ->name('member.guarantor.respond');
    Route::get('/member/guarantor-requests', [MemberLoanController::class, 'guarantorRequests'])
        ->name('member.guarantor-requests');
    Route::get('/member/guarantor/{loan}/show', [MemberLoanController::class, 'showGuarantorRequest'])
        ->name('member.guarantor.show');

    Route::get('member/commodities', [MemberCommodityController::class, 'index'])->name('member.commodities.index');
    Route::get('member/commodities/{commodity}', [MemberCommodityController::class, 'show'])->name('member.commodities.show');
    Route::post('member/commodities/{commodity}/subscribe', [MemberCommodityController::class, 'subscribe'])->name('member.commodities.subscribe');

    Route::get('member/commodity-subscriptions', [MemberCommoditySubscriptionController::class, 'index'])->name('member.commodity-subscriptions.index');
    Route::get('member/commodity-subscriptions/{subscription}', [MemberCommoditySubscriptionController::class, 'show'])->name('member.commodity-subscriptions.show');


     Route::get('/member/commodity-subscriptions/{subscription}/payments', [MemberCommodityPaymentController::class, 'index'])
        ->name('member.commodity-payments.index');
    Route::get('/member/commodity-subscriptions/{subscription}/payments/create', [MemberCommodityPaymentController::class, 'create'])
        ->name('member.commodity-payments.create');
    Route::post('/member/commodity-subscriptions/{subscription}/payments', [MemberCommodityPaymentController::class, 'store'])
        ->name('member.commodity-payments.store');



Route::get('/member/savings/monthly-summary', [MemberSavingsController::class, 'monthlySummary'])->name('member.savings.monthly-summary');
Route::get('/member/savings/history', [MemberSavingsController::class, 'savingsHistory'])->name('member.savings.history');

Route::get('/member/financial-summary', [MemberFinancialSummaryController::class, 'index'])->name('member.financial-summary');
   Route::get('/member/financial-summary', [MemberFinancialSummaryController::class, 'index'])->name('member.financial-summary');
    Route::get('/member/financial-summary/export', [MemberFinancialSummaryController::class, 'export'])->name('member.financial-summary.export');
    Route::get('/member/financial-summary/pdf', [MemberFinancialSummaryController::class, 'downloadPdf'])->name('member.financial-summary.pdf');


        Route::get('/member/savings/settings', [MemberSavingsController::class, 'showSavingsSettings'])
        ->name('member.savings.settings.index');
    Route::get('/member/savings/settings/create', [MemberSavingsController::class, 'createSavingsSetting'])
        ->name('member.savings.settings.create');
    Route::post('/member/savings/settings', [MemberSavingsController::class, 'storeSavingsSetting'])
        ->name('member.savings.settings.store');
    Route::get('/member/savings/settings/{setting}/edit', [MemberSavingsController::class, 'editSavingsSetting'])
        ->name('member.savings.settings.edit');
    Route::put('/member/savings/settings/{setting}', [MemberSavingsController::class, 'updateSavingsSetting'])
        ->name('member.savings.settings.update');
    Route::delete('/member/savings/settings/{setting}', [MemberSavingsController::class, 'destroySavingsSetting'])
        ->name('member.savings.settings.destroy');


        Route::get('/withdrawals', [MemberWithdrawalController::class, 'index'])->name('member.withdrawals.index');
    Route::get('/withdrawals/create', [MemberWithdrawalController::class, 'create'])->name('member.withdrawals.create');
    Route::post('/withdrawals', [MemberWithdrawalController::class, 'store'])->name('member.withdrawals.store');
    Route::get('/withdrawals/{withdrawal}', [MemberWithdrawalController::class, 'show'])->name('member.withdrawals.show');

}); //end of member routes

// Admin Routes
Route::middleware(['auth', 'is_admin', 'permission:view_roles'])->prefix('admin')->name('admin.')->group(function () {
    route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/loans/repayment', [LoanRepaymentController::class, 'index'])->name('loans.repayments.index');
    //member management

    Route::get('/members/create', [MemberController::class, 'create'])->name('members.create');
    Route::post('/members', [MemberController::class, 'store'])->name('members.store');


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
    Route::get('/members/{member}/authority-deduct', [MemberController::class, 'authorityDeduct'])->name('members.authority-deduct');


    //entrance fee management
    Route::get('/member/entrance-fees', [EntranceFeeController::class, 'index'])->name('entrance-fees');
    Route::get('/member/entrance-fees/create', [EntranceFeeController::class, 'create'])->name('entrance-fees.create');
    Route::post('/member/entrance-fees', [EntranceFeeController::class, 'store'])->name('entrance-fees.store');
    Route::get('/member/entrance-fees/{entranceFee}/edit', [EntranceFeeController::class, 'edit'])->name('entrance-fees.edit');
    Route::put('/member/entrance-fees/{entranceFee}', [EntranceFeeController::class, 'update'])->name('entrance-fees.update');
    Route::delete('/member/entrance-fees/{entranceFee}', [EntranceFeeController::class, 'destroy'])->name('entrance-fees.destroy');

    Route::post('/entrance-fees/import', [EntranceFeeController::class, 'import'])->name('entrance-fees.import');
Route::get('/entrance-fees/export', [EntranceFeeController::class, 'export'])->name('entrance-fees.export');


    // Admin Saving Types Routes
    Route::get('/saving-types', [SavingTypeController::class, 'index'])->name('saving-types.index');
    Route::get('/saving-types/create', [SavingTypeController::class, 'create'])->name('saving-types.create');
    Route::post('/saving-types', [SavingTypeController::class, 'store'])->name('saving-types.store');
    Route::get('/saving-types/{savingType}/edit', [SavingTypeController::class, 'edit'])->name('saving-types.edit');
    Route::put('/saving-types/{savingType}', [SavingTypeController::class, 'update'])->name('saving-types.update');


    //savings import
    Route::get('/savings/import', [SavingController::class, 'import'])->name('savings.import');

    Route::post('/savings/process-import', [SavingController::class, 'processImport'])->name('savings.process-import');

    Route::get('/savings/download-format', [SavingController::class, 'downloadFormat'])
    ->name('savings.download-format');
//save settings
  Route::get('savings/settings', [SavingController::class, 'monthlySavingsSettings'])
        ->name('savings.settings.index');
            Route::post('/savings/settings/{setting}/approve', [SavingController::class, 'approveSavingsSetting'])
        ->name('savings.settings.approve');
    Route::post('/savings/settings/{setting}/reject', [SavingController::class, 'rejectSavingsSetting'])
        ->name('savings.settings.reject');
        Route::get('/get-member-savings-amount/{member}/{yearId}/{monthId}/{savingTypeId}', [SavingController::class, 'getMemberSavingsAmount']);

    //savings
     Route::get('/savings/withdraw', [SavingController::class, 'showWithdrawForm'])->name('savings.withdraw-form');
    Route::post('/savings/withdraw', [SavingController::class, 'withdraw'])->name('savings.withdraw');

    Route::get('/savings', [SavingController::class, 'index'])->name('savings');
    Route::get('/savings/create', [SavingController::class, 'create'])->name('savings.create');
    Route::post('/savings', [SavingController::class, 'store'])->name('savings.store');
    Route::get('/savings/bulk', [SavingController::class, 'bulkCreate'])->name('savings.bulk');
    Route::post('/savings/bulk', [SavingController::class, 'bulkStore'])->name('savings.bulk.store');
    Route::get('/savings/{saving}', [SavingController::class, 'show'])->name('savings.show');
    Route::get('/savings/{saving}/edit', [SavingController::class, 'edit'])->name('savings.edit');
    Route::put('/savings/{saving}', [SavingController::class, 'update'])->name('savings.update');
    Route::delete('/savings/{saving}', [SavingController::class, 'destroy'])->name('savings.destroy');

 //withdrawals
 Route::get('/withdrawals', [WithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::get('/withdrawals/create', [WithdrawalController::class, 'create'])->name('withdrawals.create');
    Route::post('/withdrawals', [WithdrawalController::class, 'store'])->name('withdrawals.store');
    Route::get('/withdrawals/{withdrawal}', [WithdrawalController::class, 'show'])->name('withdrawals.show');
    Route::post('/withdrawals/{withdrawal}/approve', [WithdrawalController::class, 'approve'])->name('withdrawals.approve');
    Route::post('/withdrawals/{withdrawal}/reject', [WithdrawalController::class, 'reject'])->name('withdrawals.reject');









    // Share Types
    Route::resource('/share-types', ShareTypeController::class)->names([
        'index' => 'share-types.index',
        'create' => 'share-types.create',
        'store' => 'share-types.store',
        'edit' => 'share-types.edit',
        'update' => 'share-types.update',
    ]);
    // Shares
    Route::get('/shares', [ShareController::class, 'index'])->name('shares.index');
    Route::get('/shares/create', [ShareController::class, 'create'])->name('shares.create');
     Route::get('/shares/withdraw', [ShareController::class, 'withdraw'])->name('shares.withdraw');
      Route::post('/shares/withdraw', [ShareController::class, 'withdrawStore'])->name('shares.withdrawals.store');
    Route::post('/shares', [ShareController::class, 'store'])->name('shares.store');
    Route::get('/shares/{share}', [ShareController::class, 'show'])->name('shares.show');
    Route::post('/shares/{share}/approve', [ShareController::class, 'approve'])->name('shares.approve');
    Route::post('/shares/{share}/reject', [ShareController::class, 'reject'])->name('shares.reject');
    Route::delete('/shares/{share}', [ShareController::class, 'destroy'])->name('shares.destroy');
  Route::post('/shares/import', [ShareController::class, 'import'])->name('shares.import');





    //loan import

    Route::get('/loans/import', [LoanController::class, 'import'])->name('loans.import');
    Route::post('/loans/process-import', [LoanController::class, 'processImport'])->name('loans.process-import');
    Route::get('/loans/download-format', [LoanController::class, 'downloadFormat'])->name('loans.download-format');




    // Admin Loan Management Routes

    Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');
    Route::get('/loans/create', [LoanController::class, 'create'])->name('loans.create');
    Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');
    Route::get('/loans/{loan}', [LoanController::class, 'show'])->name('loans.show');
    Route::post('/loans/{loan}/approve', [LoanController::class, 'approve'])->name('loans.approve');
    Route::post('/loans/{loan}/reject', [LoanController::class, 'reject'])->name('loans.reject');

    Route::get('/loans/repayments/upload', [LoanRepaymentController::class, 'upload'])->name('loans.repayments.upload');
    Route::post('/loans/repayments/process-upload', [LoanRepaymentController::class, 'processUpload'])->name('loans.repayments.process-upload');

       Route::get('/loans/repayments/download-template', [LoanRepaymentController::class, 'downloadTemplate'])->name('loans.repayments.download-template');


    // Admin Loan Types Routes
    Route::resource('loan-types', LoanTypeController::class);



    Route::resource('resources', ResourceController::class);


    //loan repayment

    Route::get('/loans/{loan}/repayments/create', [LoanRepaymentController::class, 'create'])->name('loans.repayments.create');
    Route::post('/loans/{loan}/repayments', [LoanRepaymentController::class, 'store'])->name('loans.repayments.store');



    //transactions
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::get('/transactions/export', [TransactionController::class, 'export'])->name('transactions.export');

    Route::get('/profile-updates', [AdminProfileUpdateController::class, 'index'])->name('profile-updates.index');
    Route::get('/profile-updates/{request}', [AdminProfileUpdateController::class, 'show'])->name('profile-updates.show');
    Route::post('/profile-updates/{request}/approve', [AdminProfileUpdateController::class, 'approve'])->name('profile-updates.approve');
    Route::post('/profile-updates/{request}/reject', [AdminProfileUpdateController::class, 'reject'])->name('profile-updates.reject');
    //resources
    Route::resource('resources', ResourceController::class);
    //faq
    Route::resource('faqs', FaqController::class);


    //admin management
    Route::resource('admins', AdminUserController::class);
    Route::resource('roles', RoleController::class);

    Route::get('/admin/roles', function () {
        return view('admin.roles.index');
    })->name('admin.roles.index');



    Route::prefix('/reports')->name('reports.')->group(function () {

        Route::get('/members', [ReportController::class, 'members'])->name('members');
        Route::get('/admins', [ReportController::class, 'admins'])->name('admins');
        Route::get('/entrance-fees', [ReportController::class, 'entranceFees'])->name('entrance-fees');
        Route::get('/savings', [ReportController::class, 'savings'])->name('savings');
        Route::get('/shares', [ReportController::class, 'shares'])->name('shares');
        Route::get('/loans', [ReportController::class, 'loans'])->name('loans');
        Route::get('/transactions', [ReportController::class, 'transactions'])->name('transactions');

        Route::get('/members/excel', [ReportController::class, 'membersExcel'])->name('members.excel');
        Route::get('/members/pdf', [ReportController::class, 'membersPdf'])->name('members.pdf');

        // Excel exports
        Route::get('/admins/excel', [ReportController::class, 'adminsExcel'])->name('admins.excel');

         Route::get('/saving/excel', [ReportController::class, 'savingsExcel'])->name('savings.excel');


        Route::get('/entrance-fees/excel', [ReportController::class, 'entranceFeesExcel'])->name('entrance-fees.excel');
        Route::get('/shares/excel', [ReportController::class, 'sharesExcel'])->name('shares.excel');
        Route::get('/loans/excel', [ReportController::class, 'loansExcel'])->name('loans.excel');
        Route::get('/transactions/excel', [ReportController::class, 'transactionsExcel'])->name('transactions.excel');

        // PDF exports
        Route::get('/admins/pdf', [ReportController::class, 'adminsPdf'])->name('admins.pdf');
        Route::get('/entrance-fees/pdf', [ReportController::class, 'entranceFeesPdf'])->name('entrance-fees.pdf');
        Route::get('/shares/pdf', [ReportController::class, 'sharesPdf'])->name('shares.pdf');
        Route::get('/loans/pdf', [ReportController::class, 'loansPdf'])->name('loans.pdf');
        Route::get('/transactions/pdf', [ReportController::class, 'transactionsPdf'])->name('transactions.pdf');

         Route::get('/savings/pdf', [ReportController::class, 'savingsPdf'])->name('savings.pdf');


    Route::get('/savings/summary', [ReportController::class, 'savingsSummary'])->name('savings.summary');
      Route::get('/savings/summary/excel', [ReportController::class, 'savingsSummaryExcel'])->name('savings.summary.excel');

    });
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');


      Route::resource('commodities', CommodityController::class);

    // Admin Commodity Subscriptions Management
    Route::get('commodity-subscriptions', [CommoditySubscriptionController::class, 'index'])->name('commodity-subscriptions.index');
    Route::get('commodity-subscriptions/{subscription}', [CommoditySubscriptionController::class, 'show'])->name('commodity-subscriptions.show');
    Route::post('commodity-subscriptions/{subscription}/approve', [CommoditySubscriptionController::class, 'approve'])->name('commodity-subscriptions.approve');
    Route::post('commodity-subscriptions/{subscription}/reject', [CommoditySubscriptionController::class, 'reject'])->name('commodity-subscriptions.reject');


    Route::post('/commodity-subscriptions/{subscription}/record-payment', [CommoditySubscriptionController::class, 'recordPayment'])->name('commodity-subscriptions.record-payment');
    Route::post('/commodity-payments/{payment}/approve', [CommoditySubscriptionController::class, 'approvePayment'])->name('commodity-payments.approve');
    Route::post('/commodity-payments/{payment}/reject', [CommoditySubscriptionController::class, 'rejectPayment'])->name('commodity-payments.reject');

    Route::get('/commodity-subscriptions/{subscription}', [CommoditySubscriptionController::class, 'show'])
        ->name('commodity-subscriptions.show');


        Route::get('/commodity-payments', [AdminCommodityPaymentController::class, 'index'])
    ->name('commodity-payments.index');
Route::get('/commodity-payments/create/{subscription}', [AdminCommodityPaymentController::class, 'create'])
    ->name('commodity-payments.create');
Route::post('/commodity-payments/{subscription}', [AdminCommodityPaymentController::class, 'store'])
    ->name('commodity-payments.store');
Route::get('/commodity-payments/{payment}', [AdminCommodityPaymentController::class, 'show'])
    ->name('commodity-payments.show');
Route::post('/commodity-payments/{payment}/approve', [AdminCommodityPaymentController::class, 'approve'])
    ->name('commodity-payments.approve');
Route::post('/commodity-payments/{payment}/reject', [AdminCommodityPaymentController::class, 'reject'])
    ->name('commodity-payments.reject');



    Route::get('/financial-summary', [AdminFinancialSummaryController::class, 'index'])->name('financial-summary.index');
// Route::get('/financial-summary/member/export', [AdminFinancialSummaryController::class, 'exportMember'])->name('financial-summary.member.export');
// Route::get('/financial-summary/member/pdf', [AdminFinancialSummaryController::class, 'downloadMemberPdf'])->name('financial-summary.member.pdf');
Route::get('/financial-summary/overall/export', [AdminFinancialSummaryController::class, 'exportOverall'])->name('financial-summary.overall.export');
Route::get('/financial-summary/overall/pdf', [AdminFinancialSummaryController::class, 'downloadOverallPdf'])->name('financial-summary.overall.pdf');
   Route::get('/financial-summary/member/{member}', [AdminFinancialSummaryController::class, 'member'])->name('financial-summary.member');



    // Export routes
    Route::get('/financial-summary/overall/export', [AdminFinancialSummaryController::class, 'exportOverall'])->name('financial-summary.overall.export');
    Route::get('/financial-summary/overall/pdf', [AdminFinancialSummaryController::class, 'downloadOverallPdf'])->name('financial-summary.overall.pdf');
    Route::get('/financial-summary/member/{member}/export', [AdminFinancialSummaryController::class, 'exportMember'])->name('financial-summary.member.export');
    Route::get('/financial-summary/member/{member}/pdf', [AdminFinancialSummaryController::class, 'downloadMemberPdf'])->name('financial-summary.member.pdf');



    // Transaction Summary Report
Route::get('/reports/transaction-summary', [TransactionSummaryController::class, 'index'])->name('reports.transaction-summary');
Route::get('/reports/transaction-summary/csv', [TransactionSummaryController::class, 'exportCsv'])->name('reports.transaction-summary.csv');
Route::get('/reports/transaction-summary/pdf', [TransactionSummaryController::class, 'exportPdf'])->name('reports.transaction-summary.pdf');



});
 //end of admin routes






Route::post('/profile/request-update', [ProfileUpdateController::class, 'requestUpdate'])
    ->name('profile.request-update');


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




Route::get('/test-email', function () {
    try {
        Mail::raw('This is a test email.', function ($message) {
            $message->to('lawalthb@gmail.com') // Replace with a valid recipient
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



Route::get('/members/pdf', [ProfileController::class, 'downloadPdf'])->name('members.pdf');
Route::get('/members/authority-deduct', [ProfileController::class, 'authorityDeduct'])->name('members.authority-deduct');



Route::get('/members/authority-deductm2', [ProfileController::class, 'authorityDeduct'])->name('member.documents');
