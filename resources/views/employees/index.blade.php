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
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Employees ({{ ucfirst($status) }})</h3>
                <div>
                    <a href="{{ route('employees.create') }}" class="btn btn-primary ms-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="white" viewBox="0 0 16 16">
                                            <path d="M8 1a.5.5 0 0 1 .5.5V7.5H14.5a.5.5 0 0 1 0 1H8.5V14.5a.5.5 0 0 1-1 0V8.5H1.5a.5.5 0 0 1 0-1H7.5V1.5A.5.5 0 0 1 8 1z" />
                                        </svg> Add Employee</a>
                </div>
            </div>

            <div class="card-body">
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('employees.index', ['status' => 'active']) }}" class="btn btn-sm btn-{{ $status == 'active' ? 'primary' : 'outline-primary' }}">Active</a>
                    <a href="{{ route('employees.index', ['status' => 'inactive']) }}" class="btn btn-sm btn-{{ $status == 'inactive' ? 'danger' : 'outline-danger' }}">Inactive</a>
                </div>

                <div class="table-responsive mt-2">
                    <table class="table table-modern text-nowrap" id="basic-datatable">
                        <thead>
                            <tr>
                                <th class="wd-15p border-bottom-0">Sl.No.</th>
                                <th class="wd-15p border-bottom-0">Name</th>
                                <th class="wd-15p border-bottom-0">Designation</th>
                                <th class="wd-20p border-bottom-0">Department</th>
                                <th class="wd-15p border-bottom-0">Company</th>
                                <th class="wd-10p border-bottom-0">Leave Allocation</th>
                                <th class="wd-10p border-bottom-0">Status</th>
                                <th class="wd-25p border-bottom-0">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employees as $key => $employee)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $employee->first_name.' '.$employee?->last_name }}</td>
                                <td>{{ $employee->designation?->name }}</td>
                                <td>{{ $employee->department->name ?? '' }}</td>
                                <td>{{ $employee->company->company_name ?? '' }}</td>
                                <td>
                                    <span class="badge bg-info-light text-info rounded-pill px-3">{{ $employee->total_leaves_allocated ?? 0 }} Days</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="form-check form-switch d-flex align-items-center">
                                            <input
                                                class="form-check-input status-toggle"
                                                type="checkbox"
                                                role="switch"
                                                id="status"
                                                data-id="{{ $employee->id }}"
                                                {{ $employee->status == 'active' ? 'checked' : '' }}
                                                style="height: 25px;
                                            width: 45px;
                                            margin-left: -2.2em;
                                            margin-top: 0;
                                            position:relative;">
                                            <span class="status-text{{ $employee->id }}">{{ ucfirst($employee->status) }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-list d-flex">
                                        <!-- <a href="#" class="btn btn-sm btn-info" title="View Details">
                                            <i class="fe fe-eye"></i>
                                        </a> -->
                                        <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-sm btn-info" title="View Details">
                                            <i class="fe fe-eye"></i>
                                        </a>
                                        <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fe fe-edit"></i>
                                        </a>
                                        <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" onsubmit="confirmDelete(event)">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Deactivate">
                                                <i class="fe fe-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No employees found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div>
                
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    function confirmDelete(event) {

        event.preventDefault(); 

        Swal.fire({
            title: "Are you sure?",
            text: "This employee will be deleted!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#e9e9f1",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {

            if (result.isConfirmed) {
                event.target.submit(); 
            }

        });

    }

    document.addEventListener('change', function(e) {

        if (e.target.classList.contains('status-toggle')) {

            let employeeId = e.target.dataset.id;
            let status = e.target.checked ? 'active' : 'inactive';

            let emp_url = "{{ route('employees.updateStatus', ':id') }}";
            emp_url = emp_url.replace(':id', employeeId);


            axios.post(emp_url, {
                    status: status
                }, {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(function(response) {
                    let formattedStatus = status.charAt(0).toUpperCase() + status.slice(1);
                    $(".status-text" + employeeId).text(formattedStatus);

                    let message = status === 'active' ?
                        'Employee activated successfully.' :
                        'Employee deactivated successfully.';

                    Swal.fire({
                        icon: 'success',
                        title: message,
                        showConfirmButton: false,
                        timer: 1500
                    });

                    setTimeout(function(){
                        window.location.href = "{{ route('employees.index') }}?status=" + status;
                    }, 1510);

                })
                .catch(function(error) {

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Status update failed'
                    });

                });

        }

    });
</script>
@endsection