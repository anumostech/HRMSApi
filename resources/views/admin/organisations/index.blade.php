@extends('layouts.app')

@section('title', 'Organizations')

@section('content')
<div class="row mt-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="page-title">Organization Management</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Organizations</li>
                </ol>
            </div>
            <a href="{{ route('organizations.create') }}" class="btn btn-primary">
                <i class="fe fe-plus"></i> Add Organization
            </a>
        </div>
    </div>
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover datatable-basic text-nowrap">
                        <thead>
                            <tr>
                                <th>Logo</th>
                                <th>Org Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Multi-Company</th>
                                <th>Created At</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($organisations as $org)
                            <tr>
                                <td>
                                    @if($org->logo)
                                    <img src="{{ asset('storage/' . $org->logo) }}" alt="Logo" class="avatar avatar-sm rounded-circle shadow-sm">
                                    @else
                                    <div class="avatar avatar-sm rounded-circle bg-light text-muted d-flex align-items-center justify-content-center">
                                        <i class="fe fe-briefcase"></i>
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $org->org_name }}</div>
                                </td>
                                <td>{{ $org->phone ?? 'N/A' }}</td>
                                <td>{{ $org->email ?? 'N/A' }}</td>
                                <td>
                                    @if($org->has_multiple_companies)
                                    <span class="badge bg-success-transparent text-success">Yes</span>
                                    <a href="{{ route('companies.index', ['organisation_id' => $org->id]) }}" class="ms-2 small text-primary">Manage Companies</a>
                                    @else
                                    <span class="badge bg-light text-muted">No</span>
                                    @endif
                                </td>
                                <td>{{ $org->created_at->format('d M, Y') }}</td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('organizations.edit', $org->id) }}" class="btn btn-sm btn-outline-info">
                                            <i class="fe fe-edit-2"></i>
                                        </a>
                                        <form action="{{ route('organizations.destroy', $org->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger delete-confirm">
                                                <i class="fe fe-trash-2"></i>
                                            </button>
                                        </form>
                                    </div>
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

@section('scripts')
<script>
    $(document).on('click', '.delete-confirm', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');
        Swal.fire({
            title: 'Are you sure?',
            text: "This organization and its associated companies will be soft-deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>
@endsection