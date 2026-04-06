<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AdminAuthController;
use App\Http\Controllers\Api\Auth\EmployeeAuthController;
use App\Http\Controllers\Api\Admin\EmployeeApiController;
use App\Http\Controllers\Api\Admin\OrganizationApiController;
use App\Http\Controllers\Api\Admin\CompanyApiController;
use App\Http\Controllers\Api\Admin\HRApiController;
use App\Http\Controllers\Api\Employee\EmployeePortalApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Admin Auth Routes
Route::group(['prefix' => 'admin/auth'], function () {
    Route::post('login', [AdminAuthController::class, 'login']);
    Route::post('logout', [AdminAuthController::class, 'logout']);
    Route::post('refresh', [AdminAuthController::class, 'refresh']);
    Route::get('me', [AdminAuthController::class, 'me']);
});

// Employee Auth Routes
Route::group(['prefix' => 'employee/auth'], function () {
    Route::post('login', [EmployeeAuthController::class, 'login']);
    Route::post('logout', [EmployeeAuthController::class, 'logout']);
    Route::post('refresh', [EmployeeAuthController::class, 'refresh']);
    Route::get('me', [EmployeeAuthController::class, 'me']);
});

// Admin Protected Routes
Route::group(['middleware' => 'auth:api', 'prefix' => 'admin'], function () {
    // Employees
    Route::apiResource('employees', EmployeeApiController::class);
    Route::post('employees/{employee}/update-status', [EmployeeApiController::class, 'updateStatus']);

    // Organizations & Companies
    Route::apiResource('organizations', OrganizationApiController::class);
    Route::apiResource('companies', CompanyApiController::class);

    // HR Modules (Small)
    Route::get('designations', [HRApiController::class, 'indexDesignations']);
    Route::post('designations', [HRApiController::class, 'storeDesignation']);
    Route::put('designations/{designation}', [HRApiController::class, 'updateDesignation']);
    Route::delete('designations/{designation}', [HRApiController::class, 'destroyDesignation']);

    Route::get('departments', [HRApiController::class, 'indexDepartments']);
    Route::post('departments', [HRApiController::class, 'storeDepartment']);
    Route::delete('departments/{department}', [HRApiController::class, 'destroyDepartment']);
});

// Employee Protected Routes
Route::group(['middleware' => 'auth:employee_api', 'prefix' => 'employee'], function () {
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
