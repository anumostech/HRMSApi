<div class="row">

    <!-- EID Number -->
    <div class="col-md-4 mb-3">
        <label class="form-label">EID Number</label>
        <input type="text"
            class="form-control @error('eid_number') is-invalid @enderror"
            name="eid_number"
            value="{{ old('eid_number', $employee->eid_number ?? '') }}"
            placeholder="Enter EID number"
            pattern="[A-Za-z0-9\-]+"
            title="Only letters, numbers and hyphens allowed">
        @error('eid_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <!-- Issued Date -->
    <div class="col-md-4 mb-3">
        <label class="form-label">Issued Date</label>
                            <div class="position-relative">
        <input type="text"
            class="form-control datepicker @error('eid_issued_date') is-invalid @enderror"
            name="eid_issued_date"
            value="{{ old('eid_issued_date', isset($employee->eid_issued_date) ? \Carbon\Carbon::parse($employee->eid_issued_date)->format('d-m-Y') : '') }}"
            placeholder="Select issued date">
        <span class="date-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h1V.5a.5.5 0 0 1 .5-.5zM2 5v9h12V5H2z" />
            </svg>
        </span>
                            </div>
        @error('eid_issued_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <!-- Expiry Date -->
    <div class="col-md-4 mb-3">
        <label class="form-label">Expiry Date</label>
                            <div class="position-relative">
        <input type="text"
            class="form-control datepicker @error('eid_expiry_date') is-invalid @enderror"
            name="eid_expiry_date"
            value="{{ old('eid_expiry_date', isset($employee->eid_expiry_date) ? \Carbon\Carbon::parse($employee->eid_expiry_date)->format('d-m-Y') : '') }}"
            placeholder="Select expiry date">
        <span class="date-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h1V.5a.5.5 0 0 1 .5-.5zM2 5v9h12V5H2z" />
            </svg>
        </span>
                            </div>
        @error('eid_expiry_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <!-- Attach 1st Page -->
    <div class="col-md-6 mb-3">
        <label class="form-label">Attach 1st Page</label>
        <input type="file" class="form-control mb-1 document-upload" data-field="eid_1st_page" accept=".pdf,.jpg,.jpeg,.png">
        <input type="hidden" name="eid_1st_page" value="{{ isset($employee) ? $employee->eid_1st_page : '' }}">
        @if(isset($employee) && $employee->eid_1st_page)
        <small class="text-success"><i class="fe fe-check-circle"></i> File uploaded</small>
        @endif
    </div>
    <!-- Attach 2nd Page -->
    <div class="col-md-6 mb-3">
        <label class="form-label">Attach 2nd Page</label>
        <input type="file" class="form-control mb-1 document-upload" data-field="eid_2nd_page" accept=".pdf,.jpg,.jpeg,.png">
        <input type="hidden" name="eid_2nd_page" value="{{ isset($employee) ? $employee->eid_2nd_page : '' }}">
        @if(isset($employee) && $employee->eid_2nd_page)
        <small class="text-success"><i class="fe fe-check-circle"></i> File uploaded</small>
        @endif
    </div>

</div>