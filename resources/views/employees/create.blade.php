@extends('layouts.app')

@section('title', 'Add Employee')

@section('content')
<div class="page-header">
    <h1 class="page-title">Add Employee</h1>
    <div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Employees</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add Employee</li>
        </ol>
    </div>
</div>

<form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
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
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Designation</label>
                            <input type="text" class="form-control" name="designation" value="{{ old('designation') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Department</label>
                            <input type="text" class="form-control" name="department" value="{{ old('department') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Company <span class="text-danger">*</span></label>
                            <select class="form-control @error('company_id') is-invalid @enderror" name="company_id" required>
                                <option value="">Select Company</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                                @endforeach
                            </select>
                            @error('company_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" name="dob" value="{{ old('dob') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Date of Joining</label>
                            <input type="date" class="form-control" name="joining_date" value="{{ old('joining_date') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Gender</label>
                            <select class="form-control" name="gender">
                                <option value="">Select Gender</option>
                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Special Days (Birthdays, etc.)</label>
                            <input type="text" class="form-control" name="special_days" value="{{ old('special_days') }}">
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
                            <input type="text" class="form-control" name="passport_full_name" value="{{ old('passport_full_name') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Passport Number</label>
                            <input type="text" class="form-control" name="passport_number" value="{{ old('passport_number') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Issued From</label>
                            <input type="text" class="form-control" name="passport_issued_from" value="{{ old('passport_issued_from') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Passport Issued Date</label>
                            <input type="date" class="form-control" name="passport_issued_date" value="{{ old('passport_issued_date') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Passport Expiry Date</label>
                            <input type="date" class="form-control" name="passport_expiry_date" value="{{ old('passport_expiry_date') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Place of Birth</label>
                            <input type="text" class="form-control" name="place_of_birth" value="{{ old('place_of_birth') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Father's Name</label>
                            <input type="text" class="form-control" name="father_name" value="{{ old('father_name') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Mother's Name</label>
                            <input type="text" class="form-control" name="mother_name" value="{{ old('mother_name') }}">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" rows="2">{{ old('address') }}</textarea>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Passport 1st Page</label>
                            <input type="file" class="form-control" name="passport_1st_page" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Passport 2nd Page</label>
                            <input type="file" class="form-control" name="passport_2nd_page" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Outer Page</label>
                            <input type="file" class="form-control" name="passport_outer_page" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">ID Page</label>
                            <input type="file" class="form-control" name="passport_id_page" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Visa Details -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Visa Details</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Visa Number</label>
                            <input type="text" class="form-control" name="visa_number" value="{{ old('visa_number') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Issued Date</label>
                            <input type="date" class="form-control" name="visa_issued_date" value="{{ old('visa_issued_date') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Expiry Date</label>
                            <input type="date" class="form-control" name="visa_expiry_date" value="{{ old('visa_expiry_date') }}">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Attach Visa Page</label>
                            <input type="file" class="form-control" name="visa_page" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Labor Details -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Labor Details</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Labor Number</label>
                            <input type="text" class="form-control" name="labor_number" value="{{ old('labor_number') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Issued Date</label>
                            <input type="date" class="form-control" name="labor_issued_date" value="{{ old('labor_issued_date') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Expiry Date</label>
                            <input type="date" class="form-control" name="labor_expiry_date" value="{{ old('labor_expiry_date') }}">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Attach Labor Card</label>
                            <input type="file" class="form-control" name="labor_card" accept=".pdf,.jpg,.jpeg,.png">
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
                            <input type="text" class="form-control" name="eid_number" value="{{ old('eid_number') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Issued Date</label>
                            <input type="date" class="form-control" name="eid_issued_date" value="{{ old('eid_issued_date') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Expiry Date</label>
                            <input type="date" class="form-control" name="eid_expiry_date" value="{{ old('eid_expiry_date') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Attach 1st Page</label>
                            <input type="file" class="form-control" name="eid_1st_page" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Attach 2nd Page</label>
                            <input type="file" class="form-control" name="eid_2nd_page" accept=".pdf,.jpg,.jpeg,.png">
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
                                <option value="No" {{ old('dependents') == 'No' ? 'selected' : '' }}>No</option>
                                <option value="Yes" {{ old('dependents') == 'Yes' ? 'selected' : '' }}>Yes</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Education 1st Page</label>
                            <input type="file" class="form-control" name="educational_1st_page" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Education 2nd Page</label>
                            <input type="file" class="form-control" name="educational_2nd_page" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Home Country ID Proof</label>
                            <input type="file" class="form-control" name="home_country_id_proof" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Company Mobile Number</label>
                            <input type="text" class="form-control" name="company_mobile_number" value="{{ old('company_mobile_number') }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Personal Number</label>
                            <input type="text" class="form-control" name="personal_number" value="{{ old('personal_number') }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Other Number</label>
                            <input type="text" class="form-control" name="other_number" value="{{ old('other_number') }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Home Country Number</label>
                            <input type="text" class="form-control" name="home_country_number" value="{{ old('home_country_number') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Company Email</label>
                            <input type="email" class="form-control" name="company_email" value="{{ old('company_email') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Personal Email</label>
                            <input type="email" class="form-control" name="personal_email" value="{{ old('personal_email') }}">
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('employees.index') }}" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Employee</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
