<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'employee_id', 'designation', 'department', 'company_id', 'dob', 'joining_date', 
        'gender', 'special_days', 'passport_full_name', 'passport_number', 
        'passport_issued_from', 'passport_issued_date', 'passport_expiry_date', 
        'place_of_birth', 'father_name', 'mother_name', 'address', 
        'passport_1st_page', 'passport_2nd_page', 'passport_outer_page', 'passport_id_page', 
        'visa_number', 'visa_issued_date', 'visa_expiry_date', 'visa_page', 
        'labor_number', 'labor_issued_date', 'labor_expiry_date', 'labor_card', 
        'eid_number', 'eid_issued_date', 'eid_expiry_date', 'eid_1st_page', 'eid_2nd_page', 
        'dependents', 'educational_1st_page', 'educational_2nd_page', 
        'company_mobile_number', 'personal_number', 'other_number', 'home_country_number', 
        'company_email', 'personal_email', 'home_country_id_proof', 'status'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function attendanceLogs()
    {
        return $this->hasMany(AttendanceLog::class, 'userid', 'id');
    }
}
