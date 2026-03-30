@extends('layouts.app')

@section('content')
<div class="row w-100">
    <div class="col-lg-12 mx-auto">
        <div class="page-header mt-4 mb-4 d-flex justify-content-between align-items-center">
            <h1 class="page-title text-primary"><i class="fe fe-tag"></i> Designations</h1>
            <a href="{{ route('designations.create') }}" class="btn btn-primary"><i class="fe fe-plus"></i> Add Designation</a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 datatable-basic">
                        <thead class="bg-light">
                            <tr>
                                <th>Name</th>
                                <th>Default Punch Access</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($designations as $designation)
                            <tr>
                                <td class="fw-bold">{{ $designation->name }}</td>
                                <td>
                                    @if($designation->default_punch_access)
                                        <span class="badge bg-success">Yes</span>
                                    @else
                                        <span class="badge bg-secondary">No</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('designations.edit', $designation->id) }}" class="btn btn-sm btn-outline-info me-1"><i class="fe fe-edit"></i></a>
                                    <form action="{{ route('designations.destroy', $designation->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fe fe-trash-2"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
