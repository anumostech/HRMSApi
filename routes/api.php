<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Admin\EmployeeApiController;
use App\Http\Controllers\Api\Admin\OrganizationApiController;
use App\Http\Controllers\Api\Admin\CompanyApiController;
use App\Http\Controllers\Api\Admin\HRApiController;
use App\Http\Controllers\Api\Employee\EmployeePortalApiController;
use \App\Http\Controllers\Api\Admin\DashboardApiController;

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

    // Organizations & Companies
    Route::apiResource('organizations', OrganizationApiController::class);
    Route::apiResource('companies', CompanyApiController::class);

    // HR Modules
    Route::get('designations', [HRApiController::class, 'indexDesignations']);
    Route::post('designations', [HRApiController::class, 'storeDesignation']);
    Route::put('designations/{designation}', [HRApiController::class, 'updateDesignation']);
    Route::delete('designations/{designation}', [HRApiController::class, 'destroyDesignation']);

    Route::get('departments', [HRApiController::class, 'indexDepartments']);
    Route::post('departments', [HRApiController::class, 'storeDepartment']);
    Route::delete('departments/{department}', [HRApiController::class, 'destroyDepartment']);
});

// Employee Protected Routes (Now also using auth:api)
Route::group(['middleware' => 'auth:api', 'prefix' => 'employee'], function () {
    Route::get('dashboard', [EmployeePortalApiController::class, 'dashboard']);
    Route::post('punch-in', [EmployeePortalApiController::class, 'punchIn']);
    Route::post('punch-out', [EmployeePortalApiController::class, 'punchOut']);
    
    // Leaves
    Route::get('leaves', [EmployeePortalApiController::class, 'leaves']);
    Route::post('leaves', [EmployeePortalApiController::class, 'storeLeave']);

    // Task Reports
    Route::get('task-reports', [EmployeePortalApiController::class, 'taskReports']);
    Route::post('task-reports', [EmployeePortalApiController::class, 'storeTaskReport']);

    // WFH Requests
    Route::get('wfh-requests', [EmployeePortalApiController::class, 'wfhRequests']);
    Route::post('wfh-requests', [EmployeePortalApiController::class, 'storeWfhRequest']);
});
