<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Organisation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $organisation_id = $request->get('organisation_id');
        $companies = Company::with('organisation')
            ->when($organisation_id, function($query) use ($organisation_id) {
                return $query->where('organisation_id', $organisation_id);
            })
            ->latest()
            ->get();
        
        return view('admin.companies.index', compact('companies', 'organisation_id'));
    }

    public function getByOrganization($organization_id)
    {
        $companies = Company::where('organisation_id', $organization_id)->get();
        return response()->json($companies);
    }

    public function create(Request $request)
    {
        $organisation_id = $request->get('organisation_id');
        $organisations = Organisation::where('has_multiple_companies', true)->get();
        return view('admin.companies.create', compact('organisation_id', 'organisations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'organisation_id' => 'required|exists:organisations,id',
            'company_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'address' => 'nullable|string',
        ]);

        $data = $request->except('logo');
        
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos/companies', 'public');
            $data['logo'] = $path;
        }

        Company::create($data);

        return redirect()->route('companies.index', ['organisation_id' => $request->organisation_id])
            ->with('success', 'Company created successfully.');
    }

    public function edit(Company $company)
    {
        $organisations = Organisation::where('has_multiple_companies', true)->get();
        return view('admin.companies.edit', compact('company', 'organisations'));
    }

    public function update(Request $request, Company $company)
    {
        $request->validate([
            'organisation_id' => 'required|exists:organisations,id',
            'company_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'address' => 'nullable|string',
        ]);

        $data = $request->except('logo');

        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }
            $path = $request->file('logo')->store('logos/companies', 'public');
            $data['logo'] = $path;
        }

        $company->update($data);

        return redirect()->route('companies.index', ['organisation_id' => $company->organisation_id])
            ->with('success', 'Company updated successfully.');
    }

    public function destroy(Company $company)
    {
        $org_id = $company->organisation_id;
        $company->delete();
        return redirect()->route('companies.index', ['organisation_id' => $org_id])
            ->with('success', 'Company deleted successfully.');
    }
}
