@extends('layouts.app')

@section('title', 'Employee Details')

@section('content')
<div class="page-header">
    <h1 class="page-title">Employee Details</h1>
    <div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Employees</a></li>
            <li class="breadcrumb-item active" aria-current="page">Details</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-xl-4 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Basic Info</h3>
                <div class="card-options">
                    <span class="badge bg-{{ $employee->status == 'active' ? 'success' : 'danger' }}">{{ ucfirst($employee->status) }}</span>
                </div>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <h4 class="mb-0 fw-bold">{{ $employee->name }}</h4>
                    <p class="text-muted">{{ $employee->designation }} | {{ $employee->department }}</p>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Company <span class="fw-semibold">{{ $employee->company->name ?? 'N/A' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Joining Date <span class="fw-semibold">{{ $employee->joining_date ?? 'N/A' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        DOB <span class="fw-semibold">{{ $employee->dob ?? 'N/A' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Gender <span class="fw-semibold">{{ $employee->gender ?? 'N/A' }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Contact Details</h3>
            </div>
            <div class="card-body">
                <p><strong>Company Email:</strong> <br><span class="text-primary">{{ $employee->company_email ?? 'N/A' }}</span></p>
                <p><strong>Personal Email:</strong> <br><span>{{ $employee->personal_email ?? 'N/A' }}</span></p>
                <p><strong>Company Mobile:</strong> <br><span>{{ $employee->company_mobile_number ?? 'N/A' }}</span></p>
                <p><strong>Personal Mobile:</strong> <br><span>{{ $employee->personal_number ?? 'N/A' }}</span></p>
                <p><strong>Home Country:</strong> <br><span>{{ $employee->home_country_number ?? 'N/A' }}</span></p>
            </div>
        </div>
    </div>

    <div class="col-xl-8 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Documents Management</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    @php
                        $docs = [
                            'Passport 1st Page' => 'passport_1st_page',
                            'Passport 2nd Page' => 'passport_2nd_page',
                            'Passport Outer' => 'passport_outer_page',
                            'Passport ID' => 'passport_id_page',
                            'Visa Page' => 'visa_page',
                            'Labor Card' => 'labor_card',
                            'EID 1st Page' => 'eid_1st_page',
                            'EID 2nd Page' => 'eid_2nd_page',
                            'Educational 1' => 'educational_1st_page',
                            'Educational 2' => 'educational_2nd_page',
                            'Home ID' => 'home_country_id_proof',
                        ];
                    @endphp

                    @foreach($docs as $label => $field)
                        <div class="col-md-4 mb-4">
                            <div class="card border p-3 text-center h-100">
                                <div class="mb-2">
                                    @if($employee->$field)
                                        <i class="fe fe-file-text fs-40 text-primary"></i>
                                    @else
                                        <i class="fe fe-file-minus fs-40 text-muted"></i>
                                    @endif
                                </div>
                                <h6 class="fw-bold">{{ $label }}</h6>
                                @if($employee->$field)
                                    <div class="mt-auto">
                                        <button type="button" class="btn btn-sm btn-info btn-block mb-1" onclick="previewDocument('{{ asset('storage/' . $employee->$field) }}', '{{ $label }}')">
                                            <i class="fe fe-eye"></i> Preview
                                        </button>
                                        <a href="{{ asset('storage/' . $employee->$field) }}" download class="btn btn-sm btn-outline-primary btn-block">
                                            <i class="fe fe-download"></i> Download
                                        </a>
                                    </div>
                                @else
                                    <span class="text-muted mt-auto small">Not Uploaded</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Miscellaneous Details</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Passport Full Name:</strong> {{ $employee->passport_full_name ?? 'N/A' }}</p>
                        <p><strong>Passport Number:</strong> {{ $employee->passport_number ?? 'N/A' }}</p>
                        <p><strong>Father Name:</strong> {{ $employee->father_name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Visa Number:</strong> {{ $employee->visa_number ?? 'N/A' }}</p>
                        <p><strong>Labor Number:</strong> {{ $employee->labor_number ?? 'N/A' }}</p>
                        <p><strong>EID Number:</strong> {{ $employee->eid_number ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">Document Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div id="previewContent" class="text-center" style="min-height: 500px;">
                    <!-- Content will be injected here -->
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function previewDocument(url, title) {
        const modal = new bootstrap.Modal(document.getElementById('previewModal'));
        const content = document.getElementById('previewContent');
        const modalTitle = document.getElementById('previewModalLabel');
        
        modalTitle.innerText = title + ' Preview';
        content.innerHTML = '';
        
        const extension = url.split('.').pop().toLowerCase();
        
        if (extension === 'pdf') {
            content.innerHTML = `<iframe src="${url}" style="width:100%; height:80vh; border:none;"></iframe>`;
        } else if (['jpg', 'jpeg', 'png'].includes(extension)) {
            content.innerHTML = `<img src="${url}" class="img-fluid p-3" style="max-height: 80vh;">`;
        } else {
            content.innerHTML = `<div class="p-5">
                <i class="fe fe-alert-circle text-warning fs-50"></i>
                <h5>Preview not available for this file type.</h5>
                <a href="${url}" class="btn btn-primary mt-3" target="_blank">Open in new tab</a>
            </div>`;
        }
        
        modal.show();
    }
</script>
@endsection
