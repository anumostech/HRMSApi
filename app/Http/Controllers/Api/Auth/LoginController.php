<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class LoginController extends ApiController
{
    /**
     * Login (Single endpoint for all users)
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required', 
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $loginField = filter_var($request->username, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        if (!$token = auth('api')->attempt([
            $loginField => $request->username,
            'password' => $request->password
        ])) {
            return $this->error('Unauthorized - Invalid credentials', 401);
        }

        $user = auth('api')->user();

        if ($user->status !== 'active') {
            auth('api')->logout();
            return $this->error('Account is inactive', 403);
        }

        return $this->respondWithToken($token, $user);
    }

    /**
     * Response with token + user + employee + roles
     */
    protected function respondWithToken($token, $user): JsonResponse
    {
        $employee = $user->employee;

        return $this->success([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,

            'user' => [
                // 🔹 User table data
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'type' => $user->type,
                'status' => $user->status,

                // 🔹 Employee table data
                'employee' => $employee ? [
                    'id' => $employee->id,
                    'name' => trim(($employee->first_name ?? '') . ' ' . ($employee->last_name ?? '')),
                    'employee_id' => $employee->employee_id,
                    'company_email' => $employee->company_email,
                    'phone' => $employee->personal_number,
                    'joining_date' => $employee->joining_date,
                ] : null,

                // 🔹 Relations
                'organization_id' => $user->organization_id,
                'company_id' => $user->company_id,
                'department_id' => $user->department_id,
                'designation_id' => $user->designation_id,

                // 🔹 Avatar
                'avatar' => $user->avatar_url,

                // 🔹 Roles & Permissions (Spatie)
                'roles' => $user->getRoleNames(),
                'permissions' => $user->getAllPermissions()->pluck('name'),
            ]
        ], 'Login successful');
    }

    /**
     * Logout
     */
    public function logout(): JsonResponse
    {
        auth('api')->logout();
        return $this->success(null, 'Successfully logged out');
    }

    /**
     * Get logged-in user
     */
    public function me(): JsonResponse
    {
        if (!auth('api')->check()) {
            return $this->error('Unauthenticated', 401);
        }

        $user = auth('api')->user();

        return $this->respondWithToken(null, $user);
    }
}