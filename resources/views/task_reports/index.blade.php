@extends('layouts.app')

@section('content')
<div class="row w-100">
    <div class="col-lg-12 mx-auto">
        <div class="page-header mt-4 mb-4">
            <h1 class="page-title text-primary"><i class="fe fe-clipboard"></i> Task Reports</h1>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 datatable-basic">
                        <thead class="bg-light">
                            <tr>
                                <th>Date</th>
                                <th>Employee</th>
                                <th>Tasks Completed</th>
                                <th>Plan for Tomorrow</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $report)
                            <tr>
                                <td class="fw-bold whitespace-nowrap">{{ \Carbon\Carbon::parse($report->date)->format('d M Y') }}</td>
                                <td>
                                    <div class="fw-bold">{{ $report->employee->name }}</div>
                                    <div class="small text-muted">{{ $report->employee->employee_id }}</div>
                                </td>
                                <td>{{ $report->tasks_completed }}</td>
                                <td>{{ $report->plan_tomorrow }}</td>
                                <td>{{ $report->remarks ?? '-' }}</td>
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
