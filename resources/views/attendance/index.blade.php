@extends('layouts.app')

@section('title', 'Attendance Listing')

@section('styles')
<link href="{{ asset('assets/css/dashboard_modern.css') }}" rel="stylesheet">
<style>
    .breadcrumb-item.active {
        color: #6366f1;
        font-weight: 600;
    }

    .page-title {
        font-weight: 700;
        color: #1e293b;
    }
</style>
@endsection

@section('content')
<!-- PAGE-HEADER -->
<div class="page-header">
    <h1 class="page-title">Attendance</h1>
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
        <a href="{{ route('employees.index') }}">
            <div class="card overflow-hidden stat-card">
                <div class="card-body">
                    <div class="text-center">
                        <h6 class="mb-1">
                            <span class="me-1">
                                <i class="fe fe-users fs-16 text-primary"></i>
                            </span>Total Employees
                        </h6>
                        <h2 class="mb-0 number-font fs-20">{{ $stats['total'] }}</h2>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-2">
        <a href="{{ route('attendance.punchInToday') }}">
            <div class="card overflow-hidden stat-card">
                <div class="card-body">
                    <div class="text-center">
                        <h6 class="mb-1">
                            <span class="me-1">
                                <i class="fe fe-log-in fs-16 text-success"></i>
                            </span>Punched In Today
                        </h6>
                        <h2 class="mb-0 number-font fs-20 text-success">{{ $stats['punched_in'] }}</h2>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-2">
        <a href="{{ route('attendance.late') }}">
            <div class="card overflow-hidden stat-card">
                <div class="card-body">
                    <div class="text-center">
                        <h6 class="mb-1">
                            <span class="me-1">
                                <i class="fe fe-clock text-warning fs-16"></i>
                            </span>Late Today
                        </h6>
                        <h2 class="mb-0 number-font fs-20 text-muted">{{ $stats['punched_late'] }}</h2>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-2">
        <a href="{{ route('attendance.absent') }}">
            <div class="card overflow-hidden stat-card">
                <div class="card-body">
                    <div class="text-center">
                        <h6 class="mb-1">
                            <span class="me-1">
                                <i class="fe fe-x-circle text-danger fs-16"></i>
                            </span>
                            Absent Today
                        </h6>
                        <h2 class="mb-0 number-font fs-20 text-primary">{{ $stats['absent_today'] }}</h2>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-2">
        <a href="{{ route('attendance.punchOutYesterday') }}">
            <div class="card overflow-hidden stat-card">
                <div class="card-body">
                    <div class="text-center">
                        <h6 class="mb-1">
                            <span class="me-1">
                                <i class="fe fe-log-out text-danger fs-16"></i>
                            </span>
                            Punch Out Yesterday
                        </h6>
                        <h2 class="mb-0 number-font fs-20 text-info">{{ $stats['punch_out_yesterday'] }}</h2>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3 class="card-title">Attendance Records</h3>
                <a href="{{ route('attendance.upload') }}" class="btn btn-primary"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="white" viewBox="0 0 16 16">
                        <path d="M8 1a.5.5 0 0 1 .5.5V7.5H14.5a.5.5 0 0 1 0 1H8.5V14.5a.5.5 0 0 1-1 0V8.5H1.5a.5.5 0 0 1 0-1H7.5V1.5A.5.5 0 0 1 8 1z" />
                    </svg>Upload Logs</a>
            </div>
            <div class="card-body">

                <form action="{{ route('attendance.index') }}" method="GET" class="mb-4">
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <label for="company_id" class="form-label">Company</label>
                            <div class="select-wrapper">
                                <select name="company_id" id="company_id" class="form-control">
                                    <option value="">All Companies</option>
                                    @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                        {{ $company->company_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="employee_name" class="form-label">Employee Name</label>
                            <input type="text" name="employee_name" id="employee_name" class="form-control" placeholder="Search name..." value="{{ request('employee_name') }}">
                        </div>
                        <div class="col-md-2">
                            <label for="date_preset" class="form-label">Date Filter</label>
                            <div class="select-wrapper">
                                <select name="date_preset" id="date_preset" class="form-control" onchange="toggleCustomDates(this.value)">
                                    <option value="">All Time</option>
                                    <option value="today" {{ request('date_preset') == 'today' ? 'selected' : '' }}>Today</option>
                                    <option value="yesterday" {{ request('date_preset') == 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                                    <option value="last_week" {{ request('date_preset') == 'last_week' ? 'selected' : '' }}>Last Week</option>
                                    <option value="last_month" {{ request('date_preset') == 'last_month' ? 'selected' : '' }}>Last Month</option>
                                    <option value="custom" {{ request('date_preset') == 'custom' ? 'selected' : '' }}>Custom</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 custom-date-range" style="display: {{ request('date_preset') == 'custom' ? 'block' : 'none' }};">
                            <label for="from_date" class="form-label">From</label>
                            <input type="text" name="from_date" id="from_date" class="form-control datepicker" value="{{ request('from_date') }}" placeholder="Select date">
                        </div>
                        <div class="col-md-2 custom-date-range" style="display: {{ request('date_preset') == 'custom' ? 'block' : 'none' }};">
                            <label for="to_date" class="form-label">To</label>
                            <input type="text" name="to_date" id="to_date" class="form-control datepicker" value="{{ request('to_date') }}" placeholder="Select date">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="mt-2 btn btn-primary w-100">Filter</button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-modern text-nowrap">
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
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $record->company->company_name }}</td>
                                <td>{{ $record->user->name ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($record->date)->format('d-m-Y') }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2"><span>
                                            @if($record->punch_in)
                                            {{ \Carbon\Carbon::parse($record->punch_in)->format('h:i A') }}
                                            @else
                                            <span class="text-danger" style="font-size: 12px;">Not Punched In</span>
                                            @endif</span>
                                        @if($record->status === 'Late Comer')
                                        <span class="badge bg-danger">Late</span>
                                        @endif
                                    </div>
                                </td>
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
                {{ $attendance->links() }}
                <div>

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