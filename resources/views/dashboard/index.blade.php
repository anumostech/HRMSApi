@extends('layouts.app')

@section('title', 'Dashboard')

@section('styles')
<link href="{{ asset('assets/css/dashboard_modern.css') }}" rel="stylesheet">
<style>
    .breadcrumb-item.active { color: #6366f1; font-weight: 600; }
    .page-title { font-weight: 700; color: #1e293b; }
</style>
@endsection

@section('content')
<div class="page-header d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="page-title mb-0">Dashboard</h1>
        <!-- <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-muted">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol> -->
    </div>
    <div class="d-flex align-items-center gap-3">
        <div class="text-end d-none d-md-block">
            <p class="mb-0 text-muted small">Current Date</p>
            <h6 class="mb-0 fw-bold text-dark">{{ now()->format('l, d M Y') }}</h6>
        </div>
    </div>
</div>

<!-- Welcome Hero Section -->
<div class="row">
    <div class="col-12">
        <div class="dashboard-hero d-flex flex-column flex-md-row align-items-md-center justify-content-between">
            <div class="hero-content">
                <h2 class="fw-bold mb-2">Welcome, {{ Auth::user()->name ?? 'Admin' }}! </h2>
                <p class="mb-0 opacity-75">Here is what's happening today.</p>
            </div>
            <div class="hero-stats d-flex gap-4 mt-3 mt-md-0 pt-3 pt-md-0 border-md-top-0 border-light border-opacity-25">
                <div class="text-center">
                    <h3 class="fw-bold mb-0" id="hero-total-employees">{{ $todayStats['present'] + $todayStats['absent'] }}</h3>
                    <p class="mb-0 small opacity-75">Total Staff</p>
                </div>
                <div class="text-center">
                    <h3 class="fw-bold mb-0 text-white" id="hero-present">{{ $todayStats['present'] }}</h3>
                    <p class="mb-0 small opacity-75">Present</p>
                </div>
                <div class="text-center">
                    <h3 class="fw-bold mb-0 text-warning" id="hero-late">{{ $todayStats['late'] }}</h3>
                    <p class="mb-0 small opacity-75">Late</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <!-- Total Employees -->
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body p-4">
                <div class="stat-icon bg-soft-primary">
                    <i class="fe fe-users"></i>
                </div>
                <h6 class="text-muted mb-2 fw-semibold">Total Employees</h6>
                <div class="d-flex align-items-end justify-content-between">
                    <div>
                        <h2 class="mb-0 fw-bold" id="stat-total-val">{{ $todayStats['present'] + $todayStats['absent'] }}</h2>
                        <span class="text-success small fw-bold"><i class="fe fe-trending-up me-1"></i>Active Staff</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Punch-In Status -->
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body p-4">
                <div class="stat-icon bg-soft-success">
                    <i class="fe fe-log-in"></i>
                </div>
                <h6 class="text-muted mb-2 fw-semibold">Punched In Today</h6>
                <div class="d-flex align-items-end justify-content-between">
                    <div>
                        <h2 class="mb-0 fw-bold text-success">{{ $todayStats['punched_in'] }}</h2>
                        <span class="text-muted small">From total staff</span>
                    </div>
                    <div class="chart-mini">
                        <canvas id="miniChartIn" width="60" height="30"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Late Employees -->
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body p-4">
                <div class="stat-icon bg-soft-warning">
                    <i class="fe fe-clock"></i>
                </div>
                <h6 class="text-muted mb-2 fw-semibold">Late Arrivals</h6>
                <div class="d-flex align-items-end justify-content-between">
                    <div>
                        <h2 class="mb-0 fw-bold text-warning">{{ $todayStats['late'] }}</h2>
                        <a href="{{ route('attendance.late') }}" class="text-primary small fw-semibold text-decoration-none">View Details <i class="fe fe-chevron-right small"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Absent Employees -->
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body p-4">
                <div class="stat-icon bg-soft-danger">
                    <i class="fe fe-user-x"></i>
                </div>
                <h6 class="text-muted mb-2 fw-semibold">Absent Today</h6>
                <div class="d-flex align-items-end justify-content-between">
                    <div>
                        <h2 class="mb-0 fw-bold text-danger">{{ $todayStats['absent'] }}</h2>
                        <a href="{{ route('attendance.absent') }}" class="text-primary small fw-semibold text-decoration-none">Review Requests <i class="fe fe-chevron-right small"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row mb-4">
    <div class="col-xl-8">
        <div class="card shadow-sm border-0 br-16 h-100">
            <div class="card-header border-0 bg-white pt-4 px-4 d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="card-title fw-bold mb-1">Attendance Analytics</h5>
                    <p class="text-muted small mb-0">Daily presence trend for the last 7 days</p>
                </div>
                <div class="dropdown">
                    <button class="btn btn-sm btn-light border-0 px-3" data-bs-toggle="dropdown">
                        Last 7 Days <i class="fe fe-chevron-down ms-1"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Today</a></li>
                        <li><a class="dropdown-item active" href="#">Last 7 Days</a></li>
                        <li><a class="dropdown-item" href="#">This Month</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="chart-container" style="height: 350px;">
                    <canvas id="mainAttendanceChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="row h-100">
            <div class="col-12 mb-4">
                <div class="card shadow-sm border-0 br-16 h-100">
                    <div class="card-header border-0 bg-white pt-4 px-4 pb-0">
                        <h5 class="card-title fw-bold mb-1">Punch Activity</h5>
                    </div>
                    <div class="card-body p-4 d-flex flex-column justify-content-center">
                        <div class="chart-container" style="height: 180px;">
                            <canvas id="punchDistributionChart"></canvas>
                        </div>
                        <div class="mt-4">
                            <div class="row text-center mb-2">
                                <div class="col-4"></div>
                                <div class="col-4 text-muted small fw-bold">Today</div>
                                <div class="col-4 text-muted small fw-bold">Yesterday</div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-2 border-bottom pb-2 border-light">
                                <div class="d-flex align-items-center gap-2" style="width: 33%;">
                                    <span class="dot bg-success"></span>
                                    <span class="text-muted small">In</span>
                                </div>
                                <div class="fw-bold text-center" style="width: 33%;">{{ $todayStats['punched_in'] }}</div>
                                <div class="text-muted text-center" style="width: 33%;">{{ $yesterdayStats['punched_in'] }}</div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2" style="width: 33%;">
                                    <span class="dot bg-danger"></span>
                                    <span class="text-muted small">Out</span>
                                </div>
                                <div class="fw-bold text-center" style="width: 33%;">{{ $todayStats['punched_out'] }}</div>
                                <div class="text-muted text-center" style="width: 33%;">{{ $yesterdayStats['punched_out'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="panel panel-primary">
                    <div class="card-header border-0 bg-white pt-4 px-4 pb-0">
                        <div class="tabs-menu1">
                            <!-- Tabs -->
                            <ul class="nav nav-pills nav-pills-custom" role="tablist">
                                <li class="nav-item"><a href="#tab5" class="nav-link active" data-bs-toggle="tab" aria-selected="true" role="tab"><i class="fe fe-file-text me-2"></i>Organization Files</a></li>
                                <li class="nav-item"><a href="#tab6" class="nav-link" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab"><i class="fe fe-award me-2"></i>Agreements</a></li>
                                <li class="nav-item"><a href="#tab7" class="nav-link" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab"><i class="fe fe-briefcase me-2"></i>HR</a></li>
                                <li class="nav-item"><a href="#tab10" class="nav-link" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab"><i class="fe fe-users me-2"></i>Employees</a></li>
                                <li class="nav-item"><a href="#tab9" class="nav-link" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab"><i class="fe fe-folder me-2"></i>Folders</a></li>
                                <li class="nav-item"><a href="#tab8" class="nav-link" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab"><i class="fe fe-more-horizontal me-2"></i>Others</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body tabs-menu-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab5" role="tabpanel">
                                @if($organization_files->isEmpty())
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <img src="{{ asset('assets/images/no-data.png') }}">
                                    </div>
                                </div>
                                <h5 class="text-center mt-2">No organization file added</h5>
                                <p class="text-center">Upload important common files such as policies or company handbooks that can be shared across the entire organization or for selected business entities, locations, departments, etc.</p>
                                <div class="row">
                                    <div class="col-md-12 text-center mt-2">
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#largemodal" data-type="organization"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="white" viewBox="0 0 16 16">
                                                <path d="M8 1a.5.5 0 0 1 .5.5V7.5H14.5a.5.5 0 0 1 0 1H8.5V14.5a.5.5 0 0 1-1 0V8.5H1.5a.5.5 0 0 1 0-1H7.5V1.5A.5.5 0 0 1 8 1z" />
                                            </svg> Add Organization Files</button>
                                    </div>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col-md-12 text-end mt-2">
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#largemodal" data-type="organization"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="white" viewBox="0 0 16 16">
                                                <path d="M8 1a.5.5 0 0 1 .5.5V7.5H14.5a.5.5 0 0 1 0 1H8.5V14.5a.5.5 0 0 1-1 0V8.5H1.5a.5.5 0 0 1 0-1H7.5V1.5A.5.5 0 0 1 8 1z" />
                                            </svg> Add Organization Files</button>
                                    </div>
                                </div>
                                <div class="table-responsive mt-4">
                                    <table class="table table-modern border-0 datatable-basic" id="org-files-table">
                                        <thead>
                                            <tr>
                                                <th>Sl.No.</th>
                                                <th>Name</th>
                                                <th>Folder</th>
                                                <th>Share With</th>
                                                <th>Expiry Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($organization_files as $key => $record)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>

                                                <!-- File Name -->
                                                <td>
                                                    @php
                                                    $ext = strtolower(pathinfo($record->file_path, PATHINFO_EXTENSION));
                                                    @endphp

                                                    @if($ext == 'pdf')
                                                    <i class="fe fe-file-text text-danger"></i>

                                                    @elseif($ext == 'docx')
                                                    <i class="fe fe-file text-info"></i>

                                                    @elseif(in_array($ext,['jpg','jpeg','png']))
                                                    <i class="fe fe-image text-success"></i>
                                                    @endif
                                                    <a href="{{ asset('storage/'.$record->file_path) }}" class="text-black">
                                                        {{ $record->name }}{{ $ext }}
                                                    </a>
                                                </td>

                                                <!-- Folder -->
                                                <td>{{ $record->folder }}</td>

                                                <!-- Share With -->
                                                <td>{{ $record->shareWith->name ?? 'All' }}</td>

                                                <!-- Expiry Date -->
                                                <td>
                                                    @if($record->expiry_date)
                                                    {{ \Carbon\Carbon::parse($record->expiry_date)->format('d-m-Y') }}
                                                    @else
                                                    <span class="text-muted">No Expiry</span>
                                                    @endif
                                                </td>

                                                <!-- Actions -->
                                                <td>

                                                    <a href="{{ asset('storage/'.$record->file_path) }}"
                                                        class="btn btn-sm btn-primary"
                                                        target="_blank">

                                                        <i class="fe fe-eye"></i>
                                                    </a>

                                                    <a href="{{ asset('storage/'.$record->file_path) }}"
                                                        class="btn btn-sm btn-success"
                                                        download>

                                                        <i class="fe fe-download"></i>
                                                    </a>

                                                    <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $record->id }}">
                                                        <i class="fe fe-trash"></i>
                                                    </button>

                                                </td>

                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div>
                                
                                </div>
                                @endif
                            </div>
                            <div class="tab-pane" id="tab6" role="tabpanel">
                                @if($agreements->isEmpty())
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <img src="{{ asset('assets/images/no-data.png') }}">
                                    </div>
                                </div>
                                <h5 class="text-center mt-2">No agreements added</h5>
                                <div class="row">
                                    <div class="col-md-12 text-center mt-2">
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#largemodal" data-type="agreement"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="white" viewBox="0 0 16 16">
                                                <path d="M8 1a.5.5 0 0 1 .5.5V7.5H14.5a.5.5 0 0 1 0 1H8.5V14.5a.5.5 0 0 1-1 0V8.5H1.5a.5.5 0 0 1 0-1H7.5V1.5A.5.5 0 0 1 8 1z" />
                                            </svg> Add Agreement</button>
                                    </div>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col-md-12 text-end mt-2">
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#largemodal" data-type="agreement"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="white" viewBox="0 0 16 16">
                                                <path d="M8 1a.5.5 0 0 1 .5.5V7.5H14.5a.5.5 0 0 1 0 1H8.5V14.5a.5.5 0 0 1-1 0V8.5H1.5a.5.5 0 0 1 0-1H7.5V1.5A.5.5 0 0 1 8 1z" />
                                            </svg> Add Agreement</button>
                                    </div>
                                </div>
                                <div class="table-responsive mt-4">
                                    <table class="table table-modern border-0 datatable-basic" id="agreements-table">
                                        <thead>
                                            <tr>
                                                <th>Sl.No.</th>
                                                <th>Name</th>
                                                <th>Folder</th>
                                                <th>Share With</th>
                                                <th>Expiry Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($agreements as $key => $record)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>

                                                <!-- File Name -->
                                                <td>
                                                    @php
                                                    $ext = strtolower(pathinfo($record->file_path, PATHINFO_EXTENSION));
                                                    @endphp

                                                    @if($ext == 'pdf')
                                                    <i class="fe fe-file-text text-danger"></i>

                                                    @elseif($ext == 'docx')
                                                    <i class="fe fe-file text-info"></i>

                                                    @elseif(in_array($ext,['jpg','jpeg','png']))
                                                    <i class="fe fe-image text-success"></i>
                                                    @endif
                                                    <a href="{{ asset('storage/'.$record->file_path) }}" class="text-black">
                                                        {{ $record->name }}
                                                    </a>
                                                </td>

                                                <!-- Folder -->
                                                <td>{{ $record->folder }}</td>

                                                <!-- Share With -->
                                                <td>{{ $record->shareWith->name ?? 'All' }}</td>

                                                <!-- Expiry Date -->
                                                <td>
                                                    @if($record->expiry_date)
                                                    {{ \Carbon\Carbon::parse($record->expiry_date)->format('d-m-Y') }}
                                                    @else
                                                    <span class="text-muted">No Expiry</span>
                                                    @endif
                                                </td>

                                                <!-- Actions -->
                                                <td>

                                                    <a href="{{ asset('storage/'.$record->file_path) }}"
                                                        class="btn btn-sm btn-primary"
                                                        target="_blank">

                                                        <i class="fe fe-eye"></i>
                                                    </a>

                                                    <a href="{{ asset('storage/'.$record->file_path) }}"
                                                        class="btn btn-sm btn-success"
                                                        download>

                                                        <i class="fe fe-download"></i>
                                                    </a>

                                                    <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $record->id }}">
                                                        <i class="fe fe-trash"></i>
                                                    </button>

                                                </td>

                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div>
                                
                                </div>
                                @endif
                            </div>
                            <div class="tab-pane" id="tab7" role="tabpanel">
                                @if($hr->isEmpty())
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <img src="{{ asset('assets/images/no-data.png') }}">
                                    </div>
                                </div>
                                <h5 class="text-center mt-2">No HR Files added</h5>
                                <div class="row">
                                    <div class="col-md-12 text-center mt-2">
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#largemodal" data-type="HR"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="white" viewBox="0 0 16 16">
                                                <path d="M8 1a.5.5 0 0 1 .5.5V7.5H14.5a.5.5 0 0 1 0 1H8.5V14.5a.5.5 0 0 1-1 0V8.5H1.5a.5.5 0 0 1 0-1H7.5V1.5A.5.5 0 0 1 8 1z" />
                                            </svg> Add HR File</button>
                                    </div>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col-md-12 text-end mt-2">
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#largemodal" data-type="HR"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="white" viewBox="0 0 16 16">
                                                <path d="M8 1a.5.5 0 0 1 .5.5V7.5H14.5a.5.5 0 0 1 0 1H8.5V14.5a.5.5 0 0 1-1 0V8.5H1.5a.5.5 0 0 1 0-1H7.5V1.5A.5.5 0 0 1 8 1z" />
                                            </svg> Add HR File</button>
                                    </div>
                                </div>
                                <div class="table-responsive mt-4">
                                    <table class="table table-modern border-0 datatable-basic" id="hr-files-table">
                                        <thead>
                                            <tr>
                                                <th>Sl.No.</th>
                                                <th>Name</th>
                                                <th>Folder</th>
                                                <th>Share With</th>
                                                <th>Expiry Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($hr as $key => $record)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>

                                                <!-- File Name -->
                                                <td>
                                                    @php
                                                    $ext = strtolower(pathinfo($record->file_path, PATHINFO_EXTENSION));
                                                    @endphp

                                                    @if($ext == 'pdf')
                                                    <i class="fe fe-file-text text-danger"></i>

                                                    @elseif($ext == 'docx')
                                                    <i class="fe fe-file text-info"></i>

                                                    @elseif(in_array($ext,['jpg','jpeg','png']))
                                                    <i class="fe fe-image text-success"></i>
                                                    @endif
                                                    <a href="{{ asset('storage/'.$record->file_path) }}" class="text-black">
                                                        {{ $record->name }}
                                                    </a>
                                                </td>

                                                <!-- Folder -->
                                                <td>{{ $record->folder }}</td>

                                                <!-- Share With -->
                                                <td>{{ $record->shareWith->name ?? 'All' }}</td>

                                                <!-- Expiry Date -->
                                                <td>
                                                    @if($record->expiry_date)
                                                    {{ \Carbon\Carbon::parse($record->expiry_date)->format('d-m-Y') }}
                                                    @else
                                                    <span class="text-muted">No Expiry</span>
                                                    @endif
                                                </td>

                                                <!-- Actions -->
                                                <td>

                                                    <a href="{{ asset('storage/'.$record->file_path) }}"
                                                        class="btn btn-sm btn-primary"
                                                        target="_blank">

                                                        <i class="fe fe-eye"></i>
                                                    </a>

                                                    <a href="{{ asset('storage/'.$record->file_path) }}"
                                                        class="btn btn-sm btn-success"
                                                        download>

                                                        <i class="fe fe-download"></i>
                                                    </a>

                                                    <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $record->id }}">
                                                        <i class="fe fe-trash"></i>
                                                    </button>

                                                </td>

                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div>
                                
                                </div>
                                @endif
                            </div>
                            <div class="tab-pane" id="tab8" role="tabpanel">
                                @if($others->isEmpty())
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <img src="{{ asset('assets/images/no-data.png') }}">
                                    </div>
                                </div>
                                <h5 class="text-center mt-2">No Files added</h5>
                                <div class="row">
                                    <div class="col-md-12 text-center mt-2">
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#largemodal" data-type="others"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="white" viewBox="0 0 16 16">
                                                <path d="M8 1a.5.5 0 0 1 .5.5V7.5H14.5a.5.5 0 0 1 0 1H8.5V14.5a.5.5 0 0 1-1 0V8.5H1.5a.5.5 0 0 1 0-1H7.5V1.5A.5.5 0 0 1 8 1z" />
                                            </svg> Add File</button>
                                    </div>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col-md-12 text-end mt-2">
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#largemodal" data-type="others"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="white" viewBox="0 0 16 16">
                                                <path d="M8 1a.5.5 0 0 1 .5.5V7.5H14.5a.5.5 0 0 1 0 1H8.5V14.5a.5.5 0 0 1-1 0V8.5H1.5a.5.5 0 0 1 0-1H7.5V1.5A.5.5 0 0 1 8 1z" />
                                            </svg> Add File</button>
                                    </div>
                                </div>
                                <div class="table-responsive mt-4">
                                    <table class="table table-modern border-0 datatable-basic" id="others-table">
                                        <thead>
                                            <tr>
                                                <th>Sl.No.</th>
                                                <th>Name</th>
                                                <th>Folder</th>
                                                <th>Share With</th>
                                                <th>Expiry Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($others as $key => $record)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>

                                                <!-- File Name -->
                                                <td>
                                                    @php
                                                    $ext = strtolower(pathinfo($record->file_path, PATHINFO_EXTENSION));
                                                    @endphp

                                                    @if($ext == 'pdf')
                                                    <i class="fe fe-file-text text-danger"></i>

                                                    @elseif($ext == 'docx')
                                                    <i class="fe fe-file text-info"></i>

                                                    @elseif(in_array($ext,['jpg','jpeg','png']))
                                                    <i class="fe fe-image text-success"></i>
                                                    @endif
                                                    <a href="{{ asset('storage/'.$record->file_path) }}" class="text-black">
                                                        {{ $record->name }}
                                                    </a>
                                                </td>

                                                <!-- Folder -->
                                                <td>{{ $record->folder }}</td>

                                                <!-- Share With -->
                                                <td>{{ $record->shareWith->name ?? 'All' }}</td>

                                                <!-- Expiry Date -->
                                                <td>
                                                    @if($record->expiry_date)
                                                    {{ \Carbon\Carbon::parse($record->expiry_date)->format('d-m-Y') }}
                                                    @else
                                                    <span class="text-muted">No Expiry</span>
                                                    @endif
                                                </td>

                                                <!-- Actions -->
                                                <td>

                                                    <a href="{{ asset('storage/'.$record->file_path) }}"
                                                        class="btn btn-sm btn-primary"
                                                        target="_blank">

                                                        <i class="fe fe-eye"></i>
                                                    </a>

                                                    <a href="{{ asset('storage/'.$record->file_path) }}"
                                                        class="btn btn-sm btn-success"
                                                        download>

                                                        <i class="fe fe-download"></i>
                                                    </a>

                                                    <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $record->id }}">
                                                        <i class="fe fe-trash"></i>
                                                    </button>

                                                </td>

                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div>
                                
                                </div>
                                @endif
                            </div>
                            <div class="tab-pane" id="tab9" role="tabpanel">
                                @if($folders->isEmpty())
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <img src="{{ asset('assets/images/no-data.png') }}">
                                    </div>
                                </div>
                                <h5 class="text-center mt-2">No Folders Found</h5>
                                @else
                                <div class="row mt-4 px-3">
                                    @foreach($folders as $folder)
                                    <div class="col-md-3 col-sm-6 mb-4">
                                        <div class="folder-grid-item d-flex align-items-center gap-3" onclick="openFolder('{{ $folder }}')">
                                            <div class="folder-icon-large mb-0">
                                                <i class="fa fa-folder"></i>
                                            </div>
                                            <div class="text-start">
                                                <h6 class="folder-name mb-0 fw-bold">{{ ucfirst($folder) }}</h6>
                                                <p class="text-muted small mb-0">Shared Folder</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            <div class="tab-pane" id="tab10" role="tabpanel">
                                @if($employees->isEmpty())
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <img src="{{ asset('assets/images/no-data.png') }}">
                                    </div>
                                </div>
                                <h5 class="text-center mt-2">No records found</h5>
                                <div class="row">
                                    <div class="col-md-12 text-center mt-2">
                                        <a href="{{ route('employees.create') }}" class="btn btn-primary ms-2"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="white" viewBox="0 0 16 16">
                                                <path d="M8 1a.5.5 0 0 1 .5.5V7.5H14.5a.5.5 0 0 1 0 1H8.5V14.5a.5.5 0 0 1-1 0V8.5H1.5a.5.5 0 0 1 0-1H7.5V1.5A.5.5 0 0 1 8 1z" />
                                            </svg> Add Employee</a>
                                    </div>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col-md-12 text-end mt-2">
                                        <a href="{{ route('employees.create') }}" class="btn btn-primary ms-2"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="white" viewBox="0 0 16 16">
                                                <path d="M8 1a.5.5 0 0 1 .5.5V7.5H14.5a.5.5 0 0 1 0 1H8.5V14.5a.5.5 0 0 1-1 0V8.5H1.5a.5.5 0 0 1 0-1H7.5V1.5A.5.5 0 0 1 8 1z" />
                                            </svg> Add Employee</a>
                                    </div>
                                </div>
                                <div class="table-responsive mt-4">
                                    <table class="table table-modern border-0 datatable-basic" id="employee-table">
                                        <thead>
                                            <tr>
                                                <th class="wd-15p border-bottom-0">Sl.No.</th>
                                                <th class="wd-15p border-bottom-0">Name</th>
                                                <th class="wd-15p border-bottom-0">Designation</th>
                                                <th class="wd-20p border-bottom-0">Department</th>
                                                <th class="wd-15p border-bottom-0">Company</th>
                                                <th class="wd-10p border-bottom-0">Status</th>
                                                <th class="wd-25p border-bottom-0">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($employees as $key => $employee)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $employee->name }}</td>
                                                <td>{{ $employee->designation }}</td>
                                                <td>{{ $employee->department->name ?? '' }}</td>
                                                <td>{{ $employee->company->name ?? '' }}</td>
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
                                                        @if($employee->status == 'active')
                                                        <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" onsubmit="confirmDelete(event)">
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
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div>
                                
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- { Modal } -->
    <div class="modal fade" id="largemodal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Files</h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- <form> -->
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Upload file</label>
                            <p>Upload important organization-wide files such as policies or company handbooks.</p>
                            <div class="ff_fileupload_dropzone_wrap">
                                <form action="/upload-temp-document"
                                    class="dropzone"
                                    id="documentDropzone"
                                    method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="dz-message">
                                        <i class="fe fe-upload-cloud" style="font-size:40px;"></i>
                                        <p>Drag & Drop files here or click to upload</p>
                                    </div>
                                    <input type="hidden" name="file_path" id="file_path">
                                </form>
                            </div>
                            <span>All standard document file types such as .pdf .docx .xls can be uploaded with a maximum file size of 10 MB</span>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">File Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" value="" placeholder="File name">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="2"></textarea>
                        </div>
                        <div class="col-md-12 mb-3 d-none" id="agreementDropdown">
                            <label class="form-label">Party</label>
                            <div class="select-wrapper">
                                <select class="form-control @error('party_id') is-invalid @enderror"
                                    name="party_id"
                                    id="agreementSelect">
                                    <option value="">Select Party</option>
                                    @foreach($parties as $party)
                                    <option value="{{ $party->id }}">{{ $party->name }}</option>
                                    @endforeach
                                    <option value="__party__" id="addPartyOption" class="text-center" style="background:#0D9C1E;color:#fff;">+ Add Party</option>
                                </select>

                                @error('party_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Share with <span class="text-danger">*</span></label>
                            <div class="select-wrapper">
                                <select class="form-control @error('share_with') is-invalid @enderror" name="share_with">
                                    <option value="">Share with</option>
                                    @foreach($share_with as $share)
                                    <option value="{{ $share->id }}">{{ $share->name }}</option>
                                    @endforeach
                                </select>
                                @error('share_with') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Folders <span class="text-danger">*</span></label>
                                <div class="select-wrapper">
                                    <select class="form-control @error('company_id') is-invalid @enderror" name="folder" id="folderSelect">
                                        <option value="">Select Folder</option>
                                        @foreach($folders as $folder)
                                        <option value="{{ $folder }}">{{ $folder }}</option>
                                        @endforeach
                                        <option value="__new__" id="addFolderOption" class="text-center" style="background:#0D9C1E;color:#fff;">+ Add Folder</option>
                                    </select>
                                    @error('folder') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">File Expiry Date</label>
                                <input type="text" class="form-control datepicker" name="expiry_date" value="" placeholder="Select date">
                                <input type="hidden" name="type" id="type">
                            </div>
                        </div>
                        <!-- </form> -->
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="submitForm()">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="createFolderModal">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Create Folder</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="text" id="newFolderName" class="form-control" placeholder="Enter folder name">
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-default" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" id="saveFolderBtn">Create</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createPartyModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Add Party</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <form id="partyForm">

                        <div class="mb-3">
                            <label class="form-label">Party Name</label>
                            <input type="text" class="form-control" name="name">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Company Name</label>
                            <input type="text" class="form-control" name="company_name">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Contact Person</label>
                            <input type="text" class="form-control" name="contact_person">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address"></textarea>
                        </div>

                    </form>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-default" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary" id="savePartyBtn">Save</button>
                </div>

            </div>
        </div>
    </div>

    @endsection

    @section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        Dropzone.autoDiscover = false;

        var myDropzone = new Dropzone("#documentDropzone", {

            url: "{{ route('documents.upload') }}",

            maxFilesize: 10,

            acceptedFiles: ".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png",

            success: function(file, response) {

                document.getElementById('file_path').value = response.path;

            }

        });

        var modal = document.getElementById('largemodal');

        modal.addEventListener('show.bs.modal', function(event) {

            var button = event.relatedTarget;

            var type = button.getAttribute('data-type');

            modal.querySelector('#type').value = type;

            if (type === 'agreement') {
                document.getElementById('agreementDropdown').classList.remove('d-none');
            } else {
                document.getElementById('agreementDropdown').classList.add('d-none');
            }

        });

        function submitForm() {

            axios.post('{{ route("documents.store") }}', {

                name: document.querySelector('[name="name"]').value,

                description: document.querySelector('[name="description"]').value,

                folder: document.querySelector('[name="folder"]').value,

                share_with: document.querySelector('[name="share_with"]').value,

                party_id: document.querySelector('[name="party_id"]').value,

                expiry_date: document.querySelector('[name="expiry_date"]').value,

                file_path: document.getElementById('file_path').value,

                type: document.querySelector('[name="type"]').value,

            }).then(response => {

                let modalElement = document.getElementById('largemodal');
                let modal = bootstrap.Modal.getInstance(modalElement);

                modal.hide();

                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Organization added successfully',
                    showConfirmButton: false,
                    timer: 1500
                });

                location.reload();

            });

        }
        $('#folderSelect').on('change', function() {

            if ($(this).val() === '__new__') {

                let modal = new bootstrap.Modal(document.getElementById('createFolderModal'));
                modal.show();

            }

        });

        $('#saveFolderBtn').click(function() {

            let folderName = $('#newFolderName').val();

            if (!folderName) {
                alert("Folder name is required");
                return;
            }

            let newOption = `<option value="${folderName}">${folderName}</option>`;

            $('#addFolderOption').before(newOption);

            $('#folderSelect').val(folderName).trigger('change');

            $('#newFolderName').val('');

            bootstrap.Modal.getInstance(document.getElementById('createFolderModal')).hide();

        });

        $('#createFolderModal').on('hidden.bs.modal', function() {

            if ($('#folderSelect').val() === '__new__') {
                $('#folderSelect').val('');
            }

        });




        $('#agreementSelect').on('change', function() {

            if ($(this).val() === '__party__') {

                let modal = new bootstrap.Modal(document.getElementById('createPartyModal'));
                modal.show();

            }

        });

        $('#savePartyBtn').click(function() {

            let formData = new FormData(document.getElementById('partyForm'));

            axios.post("{{ route('parties.store') }}", formData)
                .then(function(response) {

                    Swal.fire(
                        "Success",
                        response.data.message,
                        "success"
                    );
                    let party = response.data.data;

                    let newOption = `<option value="${party.id}">${party.name}</option>`;

                    $('#addPartyOption').before(newOption);

                    $('#agreementSelect').val(party.id).trigger('change');

                    bootstrap.Modal.getInstance(document.getElementById('createPartyModal')).hide();

                    document.getElementById('partyForm').reset();
                })
                .catch(function(error) {

                    if (error.response.status === 422) {
                        console.log(error.response.data.errors);
                    }

                });


        });

        $('#createPartyModal').on('hidden.bs.modal', function() {

            if ($('#agreementSelect').val() === '__party__') {
                $('#agreementSelect').val('');
            }

        });
    </script>
    <script>
        function confirmDelete(event) {

            event.preventDefault();

            Swal.fire({
                title: "Are you sure?",
                text: "This employee will be deleted!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#eee",
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
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: message,
                            showConfirmButton: false,
                            timer: 1500
                        });

                        setTimeout(function() {
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
    <script>
        $(document).on('click', '.delete-btn', function() {

            let id = $(this).data('id');

            Swal.fire({
                title: "Are you sure?",
                text: "This document will be deleted!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "Cancel",
                confirmButtonColor: "#d33",
                cancelButtonColor: "#e9e9f1",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {

                if (result.isConfirmed) {
                    let url = "{{ route('documents.delete', ':id') }}";
                    url = url.replace(':id', id);
                    axios.post(url, {
                            _token: "{{ csrf_token() }}"
                        })
                        .then(function(response) {

                            Swal.fire(
                                "Success",
                                "Record deleted successfully.",
                                "success"
                            ).then(() => {
                                location.reload();
                            });

                        })
                        .catch(function(error) {

                            Swal.fire(
                                "Error!",
                                "Something went wrong.",
                                "error"
                            );

                        });

                }

            });

        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Main Attendance Chart (Last 7 Days - from current view data)
            const mainCtx = document.getElementById('mainAttendanceChart').getContext('2d');
            const weeklyLabels = @json($weeklyLabels);
            const weeklyData = @json($weeklyData);

            new Chart(mainCtx, {
                type: 'line',
                data: {
                    labels: weeklyLabels,
                    datasets: [{
                        label: 'Present Employees',
                        data: weeklyData,
                        borderColor: '#2ecc71',
                        backgroundColor: 'rgba(111, 247, 172, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointBackgroundColor: '#b2f3c2',
                        pointBorderColor: '#1a9e52',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { backgroundColor: '#1e293b', padding: 12, displayColors: false }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#f1f5f9', drawBorder: false }, ticks: { color: '#64748b' } },
                        x: { grid: { display: false, drawBorder: false }, ticks: { color: '#64748b' } }
                    }
                }
            });

            // Punch Activity Chart (Bar Chart: Today vs Yesterday)
            const distCtx = document.getElementById('punchDistributionChart').getContext('2d');
            new Chart(distCtx, {
                type: 'bar',
                data: {
                    labels: ['Today', 'Yesterday'],
                    datasets: [
                        {
                            label: 'Punched In',
                            data: [{{ $todayStats['punched_in'] }}, {{ $yesterdayStats['punched_in'] }}],
                            backgroundColor: '#22c55e',
                            borderRadius: 6,
                            barThickness: 20
                        },
                        {
                            label: 'Punched Out',
                            data: [{{ $todayStats['punched_out'] }}, {{ $yesterdayStats['punched_out'] }}],
                            backgroundColor: '#ef4444',
                            borderRadius: 6,
                            barThickness: 20
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { display: true, position: 'bottom', labels: { boxWidth: 12, usePointStyle: true, pointStyle: 'circle' } } 
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { display: false }, ticks: { display: false } },
                        x: { grid: { display: false } }
                    }
                }
            });

            

            // Mini Chart for Punch-In
            const miniCtx = document.getElementById('miniChartIn').getContext('2d');
            new Chart(miniCtx, {
                type: 'line',
                data: {
                    labels: ['', '', '', '', '', ''],
                    datasets: [{
                        data: [2, 4, 3, 5, 4, 6],
                        borderColor: '#6366f1',
                        borderWidth: 2,
                        fill: false,
                        pointRadius: 0,
                        tension: 0.4
                    }]
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: { x: { display: false }, y: { display: false } }
                }
            });

        });
    </script>
    </script>
    @endsection