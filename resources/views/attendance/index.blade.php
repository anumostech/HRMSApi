@extends('layouts.app')

@section('title', 'Attendance Listing')

@section('content')
<!-- PAGE-HEADER -->
<div class="page-header">
    <h1 class="page-title">Attendance Listing</h1>
    <div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Attendance</li>
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END -->

<!-- Summary Cards -->
<div class="row">
    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-2">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="text-center">
                    <h6 class="mb-1">Total Employees</h6>
                    <h2 class="mb-0 number-font fs-20">{{ $stats['total'] }}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-2">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="text-center">
                    <h6 class="mb-1">Active</h6>
                    <h2 class="mb-0 number-font fs-20 text-success">{{ $stats['active'] }}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-2">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="text-center">
                    <h6 class="mb-1">Inactive</h6>
                    <h2 class="mb-0 number-font fs-20 text-muted">{{ $stats['inactive'] }}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="text-center">
                    <h6 class="mb-1">Punch In (Today)</h6>
                    <h2 class="mb-0 number-font fs-20 text-primary">{{ $stats['punched_in'] }}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="text-center">
                    <h6 class="mb-1">Punch Out (Today)</h6>
                    <h2 class="mb-0 number-font fs-20 text-info">{{ $stats['punched_out'] }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3 class="card-title">Attendance Records</h3>
                <a href="{{ route('attendance.upload') }}" class="btn btn-primary">Upload Logs</a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('attendance.index') }}" method="GET" class="mb-4">
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <label for="company_id" class="form-label">Company</label>
                            <select name="company_id" id="company_id" class="form-control">
                                <option value="">All Companies</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="employee_name" class="form-label">Employee Name</label>
                            <input type="text" name="employee_name" id="employee_name" class="form-control" placeholder="Search name..." value="{{ request('employee_name') }}">
                        </div>
                        <div class="col-md-2">
                            <label for="date_preset" class="form-label">Date Filter</label>
                            <select name="date_preset" id="date_preset" class="form-control" onchange="toggleCustomDates(this.value)">
                                <option value="">All Time</option>
                                <option value="today" {{ request('date_preset') == 'today' ? 'selected' : '' }}>Today</option>
                                <option value="yesterday" {{ request('date_preset') == 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                                <option value="last_week" {{ request('date_preset') == 'last_week' ? 'selected' : '' }}>Last Week</option>
                                <option value="last_month" {{ request('date_preset') == 'last_month' ? 'selected' : '' }}>Last Month</option>
                                <option value="custom" {{ request('date_preset') == 'custom' ? 'selected' : '' }}>Custom</option>
                            </select>
                        </div>
                        <div class="col-md-2 custom-date-range" style="display: {{ request('date_preset') == 'custom' ? 'block' : 'none' }};">
                            <label for="from_date" class="form-label">From</label>
                            <input type="date" name="from_date" id="from_date" class="form-control" value="{{ request('from_date') }}">
                        </div>
                        <div class="col-md-2 custom-date-range" style="display: {{ request('date_preset') == 'custom' ? 'block' : 'none' }};">
                            <label for="to_date" class="form-label">To</label>
                            <input type="date" name="to_date" id="to_date" class="form-control" value="{{ request('to_date') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="mt-2 btn btn-primary w-100">Filter</button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap border-bottom" id="basic-datatable">
                        <thead>
                            <tr>
                                <th>Sl.No.</th>
                                <th>Company</th>
                                <th>Employee Name</th>
                                <th>Date</th>
                                <th>Punch In</th>
                                <th>Punch Out</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendance as $key => $record)
                                <tr>
                                    <td>{{ $attendance->firstItem() + $key }}</td>
                                    <td>{{ $record->company->name }}</td>
                                    <td>{{ $record->user->name ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($record->date)->format('d-m-Y') }}</td>
                                    <td><div class="d-flex align-items-center"><span>
                                        @if($record->punch_in)
                                            {{ \Carbon\Carbon::parse($record->punch_in)->format('h:i A') }}
                                        @else
                                            <span class="text-danger" style="font-size: 12px;">Not Punched In</span>
                                        @endif</span>
                                        @if($record->status === 'Late Comer')
                                            <span class="badge bg-danger" style="padding: 2px 5px;">Late</span>
                                        @endif</div></td>
                                    <td>
                                        @if($record->punch_out)
                                            {{ \Carbon\Carbon::parse($record->punch_out)->format('h:i A') }}
                                        @else
                                            <span class="text-danger" style="font-size: 12px;">Not Punched Out</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">No attendance records found with these filters.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex align-items-center justify-content-between mt-4">
                    <div class="text-muted d-none d-lg-block">
                        Showing {{ $attendance->firstItem() }} to {{ $attendance->lastItem() }} of {{ $attendance->total() }} records
                    </div>
                    <div>
                        {{ $attendance->links() }}
                    </div>
                </div>
@endsection

@section('scripts')
<!-- DATA TABLE JS-->
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/dataTables.bootstrap5.min.js') }}"></script>
<script>
    function toggleCustomDates(value) {
        const customDates = document.querySelectorAll('.custom-date-range');
        customDates.forEach(el => {
            el.style.display = (value === 'custom') ? 'block' : 'none';
        });
    }

    $(function(e) {
        $('#params-datatable').DataTable({
            language: {
                searchPlaceholder: 'Search...',
                sSearch: '',
            }
        });
    });
</script>
@endsection
