@extends('layouts.app')

@section('title', 'Add Employee')

@section('styles')
    <style>
        #wizardTabs .nav-link {
            cursor: pointer;
        }

        .wizard-step {
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateX(10px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
@endsection

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
        <div class="card">
            <div class="card-body">

                <!-- Step Tabs -->
                <ul class="nav nav-pills mb-4" id="wizardTabs">
                    <li class="nav-item"><a class="nav-link active" data-step="1">Basic</a></li>
                    <li class="nav-item"><a class="nav-link" data-step="2">Passport</a></li>
                    <li class="nav-item"><a class="nav-link" data-step="3">Visa & Labor</a></li>
                    <li class="nav-item"><a class="nav-link" data-step="4">EID</a></li>
                    <li class="nav-item"><a class="nav-link" data-step="5">Other</a></li>
                </ul>

                <!-- Step Content -->
                <div class="wizard-step" id="step-1">
                    @include('employees.partials.basic')
                </div>

                <div class="wizard-step d-none" id="step-2">
                    @include('employees.partials.passport')
                </div>

                <div class="wizard-step d-none" id="step-3">
                    @include('employees.partials.visa_labor')
                </div>

                <div class="wizard-step d-none" id="step-4">
                    @include('employees.partials.eid')
                </div>

                <div class="wizard-step d-none" id="step-5">
                    @include('employees.partials.other')
                </div>

            </div>

            <!-- Buttons -->
            <div class="card-footer d-flex justify-content-between">
                <button type="button" class="btn btn-light" id="prevBtn">
                    << Previous</button>
                        <button type="button" class="btn btn-primary" id="nextBtn">Next >></button>
                        <button type="submit" class="btn btn-success d-none" id="submitBtn">Save Employee</button>
            </div>
        </div>

    </form>
    <div class="modal fade" id="createCompanyModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Add Company Name</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="text" id="newCompanyName" class="form-control" placeholder="Enter company name">
                </div>

                <div class="modal-footer">
                    <button class="btn btn-default" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" id="saveCompanyBtn">Create</button>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="createDepartmentModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Add Department Name</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="text" id="newDepartmentName" class="form-control" placeholder="Enter department name">
                </div>

                <div class="modal-footer">
                    <button class="btn btn-default" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" id="saveDepartmentBtn">Create</button>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        let currentStep = 1;
        const totalSteps = 5;

        function showStep(step) {
            document.querySelectorAll('.wizard-step').forEach(el => el.classList.add('d-none'));
            document.getElementById('step-' + step).classList.remove('d-none');

            document.querySelectorAll('#wizardTabs .nav-link').forEach(el => el.classList.remove('active'));
            document.querySelector(`[data-step="${step}"]`).classList.add('active');

            // Buttons
            document.getElementById('prevBtn').style.display = step === 1 ? 'none' : 'inline-block';
            document.getElementById('nextBtn').style.display = step === totalSteps ? 'none' : 'inline-block';
            document.getElementById('submitBtn').classList.toggle('d-none', step !== totalSteps);
        }

        function validateCurrentStep() {
            let stepEl = document.getElementById('step-' + currentStep);
            if (!stepEl) return true;
            
            let inputs = stepEl.querySelectorAll('input, select, textarea');
            for (let i = 0; i < inputs.length; i++) {
                if (!inputs[i].checkValidity()) {
                    inputs[i].reportValidity();
                    return false;
                }
            }
            return true;
        }

        document.getElementById('nextBtn').addEventListener('click', () => {
            if (!validateCurrentStep()) return;
            if (currentStep < totalSteps) {
                currentStep++;
                showStep(currentStep);
            }
        });

        document.getElementById('prevBtn').addEventListener('click', () => {
            if (!validateCurrentStep()) return;
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        });

        // Click on tabs
        document.querySelectorAll('#wizardTabs .nav-link').forEach(tab => {
            tab.addEventListener('click', function () {
                let targetStep = parseInt(this.dataset.step);
                if (targetStep !== currentStep) {
                    if (!validateCurrentStep()) return;
                    currentStep = targetStep;
                    showStep(currentStep);
                }
            });
        });

        // Init
        showStep(currentStep);
    </script>
    <script>
        $(document).on('click', '.addSpecialDay', function () {
            let html = `
                <div class="row special-day-row mb-2">

                    <div class="col-md-5">
                        <input type="text" name="special_days_name[]" class="form-control" placeholder="Special Day Name">
                    </div>

                    <div class="col-md-5">
                        <input type="text" name="special_days_date[]" class="form-control datepicker" placeholder="Select Date">
                        <span class="date-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h1V.5a.5.5 0 0 1 .5-.5zM2 5v9h12V5H2z" />
                            </svg>
                        </span>
                    </div>

                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger removeSpecialDay">
                            -
                        </button>
                    </div>

                </div>`;

            $('#specialDaysWrapper').append(html);

            // Reinitialize datepicker for new fields
            $('.datepicker').datepicker({
                format: "dd-mm-yyyy",
                autoclose: true
            });
        });


        $(document).on('click', '.removeSpecialDay', function () {

            $(this).closest('.special-day-row').remove();

        });

        $(document).on('change', '.document-upload', function () {

            let file = this.files[0];
            let field = $(this).data('field');

            let formData = new FormData();
            formData.append('file', file);
            formData.append('field', field);
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

            axios.post('{{ route("documents.uploadTempDocument") }}', formData)
                .then(function (response) {
                    if (response.data.success == true) {
                        $("input[name='" + field + "']").val(response.data.path);

                        // Swal.fire({
                        //     toast: true,
                        //     position: 'top-end',
                        //     icon: 'success',
                        //     title: "Uploaded successfully",
                        //     showConfirmButton: false,
                        //     timer: 1500
                        // });
                    }

                })
                .catch(function () {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: field + "uploading failed",
                        showConfirmButton: false,
                        timer: 1500
                    });
                });

        });

        $('#companySelect').on('change', function () {

            if ($(this).val() === '__new__') {

                let modal = new bootstrap.Modal(document.getElementById('createCompanyModal'));
                modal.show();

            }

        });

        $('#saveCompanyBtn').click(function () {

            let companyName = $('#newCompanyName').val();

            if (!companyName) {
                alert("Company name is required");
                return;
            }

            $.ajax({
                url: "{{ route('companies.store') }}",
                type: "POST",
                data: {
                    name: companyName,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {

                    let newOption = `<option value="${response.company.id}">
                                    ${response.company.name}
                                 </option>`;

                    $('#addCompanyOption').before(newOption);

                    $('#companySelect').val(response.company.id).trigger('change');

                    $('#newCompanyName').val('');

                    bootstrap.Modal.getInstance(document.getElementById('createCompanyModal')).hide();

                }
            });

        });

        $('#createCompanyModal').on('hidden.bs.modal', function () {

            if ($('#companySelect').val() === '__new__') {
                $('#companySelect').val('');
            }

        });

        $('#departmentSelect').on('change', function () {

            if ($(this).val() === '__new_department__') {

                let modal = new bootstrap.Modal(document.getElementById('createDepartmentModal'));
                modal.show();
            }

        });

        $('#saveDepartmentBtn').click(function () {

            let departmentName = $('#newDepartmentName').val();

            if (!departmentName) {
                alert("Department name is required");
                return;
            }

            $.ajax({
                url: "{{ route('departments.store') }}",
                type: "POST",
                data: {
                    name: departmentName,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {

                    let newOption = `<option value="${response.department.id}">
                                    ${response.department.name}
                                 </option>`;

                    $('#addDepartmentOption').before(newOption);

                    $('#departmentSelect').val(response.department.id).trigger('change');

                    $('#newDepartmentName').val('');

                    bootstrap.Modal.getInstance(document.getElementById('createDepartmentModal')).hide();

                }
            });

        });

        $('#createDepartmentModal').on('hidden.bs.modal', function () {

            if ($('#departmentSelect').val() === '__new_department__') {
                $('#departmentSelect').val('');
            }

        });
    </script>
    <script>
        document.querySelector('form').addEventListener('submit', function (e) {
            function checkDates(issue, expiry, label) {
                let i = new Date(issue.value);
                let ex = new Date(expiry.value);

                if (issue.value && expiry.value && ex <= i) {
                    alert(label + ' expiry must be after issued date');
                    e.preventDefault();
                }
            }

            checkDates(
                document.querySelector('[name="visa_issued_date"]'),
                document.querySelector('[name="visa_expiry_date"]'),
                'Visa'
            );

            checkDates(
                document.querySelector('[name="labor_issued_date"]'),
                document.querySelector('[name="labor_expiry_date"]'),
                'Labor'
            );
        });
    </script>
    <script>
        document.querySelector('form').addEventListener('submit', function (e) {

            function validate(issueName, expiryName, label) {
                let issue = document.querySelector(`[name="${issueName}"]`);
                let expiry = document.querySelector(`[name="${expiryName}"]`);

                if (issue.value && expiry.value) {
                    let i = new Date(issue.value);
                    let ex = new Date(expiry.value);

                    if (ex <= i) {
                        alert(label + ' expiry must be after issued date');
                        e.preventDefault();
                    }
                }
            }

            validate('visa_issued_date', 'visa_expiry_date', 'Visa');
            validate('labor_issued_date', 'labor_expiry_date', 'Labor');

        });
    </script>
    <script>
        $('#organizationSelect').on('change', function() {
            let organizationId = $(this).val();
            let companySelect = $('#companySelect');
            let addCompanyOption = $('#addCompanyOption');
            let currentCompanyId = "{{ old('company_id', $employee->company_id ?? '') }}";
            
            companySelect.find('option').not('[value=""]').not('#addCompanyOption').remove();
            
            if (organizationId) {
                $.ajax({
                    url: '/companies/by-organization/' + organizationId,
                    type: 'GET',
                    success: function(response) {
                        let hasSelected = false;
                        response.forEach(function(company) {
                            let isSelected = (currentCompanyId == company.id) ? 'selected' : '';
                            if(isSelected) hasSelected = true;
                            let option = `<option value="${company.id}" ${isSelected}>${company.company_name}</option>`;
                            addCompanyOption.before(option);
                        });
                        if(!hasSelected && companySelect.val() !== '__new__') {
                            companySelect.val('');
                        }
                    }
                });
            }
        });

        $(document).ready(function() {
            if ($('#organizationSelect').val()) {
                $('#organizationSelect').trigger('change');
            }
        });
    </script>
@endsection