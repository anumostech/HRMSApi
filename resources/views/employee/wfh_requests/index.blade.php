@extends('employee.layouts.app')

@section('title', 'My WFH Requests')
@section('page-title', 'My WFH Requests')

@section('content')
<div class="card border-0 shadow-sm mt-3">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold"><i class="fe fe-home text-primary me-2"></i> Work From Home Requests</h5>
        <a href="{{ route('employee.wfh.create') }}" class="btn btn-primary shadow-sm"><i class="fe fe-plus me-1"></i> New Request</a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle datatable-basic">
                <thead class="bg-light">
                    <tr>
                        <th>Date</th>
                        <th>Reason</th>
                        <th>Notes</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $req)
                    <tr>
                        <td class="fw-bold">{{ \Carbon\Carbon::parse($req->date)->format('d M Y') }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($req->reason, 50) }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($req->notes, 50) }}</td>
                        <td>
                            @if($req->status == 'Approved')
                                <span class="badge bg-success">Approved</span>
                            @elseif($req->status == 'Rejected')
                                <span class="badge bg-danger">Rejected</span>
                            @else
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">No WFH requests found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
