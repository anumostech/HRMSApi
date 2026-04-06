<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\Employee;
use App\Models\User;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class EmployeeApiController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $status = $request->get('status', 'active');
        $perPage = $request->get('per_page', 15);

        $query = Employee::with(['user.company', 'user.department', 'user.designation']);

        if ($status === 'inactive') {
            $query = $query->onlyInactive();
        }

        $employees = $query->paginate($perPage);

        return $this->success($employees);
    }

    public function store(StoreEmployeeRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data = $this->handleDocuments($data);
        $data = $this->handleSpecialDays($request, $data);

        // 1. Create User
        $user = User::create([
            'username' => $data['username'] ?? $data['company_email'] ?? $data['first_name'],
            'email' => $data['company_email'] ?? $data['personal_email'],
            'password' => Hash::make($request->get('password', 'Thesay@ae')),
            'organization_id' => $data['organization_id'] ?? 1,
            'company_id' => $data['company_id'],
            'department_id' => $data['department_id'] ?? null,
            'designation_id' => $data['designation_id'] ?? null,
            'type' => $data['type'] ?? 'staff',
            'status' => $data['status'] ?? 'active',
        ]);

        // Assign Role
        $roleName = $data['role'] ?? 'Employee';
        $user->assignRole($roleName);

        // 2. Create Employee linked to User
        $data['user_id'] = $user->id;
        
        // Remove fields that are now on User table
        unset($data['organization_id'], $data['company_id'], $data['department_id'], $data['designation_id'], $data['password']);

        $employee = Employee::create($data);

        // Load relations for response
        $employee->load(['user.company', 'user.department', 'user.designation']);

        return $this->success($employee, 'Employee and User created successfully', 201);
    }

    public function show(Employee $employee): JsonResponse
    {
        $employee->load(['user.company', 'user.department', 'user.designation']);
        return $this->success($employee);
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee): JsonResponse
    {
        $data = $request->validated();
        $data = $this->handleDocuments($data);
        $data = $this->handleSpecialDays($request, $data);

        // Update User part if User exists
        if ($employee->user) {
            $userData = [];
            if (isset($data['username'])) $userData['username'] = $data['username'];
            if (isset($data['company_email'])) $userData['email'] = $data['company_email'];
            if (!empty($data['password'])) $userData['password'] = Hash::make($data['password']);
            if (isset($data['organization_id'])) $userData['organization_id'] = $data['organization_id'];
            if (isset($data['company_id'])) $userData['company_id'] = $data['company_id'];
            if (isset($data['department_id'])) $userData['department_id'] = $data['department_id'];
            if (isset($data['designation_id'])) $userData['designation_id'] = $data['designation_id'];
            if (isset($data['type'])) $userData['type'] = $data['type'];
            if (isset($data['status'])) $userData['status'] = $data['status'];

            if (!empty($userData)) {
                $employee->user->update($userData);
            }

            // Update role if provided
            if (isset($data['role'])) {
                $employee->user->syncRoles([$data['role']]);
            }
        }

        // Remove fields that belong to User
        unset($data['organization_id'], $data['company_id'], $data['department_id'], $data['designation_id'], $data['password'], $data['username'], $data['type']);

        $employee->update($data);

        $employee->load(['user.company', 'user.department', 'user.designation']);

        return $this->success($employee, 'Employee updated successfully');
    }

    public function destroy(Employee $employee): JsonResponse
    {
        $employee->delete();
        return $this->success(null, 'Employee deleted successfully');
    }

    public function updateStatus(Request $request, Employee $employee): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:active,inactive'
        ]);

        $employee->update(['status' => $request->status]);

        return $this->success($employee, 'Status updated successfully');
    }

    private function handleDocuments(array $data): array
    {
        $documentFields = [
            'passport_1st_page', 'passport_2nd_page', 'passport_outer_page',
            'passport_id_page', 'visa_page', 'labor_card', 'eid_1st_page',
            'eid_2nd_page', 'educational_1st_page', 'educational_2nd_page',
            'home_country_id_proof'
        ];

        foreach ($documentFields as $field) {
            if (!empty($data[$field]) && strpos($data[$field], 'temp/') === 0) {
                $tempPath = $data[$field];
                $fileName = basename($tempPath);
                $newPath = 'documents/' . $fileName;

                if (Storage::disk('public')->exists($tempPath)) {
                    Storage::disk('public')->move($tempPath, $newPath);
                    $data[$field] = $newPath;
                }
            }
        }
        return $data;
    }

    private function handleSpecialDays(Request $request, array $data): array
    {
        $names = $request->special_days_name;
        $dates = $request->special_days_date;
        $specialDays = [];

        if ($names && is_array($names)) {
            foreach ($names as $key => $name) {
                if ($name) {
                    $specialDays[] = [
                        'name' => $name,
                        'date' => $dates[$key] ?? null
                    ];
                }
            }
        }
        $data['special_days'] = !empty($specialDays) ? $specialDays : null;
        return $data;
    }
}
