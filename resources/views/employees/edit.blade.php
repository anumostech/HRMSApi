@extends('layouts.app')

@section('title', 'Edit Employee')

@section('content')
<div class="page-header">
    <h1 class="page-title">Edit Employee</h1>
    <div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Employees</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Employee</li>
        </ol>
    </div>
</div>

<form action="{{ route('employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <!-- Basic Details -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Basic Details</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $employee->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Designation</label>
                            <input type="text" class="form-control" name="designation" value="{{ old('designation', $employee->designation) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Department</label>
                            <input type="text" class="form-control" name="department" value="{{ old('department', $employee->department) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Company <span class="text-danger">*</span></label>
                            <select class="form-control @error('company_id') is-invalid @enderror" name="company_id" required>
                                <option value="">Select Company</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ old('company_id', $employee->company_id) == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                                @endforeach
                            </select>
                            @error('company_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" name="dob" value="{{ old('dob', $employee->dob) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Date of Joining</label>
                            <input type="date" class="form-control" name="joining_date" value="{{ old('joining_date', $employee->joining_date) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Gender</label>
                            <select class="form-control" name="gender">
                                <option value="">Select Gender</option>
                                <option value="Male" {{ old('gender', $employee->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender', $employee->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ old('gender', $employee->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-control" name="status">
                                <option value="active" {{ old('status', $employee->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $employee->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Special Days (Birthdays, etc.)</label>
                            <input type="text" class="form-control" name="special_days" value="{{ old('special_days', $employee->special_days) }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Passport Details -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Passport Details</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Passport Full Name</label>
                            <input type="text" class="form-control" name="passport_full_name" value="{{ old('passport_full_name', $employee->passport_full_name) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Passport Number</label>
                            <input type="text" class="form-control" name="passport_number" value="{{ old('passport_number', $employee->passport_number) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Issued From</label>
                            <input type="text" class="form-control" name="passport_issued_from" value="{{ old('passport_issued_from', $employee->passport_issued_from) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Passport Issued Date</label>
                            <input type="date" class="form-control" name="passport_issued_date" value="{{ old('passport_issued_date', $employee->passport_issued_date) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Passport Expiry Date</label>
                            <input type="date" class="form-control" name="passport_expiry_date" value="{{ old('passport_expiry_date', $employee->passport_expiry_date) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Place of Birth</label>
                            <input type="text" class="form-control" name="place_of_birth" value="{{ old('place_of_birth', $employee->place_of_birth) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Father's Name</label>
                            <input type="text" class="form-control" name="father_name" value="{{ old('father_name', $employee->father_name) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Mother's Name</label>
                            <input type="text" class="form-control" name="mother_name" value="{{ old('mother_name', $employee->mother_name) }}">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" rows="2">{{ old('address', $employee->address) }}</textarea>
                        </div>
                        
                        @php
                            $passportDocs = [
                                'passport_1st_page' => 'Passport 1st Page',
                                'passport_2nd_page' => 'Passport 2nd Page',
                                'passport_outer_page' => 'Outer Page',
                                'passport_id_page' => 'ID Page'
                            ];
                        @endphp
                        
                        @foreach($passportDocs as $field => $label)
                        <div class="col-md-3 mb-3">
                            <label class="form-label">{{ $label }}</label>
                            <input type="file" class="form-control mb-1" name="{{ $field }}" accept=".pdf,.jpg,.jpeg,.png">
                            @if($employee->$field)
                                <small class="text-success"><i class="fe fe-check-circle"></i> File uploaded</small>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Visa & Labor -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Visa Details</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Visa Number</label>
                            <input type="text" class="form-control" name="visa_number" value="{{ old('visa_number', $employee->visa_number) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Issued Date</label>
                            <input type="date" class="form-control" name="visa_issued_date" value="{{ old('visa_issued_date', $employee->visa_issued_date) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Expiry Date</label>
                            <input type="date" class="form-control" name="visa_expiry_date" value="{{ old('visa_expiry_date', $employee->visa_expiry_date) }}">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Attach Visa Page</label>
                            <input type="file" class="form-control mb-1" name="visa_page" accept=".pdf,.jpg,.jpeg,.png">
                            @if($employee->visa_page)
                                <small class="text-success"><i class="fe fe-check-circle"></i> File uploaded</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Labor Details</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Labor Number</label>
                            <input type="text" class="form-control" name="labor_number" value="{{ old('labor_number', $employee->labor_number) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Issued Date</label>
                            <input type="date" class="form-control" name="labor_issued_date" value="{{ old('labor_issued_date', $employee->labor_issued_date) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Expiry Date</label>
                            <input type="date" class="form-control" name="labor_expiry_date" value="{{ old('labor_expiry_date', $employee->labor_expiry_date) }}">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Attach Labor Card</label>
                            <input type="file" class="form-control mb-1" name="labor_card" accept=".pdf,.jpg,.jpeg,.png">
                            @if($employee->labor_card)
                                <small class="text-success"><i class="fe fe-check-circle"></i> File uploaded</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- EID Details -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">EID Details</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">EID Number</label>
                            <input type="text" class="form-control" name="eid_number" value="{{ old('eid_number', $employee->eid_number) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Issued Date</label>
                            <input type="date" class="form-control" name="eid_issued_date" value="{{ old('eid_issued_date', $employee->eid_issued_date) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Expiry Date</label>
                            <input type="date" class="form-control" name="eid_expiry_date" value="{{ old('eid_expiry_date', $employee->eid_expiry_date) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Attach 1st Page</label>
                            <input type="file" class="form-control mb-1" name="eid_1st_page" accept=".pdf,.jpg,.jpeg,.png">
                            @if($employee->eid_1st_page)
                                <small class="text-success"><i class="fe fe-check-circle"></i> File uploaded</small>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Attach 2nd Page</label>
                            <input type="file" class="form-control mb-1" name="eid_2nd_page" accept=".pdf,.jpg,.jpeg,.png">
                            @if($employee->eid_2nd_page)
                                <small class="text-success"><i class="fe fe-check-circle"></i> File uploaded</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Other Details -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Other Details</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Dependents (Yes/No)</label>
                            <select class="form-control" name="dependents">
                                <option value="No" {{ old('dependents', $employee->dependents) == 'No' ? 'selected' : '' }}>No</option>
                                <option value="Yes" {{ old('dependents', $employee->dependents) == 'Yes' ? 'selected' : '' }}>Yes</option>
                            </select>
                        </div>
                        @php
                            $otherDocs = [
                                'educational_1st_page' => 'Education 1st Page',
                                'educational_2nd_page' => 'Education 2nd Page',
                                'home_country_id_proof' => 'Home Country ID Proof'
                            ];
                        @endphp
                        @foreach($otherDocs as $field => $label)
                        <div class="col-md-3 mb-3">
                            <label class="form-label">{{ $label }}</label>
                            <input type="file" class="form-control mb-1" name="{{ $field }}" accept=".pdf,.jpg,.jpeg,.png">
                            @if($employee->$field)
                                <small class="text-success"><i class="fe fe-check-circle"></i> File uploaded</small>
                            @endif
                        </div>
                        @endforeach
                        
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Company Mobile Number</label>
                            <input type="text" class="form-control" name="company_mobile_number" value="{{ old('company_mobile_number', $employee->company_mobile_number) }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Personal Number</label>
                            <input type="text" class="form-control" name="personal_number" value="{{ old('personal_number', $employee->personal_number) }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Other Number</label>
                            <input type="text" class="form-control" name="other_number" value="{{ old('other_number', $employee->other_number) }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Home Country Number</label>
                            <input type="text" class="form-control" name="home_country_number" value="{{ old('home_country_number', $employee->home_country_number) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Company Email</label>
                            <input type="email" class="form-control" name="company_email" value="{{ old('company_email', $employee->company_email) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Personal Email</label>
                            <input type="email" class="form-control" name="personal_email" value="{{ old('personal_email', $employee->personal_email) }}">
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('employees.index') }}" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Employee</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
