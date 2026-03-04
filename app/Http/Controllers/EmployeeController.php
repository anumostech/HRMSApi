<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Company;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'active');
        $employees = Employee::with('company')
            ->where('status', $status)
            ->paginate(15);

        return view('employees.index', compact('employees', 'status'));
    }

    public function create()
    {
        $companies = Company::all();
        return view('employees.create', compact('companies'));
    }

    public function store(StoreEmployeeRequest $request)
    {
        $data = $request->validated();

        $documentFields = [
            'passport_1st_page', 'passport_2nd_page', 'passport_outer_page', 'passport_id_page',
            'visa_page', 'labor_card', 'eid_1st_page', 'eid_2nd_page',
            'educational_1st_page', 'educational_2nd_page', 'home_country_id_proof'
        ];

        foreach ($documentFields as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store('documents', 'public');
            }
        }

        Employee::create($data);

        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    public function show(Employee $employee)
    {
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $companies = Company::all();
        return view('employees.edit', compact('employee', 'companies'));
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $data = $request->validated();

        $documentFields = [
            'passport_1st_page', 'passport_2nd_page', 'passport_outer_page', 'passport_id_page',
            'visa_page', 'labor_card', 'eid_1st_page', 'eid_2nd_page',
            'educational_1st_page', 'educational_2nd_page', 'home_country_id_proof'
        ];

        foreach ($documentFields as $field) {
            if ($request->hasFile($field)) {
                // Delete old file if exists
                if ($employee->$field) {
                    Storage::disk('public')->delete($employee->$field);
                }
                $data[$field] = $request->file($field)->store('documents', 'public');
            }
        }

        $employee->update($data);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deactivated (soft deleted) successfully.');
    }
}
