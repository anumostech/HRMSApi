@extends('layouts.app')

@section('title', 'Attendance Listing')

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

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3 class="card-title">Punched Out Yesterday</h3>
            </div>
            <div class="card-body">

                <form action="{{ route('attendance.punchOutYesterday') }}" method="GET" class="mb-4">
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
                    <table class="table table-modern text-nowrap" id="basic-datatable">
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
                                <td>{{ $record->company->name }}</td>
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