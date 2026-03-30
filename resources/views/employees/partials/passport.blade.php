<div class="row">

    <!-- Passport Full Name -->
    <div class="col-md-4 mb-3">
        <label class="form-label">Passport Full Name</label>
        <input type="text" class="form-control @error('passport_full_name') is-invalid @enderror"
            name="passport_full_name" value="{{ old('passport_full_name', $employee->passport_full_name ?? '') }}"
            placeholder="Enter name as per passport" pattern="[A-Za-z\s]+" title="Only letters and spaces allowed">
        @error('passport_full_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <!-- Passport Number -->
    <div class="col-md-4 mb-3">
        <label class="form-label">Passport Number</label>
        <input type="text" class="form-control @error('passport_number') is-invalid @enderror" name="passport_number"
            value="{{ old('passport_number', $employee->passport_number ?? '') }}" placeholder="Enter passport number"
            pattern="[A-Za-z0-9]+" title="Only letters and numbers allowed">
        @error('passport_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <!-- Issued From -->
    <div class="col-md-4 mb-3">
        <label class="form-label">Issued From</label>
        <input type="text" class="form-control @error('passport_issued_from') is-invalid @enderror"
            name="passport_issued_from" value="{{ old('passport_issued_from', $employee->passport_issued_from ?? '') }}"
            placeholder="Enter issuing country/city">
        @error('passport_issued_from') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <!-- Issued Date -->
    <div class="col-md-4 mb-3">
        <label class="form-label">Issued Date</label>
        <div class="position-relative">
            <input type="text" class="form-control datepicker @error('passport_issued_date') is-invalid @enderror"
                name="passport_issued_date"
                value="{{ old('passport_issued_date', isset($employee->passport_issued_date) ? \Carbon\Carbon::parse($employee->passport_issued_date)->format('d-m-Y') : '') }}"
                placeholder="Select issued date">
            <span class="date-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path
                        d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h1V.5a.5.5 0 0 1 .5-.5zM2 5v9h12V5H2z" />
                </svg>
            </span>
        </div>
        @error('passport_issued_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <!-- Expiry Date -->
    <div class="col-md-4 mb-3">
        <label class="form-label">Expiry Date</label>
        <div class="position-relative">
            <input type="text" class="form-control datepicker @error('passport_expiry_date') is-invalid @enderror"
                name="passport_expiry_date"
                value="{{ old('passport_expiry_date', isset($employee->passport_expiry_date) ? \Carbon\Carbon::parse($employee->passport_expiry_date)->format('d-m-Y') : '') }}"
                placeholder="Select expiry date">
            <span class="date-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path
                        d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h1V.5a.5.5 0 0 1 .5-.5zM2 5v9h12V5H2z" />
                </svg>
            </span>
        </div>
        @error('passport_expiry_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <!-- Place of Birth -->
    <div class="col-md-4 mb-3">
        <label class="form-label">Place of Birth</label>
        <input type="text" class="form-control @error('place_of_birth') is-invalid @enderror" name="place_of_birth"
            value="{{ old('place_of_birth', $employee->place_of_birth ?? '') }}" placeholder="Enter place of birth">
        @error('place_of_birth') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <!-- Father's Name -->
    <div class="col-md-4 mb-3">
        <label class="form-label">Father's Name</label>
        <input type="text" class="form-control @error('father_name') is-invalid @enderror" name="father_name"
            value="{{ old('father_name', $employee->father_name ?? '') }}" placeholder="Enter father's name"
            pattern="[A-Za-z\s]+" title="Only letters and spaces allowed">
        @error('father_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <!-- Mother's Name -->
    <div class="col-md-4 mb-3">
        <label class="form-label">Mother's Name</label>
        <input type="text" class="form-control @error('mother_name') is-invalid @enderror" name="mother_name"
            value="{{ old('mother_name', $employee->mother_name ?? '') }}" placeholder="Enter mother's name"
            pattern="[A-Za-z\s]+" title="Only letters and spaces allowed">
        @error('mother_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <!-- Address -->
    <div class="col-md-12 mb-3">
        <label class="form-label">Address</label>
        <textarea class="form-control @error('address') is-invalid @enderror" name="address" rows="2"
            placeholder="Enter full address">{{ old('address', $employee->address ?? '') }}</textarea>
        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
            <input type="file" class="form-control mb-1 document-upload" data-field="{{ $field }}"
                accept=".pdf,.jpg,.jpeg,.png">
            <input type="hidden" name="{{ $field }}" value="{{ isset($employee) ? $employee->$field : '' }}">
            @if(isset($employee) && $employee->$field)
                <small class="text-success"><i class="fe fe-check-circle"></i> File uploaded</small>
            @endif
        </div>
    @endforeach

</div>