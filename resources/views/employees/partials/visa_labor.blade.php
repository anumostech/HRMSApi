<div class="row">

    <!-- Visa -->
    <div class="col-md-6">
        <h5 class="mb-3">Visa Details</h5>
        <div class="row">
            <div class="col-md-12">
                <!-- Visa Number -->
                <input type="text"
                    class="form-control mb-2 @error('visa_number') is-invalid @enderror"
                    name="visa_number"
                    value="{{ old('visa_number', $employee->visa_number ?? '') }}"
                    placeholder="Enter Visa Number"
                    pattern="[A-Za-z0-9\-]{3,20}"
                    maxlength="20"
                    title="3–20 characters, letters, numbers or hyphens only">
                @error('visa_number') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
            <!-- Issued Date -->
            <div class="col-md-12 mb-2">

                <div class="position-relative">
                <input type="text"
                    class="form-control datepicker pe-5 @error('visa_issued_date') is-invalid @enderror"
                    name="visa_issued_date"
                    value="{{ old('visa_issued_date', isset($employee->visa_issued_date) ? \Carbon\Carbon::parse($employee->visa_issued_date)->format('d-m-Y') : '') }}"
                    placeholder="Select visa issued date">
                <span class="date-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h1V.5a.5.5 0 0 1 .5-.5zM2 5v9h12V5H2z" />
                    </svg>
                </span>
                </div>
                @error('visa_issued_date') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>

            <!-- Expiry Date -->
            <div class="col-md-12 mb-2">
                <div class="position-relative">
                <input type="text"
                    class="form-control datepicker pe-5 @error('visa_expiry_date') is-invalid @enderror"
                    name="visa_expiry_date"
                    value="{{ old('visa_expiry_date', isset($employee->visa_expiry_date) ? \Carbon\Carbon::parse($employee->visa_expiry_date)->format('d-m-Y') : '') }}"
                    placeholder="Select visa expiry date">
                <span class="date-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h1V.5a.5.5 0 0 1 .5-.5zM2 5v9h12V5H2z" />
                    </svg>
                </span>
                </div>
                @error('visa_expiry_date') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>

        
            <div class="col-md-12 mb-3">
                <label class="form-label">Attach Visa Page</label>
                <input type="file" class="form-control mb-1 document-upload" data-field="visa_page" accept=".pdf,.jpg,.jpeg,.png">
                <input type="hidden" name="visa_page" value="{{ isset($employee) ? $employee->visa_page : '' }}">
                @if(isset($employee) && $employee->visa_page)
                <small class="text-success"><i class="fe fe-check-circle"></i> File uploaded</small>
                @endif
            </div>

        </div>
    </div>

    <!-- Labor -->
    <div class="col-md-6">
        <h5 class="mb-3">Labor Details</h5>
        <div class="row">

            <div class="col-md-12">

                <!-- Labor Number -->
                <input type="text"
                    class="form-control mb-2 @error('labor_number') is-invalid @enderror"
                    name="labor_number"
                    value="{{ old('labor_number', $employee->labor_number ?? '') }}"
                    placeholder="Enter Labor Number"
                    pattern="[A-Za-z0-9\-]{3,20}"
                    maxlength="20"
                    title="3–20 characters, letters, numbers or hyphens only">
                @error('labor_number') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-12 mb-2">

                <!-- Issued Date -->
                <div class="position-relative">
                <input type="text"
                    class="form-control datepicker pe-5 @error('labor_issued_date') is-invalid @enderror"
                    name="labor_issued_date"
                    value="{{ old('labor_issued_date', isset($employee->labor_issued_date) ? \Carbon\Carbon::parse($employee->labor_issued_date)->format('d-m-Y') : '') }}"
                    placeholder="Select labor issued date">
                <span class="date-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h1V.5a.5.5 0 0 1 .5-.5zM2 5v9h12V5H2z" />
                    </svg>
                </span>
                </div>
                @error('labor_issued_date') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-12 mb-2">
                <!-- Expiry Date -->
                <div class="position-relative">
                <input type="text"
                    class="form-control datepicker pe-5 @error('labor_expiry_date') is-invalid @enderror"
                    name="labor_expiry_date"
                    value="{{ old('labor_expiry_date', isset($employee->labor_expiry_date) ? \Carbon\Carbon::parse($employee->labor_expiry_date)->format('d-m-Y') : '') }}"
                    placeholder="Select labor expiry date">
                <span class="date-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h1V.5a.5.5 0 0 1 .5-.5zM2 5v9h12V5H2z" />
                    </svg>
                </span>
                </div>
                @error('labor_expiry_date') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
        
            <div class="col-md-12 mb-3">
                <label class="form-label">Attach Labor Card</label>
                <input type="file" class="form-control mb-1 document-upload" data-field="labor_card" accept=".pdf,.jpg,.jpeg,.png">
                <input type="hidden" name="labor_card" value="{{ isset($employee) ? $employee->labor_card : '' }}">
                @if(isset($employee) && $employee->labor_card)
                <small class="text-success"><i class="fe fe-check-circle"></i> File uploaded</small>
                @endif
            </div>

        </div>
    </div>

</div>