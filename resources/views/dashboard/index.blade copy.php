@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <h1 class="page-title">Dashboard</h1>
    <div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol>
    </div>
</div>

<!-- Summary Cards -->
<div class="row">
    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex">
                    <div class="mt-2">
                        <h6 class="">Total Employees</h6>
                        <h2 class="mb-0 number-font" id="stat-total">...</h2>
                    </div>
                    <div class="ms-auto">
                        <div class="chart-circle chart-circle-xs ms-auto" data-value="0.62" data-thickness="3" data-color="#05dddf">
                            <div class="chart-circle-value text-primary"><i class="fe fe-users"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex">
                    <div class="mt-2">
                        <h6 class="">Active Employees</h6>
                        <h2 class="mb-0 number-font text-success" id="stat-active">...</h2>
                    </div>
                    <div class="ms-auto">
                        <i class="fe fe-user-check fs-30 text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex">
                    <div class="mt-2">
                        <h6 class="">Punched In (Today)</h6>
                        <h2 class="mb-0 number-font text-primary" id="stat-punched-in">...</h2>
                    </div>
                    <div class="ms-auto">
                        <i class="fe fe-log-in fs-30 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex">
                    <div class="mt-2">
                        <h6 class="">Late (Today)</h6>
                        <h2 class="mb-0 number-font text-warning" id="stat-late">...</h2>
                    </div>
                    <div class="ms-auto">
                        <i class="fe fe-clock fs-30 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex">
                    <div class="mt-2">
                        <h6>Absent (Today)</h6>
                        <h2 class="mb-0 number-font text-danger" id="stat-absent">...</h2>
                    </div>
                    <div class="ms-auto">
                        <i class="fe fe-user-x fs-30 text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex">
                    <div class="mt-2">
                        <h6>Punched Out (Today)</h6>
                        <h2 class="mb-0 number-font text-info" id="stat-punched-out">...</h2>
                    </div>
                    <div class="ms-auto">
                        <i class="fe fe-log-out fs-30 text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex">
                    <div class="mt-2">
                        <h6>Inactive Employees</h6>
                        <h2 class="mb-0 number-font text-muted" id="stat-inactive">...</h2>
                    </div>
                    <div class="ms-auto">
                        <i class="fe fe-slash fs-30 text-muted"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex">
                    <div class="mt-2">
                        <h6>Not Punched In</h6>
                        <h2 class="mb-0 number-font text-secondary" id="stat-not-punched">...</h2>
                    </div>
                    <div class="ms-auto">
                        <i class="fe fe-alert-circle fs-30 text-secondary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row">
    <div class="col-lg-8 col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Monthly Attendance Trend</h3>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height:300px;">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Department Distribution</h3>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height:300px;">
                    <canvas id="deptChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Late Employees Trend (Last 30 Days)</h3>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height:300px;">
                    <canvas id="lateTrendChart"></canvas>
                </div>
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
    document.addEventListener('DOMContentLoaded', function() {
        fetchStats();
        fetchChartData();
        checkNotifications();
        
        // Poll for notifications every 30 seconds
        setInterval(checkNotifications, 30000);
    });

    function checkNotifications() {
        axios.get('{{ route("dashboard.notifications") }}')
            .then(response => {
                const notifications = response.data;
                notifications.forEach(notif => {
                    Swal.fire({
                        title: 'Late Alert!',
                        text: notif.data.message,
                        icon: 'warning',
                        confirmButtonText: 'View Details',
                        footer: `<a href="/employees/${notif.data.employee_id}">Go to Employee Profile</a>`
                    });
                });
            })
            .catch(error => console.error('Error fetching notifications:', error));
    }

    function fetchStats() {
        axios.get('{{ route("dashboard.stats") }}')
            .then(response => {
                const data = response.data;
                document.getElementById('stat-total').innerText = data.total;
                document.getElementById('stat-active').innerText = data.active;
                document.getElementById('stat-inactive').innerText = data.inactive;
                document.getElementById('stat-punched-in').innerText = data.punched_in;
                document.getElementById('stat-punched-out').innerText = data.punched_out;
                document.getElementById('stat-late').innerText = data.late;
                document.getElementById('stat-absent').innerText = data.absent;
                document.getElementById('stat-not-punched').innerText = data.not_punched_in;
            })
            .catch(error => console.error('Error fetching stats:', error));
    }

    function fetchChartData() {
        axios.get('{{ route("dashboard.charts") }}')
            .then(response => {
                const data = response.data;
                initMonthlyChart(data.monthly);
                initDeptChart(data.dept);
                initLateTrendChart(data.late);
            })
            .catch(error => console.error('Error fetching chart data:', error));
    }

    function initMonthlyChart(chartData) {
        const ctx = document.getElementById('monthlyChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Attendance Count',
                    data: chartData.data,
                    backgroundColor: 'rgba(5, 221, 223, 0.5)',
                    borderColor: 'rgba(5, 221, 223, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    function initDeptChart(chartData) {
        const ctx = document.getElementById('deptChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: chartData.labels,
                datasets: [{
                    data: chartData.data,
                    backgroundColor: [
                        '#05dddf', '#f1c40f', '#e74c3c', '#2ecc71', '#9b59b6', '#34495e'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }

    function initLateTrendChart(chartData) {
        const ctx = document.getElementById('lateTrendChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Late Count',
                    data: chartData.data,
                    borderColor: '#f1c40f',
                    backgroundColor: 'rgba(241, 196, 15, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }
</script>
@endsection
