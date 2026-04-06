<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\Employee;
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

        $query = Employee::with(['company', 'department', 'designation']);

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

        $data['organization_id'] = $data['organization_id'] ?? 1;
        $data['password'] = Hash::make($request->get('password', 'Thesay@ae'));

        $employee = Employee::create($data);

        return $this->success($employee, 'Employee created successfully', 201);
    }

    public function show(Employee $employee): JsonResponse
    {
        $employee->load(['company', 'department', 'designation']);
        return $this->success($employee);
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee): JsonResponse
    {
        $data = $request->validated();
        $data = $this->handleDocuments($data);
        $data = $this->handleSpecialDays($request, $data);

        $employee->update($data);

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
