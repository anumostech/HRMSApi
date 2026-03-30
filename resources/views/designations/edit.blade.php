@extends('layouts.app')

@section('content')
<div class="row w-100 mt-4">
    <div class="col-lg-6 mx-auto">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0 fw-bold"><i class="fe fe-edit me-2"></i>Edit Designation</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('designations.update', $designation->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Designation Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $designation->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-4 form-check form-switch">
                        <input class="form-check-input flexShrink" type="checkbox" id="default_punch_access" name="default_punch_access" value="1" {{ old('default_punch_access', $designation->default_punch_access) ? 'checked' : '' }}>
                        <label class="form-check-label ms-2" for="default_punch_access">
                            <strong>Default Punch Access</strong>
                            <br>
                            <span class="text-muted small">If enabled, employees with this designation can punch in/out without needing an approved WFH request. (e.g. Delivery Man, Salesperson)</span>
                        </label>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('designations.index') }}" class="btn btn-light me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Designation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
