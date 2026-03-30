@extends('employee.layouts.app')

@section('title', 'My Dashboard')
@section('page-title', 'My Dashboard')

@push('styles')
    <link href="{{ asset('assets/css/dashboard_modern.css') }}" rel="stylesheet">
@endpush

@section('content')

    {{-- Welcome & Quick Info --}}
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="dashboard-hero">
                <div class="d-flex align-items-center gap-4 position-relative" style="z-index: 2;">
                    <img src="{{ $employee->avatar_url }}" alt="Avatar"
                        style="width:80px;height:80px;border-radius:20px;border:4px solid rgba(255,255,255,0.2);object-fit:cover;box-shadow: 0 8px 16px rgba(0,0,0,0.2);">
                    <div>
                        <h2 class="mb-1 fw-bold">Welcome, {{ explode(' ', $employee->name)[0] }}!</h2>
                        <p class="mb-0 opacity-75 fs-6">
                            <i class="fe fe-briefcase me-1"></i> {{ $employee->designation ?? 'Team Member' }}
                            <span class="mx-2">|</span>
                            <i class="fe fe-layers me-1"></i> {{ optional($employee->department)->name ?? 'General' }}
                        </p>
                    </div>
                    <div class="ms-auto d-none d-md-block text-end">
                        <div class="fs-4 fw-bold">{{ now()->format('h:i A') }}</div>
                        <div class="opacity-75 small mb-2">{{ now()->format('l, d M Y') }}</div>
                        @if($canPunch)
                            @if(!$todayLog || !$todayLog->punch_in)
                                <form action="{{ route('employee.punch.in') }}" method="POST">
                                    @csrf
                                    <button class="btn btn-success rounded-pill px-4 shadow-sm"><i class="fe fe-log-in me-1"></i> Punch In</button>
                                </form>
                            @elseif(!$todayLog->punch_out)
                                <button type="button" class="btn btn-danger rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#punchOutModal">
                                    <i class="fe fe-log-out me-1"></i> Punch Out
                                </button>
                            @else
                                <button class="btn btn-secondary rounded-pill px-4 shadow-sm" disabled><i class="fe fe-check-circle me-1"></i> Punched Out</button>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Today's Status & Leave Info --}}
    <div class="row g-3 mb-4">
        <div class="col-md-2 col-sm-6">
            <div class="card stat-card h-100 border-0 shadow-sm p-3">
                <div class="stat-icon bg-soft-success">
                    <i class="fe fe-log-in"></i>
                </div>
                <div class="text-muted small text-uppercase fw-semibold mb-1">Punch In</div>
                <div class="h5 mb-0 fw-bold text-success">
                    {{ $todayLog && $todayLog->punch_in ? \Carbon\Carbon::parse($todayLog->punch_in)->format('h:i A') : '—' }}
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-6">
            <div class="card stat-card h-100 border-0 shadow-sm p-3">
                <div class="stat-icon bg-soft-danger">
                    <i class="fe fe-log-out"></i>
                </div>
                <div class="text-muted small text-uppercase fw-semibold mb-1">Punch Out</div>
                <div class="h5 mb-0 fw-bold text-danger">
                    {{ $todayLog && $todayLog->punch_out ? \Carbon\Carbon::parse($todayLog->punch_out)->format('h:i A') : '—' }}
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-6">
            <div class="card stat-card h-100 border-0 shadow-sm p-3">
                <div class="stat-icon bg-soft-primary">
                    <i class="fe fe-calendar"></i>
                </div>
                <div class="text-muted small text-uppercase fw-semibold mb-1">Present (30d)</div>
                <div class="h5 mb-0 fw-bold text-primary">
                    {{ $attendanceLogs->count() }} <small class="text-muted" style="font-size: 0.6rem;">DAYS</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card stat-card h-100 border-0 shadow-sm p-3">
                <div class="stat-icon bg-soft-warning">
                    <i class="fe fe-user-x"></i>
                </div>
                <div class="text-muted small text-uppercase fw-semibold mb-1">Leaves Taken</div>
                <div class="h5 mb-0 fw-bold text-warning">
                    {{ (int) $totalLeavesTaken }} <small class="text-muted" style="font-size: 0.6rem;">DAYS</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card stat-card h-100 border-0 shadow-sm p-3"
                style="border-left: 4px solid var(--hr-primary) !important;">
                <div class="stat-icon bg-soft-info">
                    <i class="fe fe-pocket"></i>
                </div>
                <div class="text-muted small text-uppercase fw-semibold mb-1">Leave Balance</div>
                <div class="h5 mb-0 fw-bold text-info">
                    @if($leaveBalance < 0)
                        <span>0</span>
                    @else
                        {{ (int) $leaveBalance }}
                    @endif
                    <small class="text-muted" style="font-size: 0.6rem;">DAYS</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Attendance History --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-transparent border-0 py-3">
            <div class="d-flex align-items-center">
                <div class="bg-soft-primary p-2 rounded-3 me-3">
                    <i class="fe fe-activity text-primary"></i>
                </div>
                <h5 class="mb-0 fw-bold">My Attendance</h5>
            </div>
        </div>
        <div class="card-body p-5">
            @if($attendanceLogs->isEmpty())
                <div class="text-center py-5 text-muted">
                    <div style="font-size:3rem;" class="opacity-25 mb-3"><i class="fe fe-calendar"></i></div>
                    <p class="mb-0">No attendance records found for the last 30 days.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-modern align-middle mb-0 datatable-basic" id="org-files-table">
                        <thead>
                            <tr>
                                <th class="ps-4">Date</th>
                                <th class="ps-4">Day</th>
                                <th>Punch In</th>
                                <th>Punch Out</th>
                                <th>Duration</th>
                                <th class="pe-4 text-end">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendanceLogs as $log)
                                @php
                                    $punchInTime = $log->punch_in ? \Carbon\Carbon::parse($log->punch_in) : null;
                                    $punchOutTime = $log->punch_out ? \Carbon\Carbon::parse($log->punch_out) : null;
                                    $isLate = $punchInTime && $punchInTime->format('H:i:s') >= '08:11:00' && $punchInTime->format('H:i:s') <= '12:00:00';

                                    $duration = '—';
                                    if ($punchInTime && $punchOutTime) {
                                        $diff = $punchInTime->diff($punchOutTime);
                                        $duration = $diff->h . 'h ' . $diff->i . 'm';
                                    }
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">
                                            @if($log->date)
                                                {{ \Carbon\Carbon::parse($log->date)->format('d-m-Y') }}
                                            @else
                                                <span class="text-muted">No Date</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-muted small">{{ \Carbon\Carbon::parse($log->date)->format('l') }}</div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="dot bg-success me-2"></div>
                                            {{ $punchInTime ? $punchInTime->format('h:i A') : '—' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="dot bg-danger me-2"></div>
                                            {{ $punchOutTime ? $punchOutTime->format('h:i A') : '—' }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark fw-normal">{{ $duration }}</span>
                                    </td>
                                    <td class="pe-4 text-end">
                                        @if($isLate)
                                            <span class="badge badge-soft-warning px-3 rounded-pill">Latecomer</span>
                                        @elseif($punchInTime)
                                            <span class="badge badge-soft-success px-3 rounded-pill">Present</span>
                                        @else
                                            <span class="badge badge-soft-danger px-3 rounded-pill">Absent</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <style>
        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .badge-soft-warning {
            background-color: rgba(245, 158, 11, 0.1);
            color: #d97706;
        }

        .badge-soft-success {
            background-color: rgba(34, 197, 94, 0.1);
            color: #15803d;
        }

        .badge-soft-danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: #b91c1c;
        }

        .table-modern thead th {
            border-bottom: 1px solid #f1f5f9;
        }

        .bg-soft-info {
            background-color: rgba(6, 182, 212, 0.1);
            color: #0891b2;
        }

        .text-info {
            color: #0891b2;
        }
    </style>

    {{-- Punch Out Modal --}}
    <div class="modal fade" id="punchOutModal" tabindex="-1" aria-labelledby="punchOutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow">
                <form action="{{ route('employee.punch.out') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-soft-danger text-danger border-0">
                        <h5 class="modal-title fw-bold" id="punchOutModalLabel"><i class="fe fe-log-out me-2"></i> Submit Task Report</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tasks Completed Today <span class="text-danger">*</span></label>
                            <textarea name="tasks_completed" class="form-control" rows="3" required placeholder="Describe what you worked on today..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Plan for Tomorrow <span class="text-danger">*</span></label>
                            <textarea name="plan_tomorrow" class="form-control" rows="3" required placeholder="What will you work on tomorrow?"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Remarks (Optional)</label>
                            <textarea name="remarks" class="form-control" rows="2" placeholder="Any blockages or extra notes?"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger"><i class="fe fe-log-out me-1"></i> Punch Out</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection