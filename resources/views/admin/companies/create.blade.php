@extends('layouts.app')

@section('title', 'Add Company')

@section('content')
<div class="page-header mt-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Add Company</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('companies.index', ['organisation_id' => $organisation_id]) }}">Companies</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add</li>
            </ol>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-8">
        <div class="card shadow-sm border-0">
            <div class="card-header border-bottom">
                <h3 class="card-title">Company Details</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('companies.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="organisation_id">Parent Organization <span class="text-danger">*</span></label>
                            <select name="organisation_id" id="organisation_id" class="form-select select2" required>
                                <option value="">Select Organization</option>
                                @foreach($organisations as $org)
                                    <option value="{{ $org->id }}" selected="selected">
                                        {{ $org->org_name }}
                                    </option>
                                @endforeach
                            </select>
                            @if($organisations->isEmpty())
                                <small class="text-danger">No organizations found with "Multiple Companies" selected.</small>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="company_name">Company Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="company_name" value="{{ old('company_name') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="phone">Phone</label>
                            <input type="text" class="form-control" name="phone" value="{{ old('phone') }}">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="address">Address</label>
                            <textarea class="form-control" name="address" rows="3">{{ old('address') }}</textarea>
                        </div>

                        <div class="col-md-12 mt-4 text-end">
                            <button type="submit" class="btn btn-primary px-5" {{ $organisations->isEmpty() ? 'disabled' : '' }}>Save Company</button>
                            <a href="{{ route('companies.index', ['organisation_id' => $organisation_id]) }}" class="btn btn-light ms-2 px-5">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Logo Upload with Preview Panel -->
    <div class="col-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header border-bottom">
                <h3 class="card-title">Company Logo</h3>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <div id="logo-preview-container" class="mb-4">
                        <div id="logo-placeholder" class="avatar avatar-xxl rounded-circle bg-light text-muted d-flex align-items-center justify-content-center mx-auto" style="width:120px; height:120px;">
                            <i class="fe fe-image fs-30"></i>
                        </div>
                        <img id="logo-preview" src="#" alt="Logo Preview" class="avatar avatar-xxl rounded-circle shadow-sm mx-auto d-none" style="width:120px; height:120px; object-fit: cover;">
                    </div>
                    <div>
                        <!-- File input added via JS -->
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill mb-2" id="btn-upload">
                        <i class="fe fe-upload me-2"></i>Choose Logo
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm rounded-pill mb-2 d-none" id="btn-remove-logo">
                        <i class="fe fe-trash-2 me-2"></i>Remove
                    </button>
                    <p class="text-muted small">Max size: 2MB. JPG, PNG, SVG</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if ($.fn.select2) {
            $('.select2').select2({
                placeholder: 'Select Organization',
                width: '100%'
            });
        }

        const logoInput = document.createElement('input');
        logoInput.type = 'file';
        logoInput.name = 'logo';
        logoInput.className = 'd-none';
        logoInput.accept = 'image/*';
        document.querySelector('form').appendChild(logoInput);

        const btnUpload = document.getElementById('btn-upload');
        const btnRemove = document.getElementById('btn-remove-logo');
        const logoPreview = document.getElementById('logo-preview');
        const logoPlaceholder = document.getElementById('logo-placeholder');

        btnUpload.addEventListener('click', () => logoInput.click());

        logoInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    logoPreview.src = e.target.result;
                    logoPreview.classList.remove('d-none');
                    logoPlaceholder.classList.add('d-none');
                    btnRemove.classList.remove('d-none');
                }
                reader.readAsDataURL(this.files[0]);
            }
        });

        btnRemove.addEventListener('click', () => {
            logoInput.value = '';
            logoPreview.src = '#';
            logoPreview.classList.add('d-none');
            logoPlaceholder.classList.remove('d-none');
            btnRemove.classList.add('d-none');
        });
    });
</script>
@endsection
