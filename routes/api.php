<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\ForgotPasswordApiController;
use App\Http\Controllers\Api\Admin\EmployeeApiController;
use App\Http\Controllers\Api\Admin\OrganizationApiController;
use App\Http\Controllers\Api\Admin\CompanyApiController;
use App\Http\Controllers\Api\Admin\HRApiController;
use App\Http\Controllers\Api\Admin\DashboardApiController;
use App\Http\Controllers\Api\Admin\PartyApiController;
use App\Http\Controllers\Api\Admin\DocumentApiController;
use App\Http\Controllers\Api\Admin\FolderApiController;
use App\Http\Controllers\Api\Admin\LeaveApiController;
use App\Http\Controllers\Api\Admin\WfhApiController;
use App\Http\Controllers\Api\Admin\AttendanceApiController;
use App\Http\Controllers\Api\Admin\LeaveTypeApiController;
use App\Http\Controllers\Api\Admin\ReportApiController;
use App\Http\Controllers\Api\Employee\EmployeePortalApiController;
use App\Http\Controllers\Api\Employee\ProfileApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Unified Auth Routes
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('logout', [LoginController::class, 'logout'])->middleware('auth:api');
    Route::get('me', [LoginController::class, 'me'])->middleware('auth:api');

    Route::post('forgot-password', [ForgotPasswordApiController::class, 'sendResetCode']);
    Route::post('reset-password', [ForgotPasswordApiController::class, 'resetPassword']);
});

// Admin Protected Routes
Route::group(['middleware' => 'auth:api', 'prefix' => 'admin'], function () {
    // Dashboard
    Route::get('dashboard', [DashboardApiController::class, 'index']);
    Route::get('dashboard/summary', [DashboardApiController::class, 'getSummaryStats']);
    Route::get('dashboard/charts', [DashboardApiController::class, 'getDetailedChartData']);
    Route::get('notifications', [DashboardApiController::class, 'getNotifications']);
    Route::post('notifications/{id}/mark-as-read', [DashboardApiController::class, 'markAsRead']);

    // Employees
    Route::apiResource('employees', EmployeeApiController::class);
    Route::post('employees/{employee}/update-status', [EmployeeApiController::class, 'updateStatus']);

    // Attendance
    Route::get('attendance', [AttendanceApiController::class, 'index']);
    Route::post('attendance/upload', [AttendanceApiController::class, 'upload']);
    Route::get('attendance/upload-status/{id}', [AttendanceApiController::class, 'uploadStatus']);
    Route::get('attendance/punch-in-today', [AttendanceApiController::class, 'punchInToday']);
    Route::get('attendance/punch-in-yesterday', [AttendanceApiController::class, 'punchInYesterday']);
    Route::get('attendance/punch-out-today', [AttendanceApiController::class, 'punchOutToday']);
    Route::get('attendance/late-comers', [AttendanceApiController::class, 'lateComers']);
    Route::get('attendance/absentees', [AttendanceApiController::class, 'absentees']);

    // Organizations & Companies
    Route::apiResource('organizations', OrganizationApiController::class);
    Route::apiResource('companies', CompanyApiController::class);
    Route::apiResource('parties', PartyApiController::class);
    Route::apiResource('documents', DocumentApiController::class);
    Route::apiResource('folders', FolderApiController::class);
    Route::post('documents/upload', [DocumentApiController::class, 'upload']);
    Route::get('documents-folders', [DocumentApiController::class, 'getFolders']);
    Route::get('shareable-users', [DocumentApiController::class, 'getShareableUsers']);

    // HR Modules
    Route::get('designations', [HRApiController::class, 'indexDesignations']);
    Route::post('designations', [HRApiController::class, 'storeDesignation']);
    Route::get('designations/{designation}', [HRApiController::class, 'showDesignation']);
    Route::put('designations/{designation}', [HRApiController::class, 'updateDesignation']);
    Route::delete('designations/{designation}', [HRApiController::class, 'destroyDesignation']);

    Route::get('departments', [HRApiController::class, 'indexDepartments']);
    Route::post('departments', [HRApiController::class, 'storeDepartment']);
    Route::get('departments/{department}', [HRApiController::class, 'showDepartment']);
    Route::put('departments/{department}', [HRApiController::class, 'updateDepartment']);
    Route::delete('departments/{department}', [HRApiController::class, 'destroyDepartment']);

    // Leave Management (Admin side)
    Route::get('leaves', [LeaveApiController::class, 'index']);
    Route::get('leaves/{leaveRequest}', [LeaveApiController::class, 'show']);
    Route::post('leaves/{leaveRequest}/status', [LeaveApiController::class, 'updateStatus']);

    // WFH Requests (Admin side)
    Route::get('wfh-requests', [WfhApiController::class, 'index']);
    Route::get('wfh-requests/{wfhRequest}', [WfhApiController::class, 'show']);
    Route::post('wfh-requests/{wfhRequest}/status', [WfhApiController::class, 'updateStatus']);

    // Leave Types (Admin side)
    Route::apiResource('leave-types', LeaveTypeApiController::class);
    Route::post('leave-types/{leaveType}/status', [LeaveTypeApiController::class, 'updateStatus']);

    // Reports
    Route::group(['prefix' => 'reports'], function () {
        Route::get('attendance', [ReportApiController::class, 'attendanceReport']);
        Route::get('leaves', [ReportApiController::class, 'leaveReport']);
        // If you still want the previous staffing/summaries, I can keep them, 
        // but for the UI table listing:
        Route::get('employees', [ReportApiController::class, 'employeeReport']);
        Route::get('export', [ReportApiController::class, 'export']);
    });
});

// Employee Protected Routes (Now also using auth:api)
Route::group(['middleware' => 'auth:api', 'prefix' => 'employee'], function () {
    Route::get('dashboard', [EmployeePortalApiController::class, 'dashboard']);
    Route::post('punch-in', [EmployeePortalApiController::class, 'punchIn']);
    Route::post('punch-out', [EmployeePortalApiController::class, 'punchOut']);
    
    // Leaves
    Route::get('leaves', [EmployeePortalApiController::class, 'leaves']);
    Route::get('leave-balance', [EmployeePortalApiController::class, 'leaveTypesAndBalance']);
    Route::post('leaves', [EmployeePortalApiController::class, 'storeLeave']);

    // Task Reports
    Route::get('task-reports', [EmployeePortalApiController::class, 'taskReports']);
    Route::post('task-reports', [EmployeePortalApiController::class, 'storeTaskReport']);

    // WFH Requests
    Route::get('wfh-requests', [EmployeePortalApiController::class, 'wfhRequests']);
    Route::post('wfh-requests', [EmployeePortalApiController::class, 'storeWfhRequest']);

    // Profile Settings
    Route::post('change-password', [ProfileApiController::class, 'changePassword']);
    Route::post('update-profile', [ProfileApiController::class, 'updateProfile']);
});
