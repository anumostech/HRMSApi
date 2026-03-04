<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'dob' => 'nullable|date',
            'joining_date' => 'nullable|date',
            'gender' => 'nullable|string|max:255',
            'special_days' => 'nullable|string',
            
            // Passport
            'passport_full_name' => 'nullable|string|max:255',
            'passport_number' => 'nullable|string|max:255',
            'passport_issued_from' => 'nullable|string|max:255',
            'passport_issued_date' => 'nullable|date',
            'passport_expiry_date' => 'nullable|date',
            'place_of_birth' => 'nullable|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'address' => 'nullable|string',

            // Documents
            'passport_1st_page' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'passport_2nd_page' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'passport_outer_page' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'passport_id_page' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'visa_page' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'labor_card' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'eid_1st_page' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'eid_2nd_page' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'educational_1st_page' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'educational_2nd_page' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'home_country_id_proof' => 'nullable|file|mimes:pdf,jpg,png|max:2048',

            // Details
            'visa_number' => 'nullable|string|max:255',
            'visa_issued_date' => 'nullable|date',
            'visa_expiry_date' => 'nullable|date',
            'labor_number' => 'nullable|string|max:255',
            'labor_issued_date' => 'nullable|date',
            'labor_expiry_date' => 'nullable|date',
            'eid_number' => 'nullable|string|max:255',
            'eid_issued_date' => 'nullable|date',
            'eid_expiry_date' => 'nullable|date',
            'dependents' => 'nullable|string|max:255',
            'company_mobile_number' => 'nullable|string|max:255',
            'personal_number' => 'nullable|string|max:255',
            'other_number' => 'nullable|string|max:255',
            'home_country_number' => 'nullable|string|max:255',
            'company_email' => 'nullable|email|max:255',
            'personal_email' => 'nullable|email|max:255',
            'status' => 'nullable|in:active,inactive',
        ];
    }
}
