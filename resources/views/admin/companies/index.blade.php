@extends('layouts.app')

@section('title', 'Companies')

@section('content')
<div class="row mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title">Company Management</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                @if($organization_id)
                <li class="breadcrumb-item"><a href="{{ route('organizations.index') }}">Organizations</a></li>
                @endif
                <li class="breadcrumb-item active" aria-current="page">Companies</li>
            </ol>
        </div>
        <a href="{{ route('companies.create', ['organization_id' => $organization_id]) }}" class="btn btn-primary">
            <i class="fe fe-plus"></i> Add Company
        </a>
    </div>
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover datatable-basic text-nowrap">
                        <thead>
                            <tr>
                                <th>Logo</th>
                                <th>Company Name</th>
                                <th>Organization</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Created At</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($companies as $company)
                            <tr>
                                <td>
                                    @if($company->logo)
                                    <img src="{{ asset('storage/' . $company->logo) }}" alt="Logo" class="avatar avatar-sm rounded-circle shadow-sm" style="object-fit:cover;">
                                    @else
                                    <div class="avatar avatar-sm rounded-circle bg-light text-muted d-flex align-items-center justify-content-center">
                                        <i class="fe fe-briefcase"></i>
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $company->company_name }}</div>
                                </td>
                                <td>
                                    {{ $company->organization ? $company->organization->org_name : 'N/A' }}
                                </td>
                                <td>{{ $company->phone ?? 'N/A' }}</td>
                                <td>{{ $company->email ?? 'N/A' }}</td>
                                <td>{{ $company->created_at->format('d M, Y') }}</td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('companies.edit', $company->id) }}" class="btn btn-sm btn-outline-info">
                                            <i class="fe fe-edit-2"></i>
                                        </a>
                                        <form action="{{ route('companies.destroy', $company->id) }}" method="POST" class="d-inline">
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
            text: "This company will be soft-deleted!",
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