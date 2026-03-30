@extends('layouts.app')

@section('content')
<div class="row w-100">
    <div class="col-lg-12 mx-auto">
        <div class="page-header mt-4 mb-4">
            <h1 class="page-title text-primary"><i class="fe fe-home"></i> Work From Home Requests</h1>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success mt-2">{{ session('success') }}</div>
                @endif
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 datatable-basic text-nowrap">
                        <thead class="bg-light">
                            <tr>
                                <th>Date</th>
                                <th>Employee</th>
                                <th>Reason</th>
                                <th>Notes</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $req)
                            <tr>
                                <td class="fw-bold">{{ \Carbon\Carbon::parse($req->date)->format('d M Y') }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="fw-bold">{{ $req->employee->name }}</div>
                                    </div>
                                    <div class="small text-muted">{{ $req->employee->employee_id }}</div>
                                </td>
                                <td>{{ \Illuminate\Support\Str::limit($req->reason, 30) }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($req->notes, 30) }}</td>
                                <td>
                                    @if($req->status == 'Approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif($req->status == 'Rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if($req->status == 'Pending')
                                    <form action="{{ route('wfh_requests.status', $req->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="Approved">
                                        <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                    </form>
                                    <form action="{{ route('wfh_requests.status', $req->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="Rejected">
                                        <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                    </form>
                                    @else
                                        -
                                    @endif
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
