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
                        <i class="fe fe-users fs-30"></i>
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


@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetchStats();
        // checkNotifications();
        
        // Poll for notifications every 30 seconds
        // setInterval(checkNotifications, 30000);
    });

    // function checkNotifications() {
    //     axios.get('{{ route("dashboard.notifications") }}')
    //         .then(response => {
    //             const notifications = response.data;
    //             notifications.forEach(notif => {
    //                 Swal.fire({
    //                     title: 'Late Alert!',
    //                     text: notif.data.message,
    //                     icon: 'warning',
    //                     confirmButtonText: 'View Details',
    //                     footer: `<a href="/employees/${notif.data.employee_id}">Go to Employee Profile</a>`
    //                 });
    //             });
    //         })
    //         .catch(error => console.error('Error fetching notifications:', error));
    // }

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

   

</script>
@endsection
