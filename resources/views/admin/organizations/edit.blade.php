@extends('layouts.app')

@section('title', 'Edit Organization')

@section('content')
<div class="page-header mt-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Edit Organization: {{ $organization->org_name }}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('organizations.index') }}">Organizations</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-8">
        <div class="card shadow-sm border-0">
            <div class="card-header border-bottom">
                <h3 class="card-title">Organization Details</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('organizations.update', $organization->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="org_name">Organization Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="org_name" value="{{ old('org_name', $organization->org_name) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="phone">Phone</label>
                            <input type="text" class="form-control" name="phone" value="{{ old('phone', $organization->phone) }}">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" class="form-control" name="email" value="{{ old('email', $organization->email) }}">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="address">Address</label>
                            <textarea class="form-control" name="address" rows="3">{{ old('address', $organization->address) }}</textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Has Multiple Companies?</label>
                            <div class="d-flex gap-4 mt-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="has_multiple_companies" id="multi_yes" value="1" {{ old('has_multiple_companies', $organization->has_multiple_companies) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="multi_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="has_multiple_companies" id="multi_no" value="0" {{ !old('has_multiple_companies', $organization->has_multiple_companies) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="multi_no">No</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mt-4 text-end">
                            <button type="submit" class="btn btn-primary px-5">Update Organization</button>
                            <a href="{{ route('organizations.index') }}" class="btn btn-light ms-2 px-5">Cancel</a>
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
                <h3 class="card-title">Organization Logo</h3>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <div id="logo-preview-container" class="mb-4">
                        @if($organization->logo)
                            <img id="logo-preview" src="{{ asset('storage/' . $organization->logo) }}" alt="Logo Preview" class="avatar avatar-xxl rounded-circle shadow-sm mx-auto" style="width:120px; height:120px; object-fit: cover;">
                            <div id="logo-placeholder" class="avatar avatar-xxl rounded-circle bg-light text-muted d-flex align-items-center justify-content-center mx-auto d-none" style="width:120px; height:120px;">
                                <i class="fe fe-image fs-30"></i>
                            </div>
                        @else
                            <div id="logo-placeholder" class="avatar avatar-xxl rounded-circle bg-light text-muted d-flex align-items-center justify-content-center mx-auto" style="width:120px; height:120px;">
                                <i class="fe fe-image fs-30"></i>
                            </div>
                            <img id="logo-preview" src="#" alt="Logo Preview" class="avatar avatar-xxl rounded-circle shadow-sm mx-auto d-none" style="width:120px; height:120px; object-fit: cover;">
                        @endif
                    </div>
                    <div>
                        <!-- Hidden logo input is added via JS to the form -->
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill mb-2" id="btn-upload">
                        <i class="fe fe-upload me-2"></i>Change Logo
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm rounded-pill mb-2 {{ $organization->logo ? '' : 'd-none' }}" id="btn-remove-logo">
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
