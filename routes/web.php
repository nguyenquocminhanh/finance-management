<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Pos\MemberController;
use App\Http\Controllers\Pos\IncomeController;
use App\Http\Controllers\Pos\OutcomeController;
use App\Http\Controllers\Pos\ReportController;
use App\Http\Controllers\Pos\DefaultController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Laravel Breeze, must be auth and verified -> access dashboard
Route::get('/dashboard', function () {
    return view('admin.index');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';

// Default All Route
Route::controller(DefaultController::class)->group(function () {
    Route::get('/check-income-amount', 'CheckIncomeAmount')->name('check-income-amount');
    Route::get('/check-outcome-amount', 'CheckOutcomeAmount')->name('check-outcome-amount');

    Route::get('/check-exist-period-for-add', 'CheckExistPeriodForAdd')->name('check-exist-period-for-add');
    Route::get('/check-exist-period-for-edit', 'CheckExistPeriodForEdit')->name('check-exist-period-for-edit');
});


// group middleware
Route::middleware('auth', 'verified')->group(function () {
    // Admin All Route
    Route::controller(AdminController::class)->group(function () {
        Route::get('/admin/logout', 'destroy')->name('admin.logout');
        Route::get('/admin/profile', 'profile')->name('admin.profile');
        Route::get('/edit/profile', 'editProfile')->name('edit.profile');
        Route::post('/store/profile', 'storeProfile')->name('store.profile');

        Route::get('/change/password', 'changePassword')->name('change.password');
        Route::post('/update/password', 'updatePassword')->name('update.password');
    });

    // Member All Route
    Route::controller(MemberController::class)->group(function () {
        Route::get('/member/all', 'MemberAll')->name('member.all');

        Route::get('/member/add', 'MemberAdd')->name('member.add');
        Route::post('/member/store', 'MemberStore')->name('member.store');
        
        Route::get('/member/edit/{id}', 'MemberEdit')->name('member.edit');
        Route::post('/member/update', 'MemberUpdate')->name('member.update');

        Route::get('/member/delete/{id}', 'MemberDelete')->name('member.delete');
    });

    // Income All Route
    Route::controller(IncomeController::class)->group(function () {
        Route::get('/income/all', 'IncomeAll')->name('income.all');

        Route::get('/income/add', 'IncomeAdd')->name('income.add');
        Route::post('/income/store', 'IncomeStore')->name('income.store');

        Route::get('/income/edit/{id}', 'IncomeEdit')->name('income.edit');
        Route::post('/income/update', 'IncomeUpdate')->name('income.update');

        Route::get('/income/delete/{id}', 'IncomeDelete')->name('income.delete');
    });

    // Outcome All Route
    Route::controller(OutcomeController::class)->group(function () {
        Route::get('/outcome/all', 'OutcomeAll')->name('outcome.all');

        Route::get('/outcome/add', 'OutcomeAdd')->name('outcome.add');
        Route::post('/outcome/store', 'OutcomeStore')->name('outcome.store');

        Route::get('/outcome/edit/{id}', 'OutcomeEdit')->name('outcome.edit');
        Route::post('/outcome/update', 'OutcomeUpdate')->name('outcome.update');

        Route::get('/outcome/delete/{id}', 'OutcomeDelete')->name('outcome.delete');
    });

    // Report All Route
    Route::controller(ReportController::class)->group(function () {
        Route::get('/report/all', 'ReportAll')->name('report.all');

        Route::get('/report/add', 'ReportAdd')->name('report.add');
        Route::post('/report/store', 'ReportStore')->name('report.store');

        Route::get('/report/edit/{id}', 'ReportEdit')->name('report.edit');
        Route::post('/report/update', 'ReportUpdate')->name('report.update');

        Route::get('/report/delete/{id}', 'ReportDelete')->name('report.delete');

        Route::get('/monthly/report', 'MonthlyReport')->name('monthly.report');
        Route::get('/monthly/report/print/{id}', 'MonthlyReportPrint')->name('monthly.report.print');

        Route::get('/period/report/list', 'PeriodReportList')->name('period.report.list');
        Route::get('/period/list/pdf', 'PeriodListPdf')->name('period.list.pdf');

        Route::get('/member/wise/report', 'MemberWiseReport')->name('member.wise.report');
        Route::get('/member/wise/report/pdf', 'MemberWiseReportPdf')->name('member.wise.report.pdf');
    });




}); // end group middleware
