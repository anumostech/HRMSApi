@extends('employee.layouts.app')

@section('title', 'My Task Reports')
@section('page-title', 'My Task Reports')

@section('content')
<div class="card border-0 shadow-sm mt-3">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold"><i class="fe fe-clipboard text-primary me-2"></i> Task Reports</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle datatable-basic">
                <thead class="bg-light">
                    <tr>
                        <th width="15%">Date</th>
                        <th width="35%">Tasks Completed</th>
                        <th width="35%">Plan for Tomorrow</th>
                        <th width="15%">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                    <tr>
                        <td class="fw-bold">{{ \Carbon\Carbon::parse($report->date)->format('d M Y') }}</td>
                        <td>{{ $report->tasks_completed }}</td>
                        <td>{{ $report->plan_tomorrow }}</td>
                        <td>{{ $report->remarks ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">No task reports found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
