<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\AgreementController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\WfhRequestController;
use App\Http\Controllers\TaskReportController;
use App\Http\Controllers\Employee\PunchController;
use App\Http\Controllers\Employee\TaskReportController as EmployeeTaskReportController;
use App\Http\Controllers\Employee\WfhRequestController as EmployeeWfhRequestController;
use App\Http\Controllers\Employee\LeaveController as EmployeeLeaveController;
use Dom\Document;
use Illuminate\Support\Facades\Artisan;

// Auth Routes
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');;
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');;
Route::post('/login', [LoginController::class, 'login'])->name('authenticate');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Forgot Password Routes
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetCode'])->name('password.email');
Route::get('/reset-password/{email}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset.form');
Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::prefix('/attendance')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::get('/upload', [AttendanceController::class, 'create'])->name('attendance.upload');
        Route::post('/upload', [AttendanceController::class, 'store'])->name('attendance.store');
        Route::get('/attendance/progress/{id}', [AttendanceController::class, 'progress'])->name('attendance.progress');
        Route::get('/punch-in-today', [AttendanceController::class, 'indexPunchInToday'])->name('attendance.punchInToday');
        Route::get('/punch-in-yesterday', [AttendanceController::class, 'indexPunchInYesterday'])->name('attendance.punchInYesterday');
        Route::get('/punch-out-today', [AttendanceController::class, 'indexPunchOutToday'])->name('attendance.punchOutToday');
        Route::get('/punch-out-yesterday', [AttendanceController::class, 'indexPunchOutYesterday'])->name('attendance.punchOutYesterday');
        Route::get('/late', [AttendanceController::class, 'lateAttendance'])->name('attendance.late');
        Route::get('/absent', [AttendanceController::class, 'absentAttendance'])->name('attendance.absent');
    });


    // Dashboard Routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/api/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');
    Route::get('/api/dashboard/charts', [DashboardController::class, 'getChartData'])->name('dashboard.charts');
    Route::get('/api/dashboard/notifications', [DashboardController::class, 'getNotifications'])->name('dashboard.notifications');
    Route::post('/api/notifications/read/{id}', [DashboardController::class, 'markAsRead'])->name('dashboard.notifications.read');

    Route::resource('employees', EmployeeController::class);

    Route::get('/documents', [DocumentController::class, 'index'])->name('documents');
    Route::post('/documents/upload', [DocumentController::class, 'upload'])->name('documents.upload');
    Route::post('/documents/store', [DocumentController::class, 'store'])->name('documents.store');
    Route::post('/documents/delete/{id}', [DocumentController::class, 'deleteDocument'])->name('documents.delete');

    Route::post('/employees/{employee}/update-status', [EmployeeController::class, 'updateStatus'])->name('employees.updateStatus');
    Route::post('/upload-temp-document', [EmployeeController::class, 'uploadTempDocument'])->name('documents.uploadTempDocument');
    Route::get('/document/preview', [EmployeeController::class, 'preview'])->name('document.preview');
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');

    Route::resource('organizations', OrganizationController::class);
    Route::resource('companies', CompanyController::class);
    Route::get('/companies/by-organization/{organizationId}', [CompanyController::class, 'getByOrganization'])->name('companies.by_organization');

    Route::prefix('/departments')->group(function () {
        Route::post('/store', [DepartmentController::class, 'store'])->name('departments.store');
    });

    Route::prefix('/agreements')->group(function () {
        Route::get('/index', [AgreementController::class, 'index'])->name('agreements.index');
        Route::post('/parties/store', [DocumentController::class, 'storeParty'])->name('parties.store');
    });

    // Leave Management Routes (Admin)
    Route::prefix('/leaves')->group(function () {
        Route::get('/', [LeaveController::class, 'index'])->name('leaves.index');
        Route::post('/{leaveRequest}/update-status', [LeaveController::class, 'updateStatus'])->name('leaves.updateStatus');

        // Leave Type Management
        Route::get('/types', [\App\Http\Controllers\LeaveTypeController::class, 'index'])->name('leaves.types.index');
        Route::post('/types', [\App\Http\Controllers\LeaveTypeController::class, 'store'])->name('leaves.types.store');
        Route::post('/types/update/{leaveType}', [\App\Http\Controllers\LeaveTypeController::class, 'update'])->name('leaves.types.update');
        Route::post('/types/delete/{leaveType}', [\App\Http\Controllers\LeaveTypeController::class, 'destroy'])->name('leaves.types.delete');
        Route::post('/types/update-status/{leaveType}', [\App\Http\Controllers\LeaveTypeController::class, 'updateStatus'])->name('leaves.types.updateStatus');
    });

    // HR Modules
    Route::resource('designations', DesignationController::class);

    // WFH Requests
    Route::get('/wfh-requests', [WfhRequestController::class, 'index'])->name('wfh_requests.index');
    Route::post('/wfh-requests/{wfhRequest}/status', [WfhRequestController::class, 'updateStatus'])->name('wfh_requests.status');

    // Task Reports
    Route::get('/task-reports', [TaskReportController::class, 'index'])->name('task_reports.index');

});

// ─── Employee Portal (separate guard — no admin auth needed) ──────────────────
Route::prefix('employee')->name('employee.')->group(function () {
    Route::get('/login',  [\App\Http\Controllers\Employee\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Employee\AuthController::class, 'login'])->name('authenticate');
    Route::post('/logout', [\App\Http\Controllers\Employee\AuthController::class, 'logout'])->name('logout');

    Route::middleware('employee.auth')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Employee\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile',   [\App\Http\Controllers\Employee\ProfileController::class, 'show'])->name('profile');
        Route::post('/profile',  [\App\Http\Controllers\Employee\ProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile/password', [\App\Http\Controllers\Employee\ProfileController::class, 'changePassword'])->name('profile.password');

        // Employee Leave Routes
        Route::prefix('/leaves')->group(function () {
            Route::get('/', [EmployeeLeaveController::class, 'index'])->name('leaves.index');
            Route::get('/create', [EmployeeLeaveController::class, 'create'])->name('leaves.create');
            Route::post('/store', [EmployeeLeaveController::class, 'store'])->name('leaves.store');
        });

        // WFH Requests
        Route::get('/wfh-requests', [EmployeeWfhRequestController::class, 'index'])->name('wfh.index');
        Route::get('/wfh-requests/create', [EmployeeWfhRequestController::class, 'create'])->name('wfh.create');
        Route::post('/wfh-requests/store', [EmployeeWfhRequestController::class, 'store'])->name('wfh.store');

        // Task Reports
        Route::get('/task-reports', [EmployeeTaskReportController::class, 'index'])->name('task_reports.index');

        // Punch System
        Route::post('/punch-in', [PunchController::class, 'punchIn'])->name('punch.in');
        Route::post('/punch-out', [PunchController::class, 'punchOut'])->name('punch.out');
    });
});



Route::get('/run-migrations', function () {
    Artisan::call('migrate:fresh', ['--force' => true]);
    return 'Migration runned successfully';
});


Route::get('/run-seeder', function () {
    Artisan::call('db:seed', ['--force' => true]);
    return 'Database seeded successfully';
});

Route::get('/optimize-clear', function () {
    Artisan::call('optimize:clear');
    return 'Optimization cache cleared';
});

Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return 'Storage linked';
});
