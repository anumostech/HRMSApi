@extends('layouts.app')

@section('title', 'Employee Listing')

@section('content')
<div class="page-header">
    <h1 class="page-title">Employees</h1>
    <div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Employees</a></li>
            <li class="breadcrumb-item active" aria-current="page">Listing</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3 class="card-title">Employee List ({{ ucfirst($status) }})</h3>
                <div>
                    <a href="{{ route('employees.index', ['status' => 'active']) }}" class="btn btn-sm btn-{{ $status == 'active' ? 'primary' : 'outline-primary' }}">Active</a>
                    <a href="{{ route('employees.index', ['status' => 'inactive']) }}" class="btn btn-sm btn-{{ $status == 'inactive' ? 'primary' : 'outline-primary' }}">Inactive</a>
                    <a href="{{ route('employees.create') }}" class="btn btn-sm btn-success ms-2"><i class="fe fe-plus"></i> Add Employee</a>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap border-bottom" id="basic-datatable">
                        <thead>
                            <tr>
                                <th class="wd-15p border-bottom-0">Name</th>
                                <th class="wd-15p border-bottom-0">Designation</th>
                                <th class="wd-20p border-bottom-0">Department</th>
                                <th class="wd-15p border-bottom-0">Company</th>
                                <th class="wd-10p border-bottom-0">Status</th>
                                <th class="wd-25p border-bottom-0">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employees as $employee)
                            <tr>
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->designation }}</td>
                                <td>{{ $employee->department }}</td>
                                <td>{{ $employee->company->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $employee->status == 'active' ? 'success' : 'danger' }}">{{ ucfirst($employee->status) }}</span>
                                </td>
                                <td>
                                    <div class="btn-list">
                                        <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-sm btn-info" title="View Details">
                                            <i class="fe fe-eye"></i>
                                        </a>
                                        <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fe fe-edit"></i>
                                        </a>
                                        @if($employee->status == 'active')
                                        <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to deactivate this employee?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Deactivate">
                                                <i class="fe fe-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No employees found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $employees->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
