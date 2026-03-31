<!doctype html>
<html lang="en" dir="ltr">

<head>
    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Attendance Application">
    <meta name="author" content="Techne Infosys">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/brand/favicon.ico') }}">

    <!-- TITLE -->
    <title>HR - @yield('title')</title>

    <!-- BOOTSTRAP CSS -->
    <link id="style" href="{{ asset('assets/css/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <style>
        /* DataTables Modern Styling (Global) */
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 6px 12px;
            margin-left: 10px;
            outline: none;
            transition: all 0.2s;
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #6366f1 !important;
            border-color: #6366f1 !important;
            color: white !important;
            border-radius: 6px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #f5f3ff !important;
            border-color: #6366f1 !important;
            color: #6366f1 !important;
        }

        table.dataTable thead th {
            border-bottom: 1px solid #f1f5f9 !important;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
        }

        .dataTables_info {
            font-size: 13px;
            color: #64748b;
            margin-top: 15px;
        }

        .dataTables_paginate {
            margin-top: 15px;
        }

        /* Password Eye Icon Styles */
        .password-toggle-wrapper {
            position: relative;
        }

        .password-toggle-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #94a3b8;
            z-index: 10;
            transition: color 0.2s;
        }

        .password-toggle-icon:hover {
            color: #6366f1;
        }
    </style>


    <!-- STYLE CSS -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/layout_modern.css') }}" rel="stylesheet">

    <!-- Plugins CSS -->
    <link href="{{ asset('assets/css/plugins.css') }}" rel="stylesheet">

    <!--- FONT-ICONS CSS -->
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
    @yield('styles')
    @stack('styles')
</head>

<body class="app sidebar-mini ltr light-mode">

    <!-- GLOBAL-LOADER -->
    <div id="global-loader">
        <img src="{{ asset('assets/images/loader.svg') }}" class="loader-img" alt="Loader">
    </div>

    <!-- PAGE -->
    <div class="page">
        <div class="page-main">

            <!-- app-header -->
            <div class="app-header header sticky">
                <div class="container-fluid main-container">
                    <div class="d-flex">
                        @auth
                            <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-bs-toggle="sidebar"
                                href="javascript:void(0)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="feather feather-grid status_toggle middle sidebar-toggle">
                                    <rect x="3" y="3" width="7" height="7"></rect>
                                    <rect x="14" y="3" width="7" height="7"></rect>
                                    <rect x="14" y="14" width="7" height="7"></rect>
                                    <rect x="3" y="14" width="7" height="7"></rect>
                                </svg>
                            </a>
                        @else
                            <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-bs-toggle="sidebar"
                                href="javascript:void(0)" style="border:none!important;">
                            </a>
                        @endauth
                        <!-- LOGO -->
                        <a class="logo-horizontal "
                            href="{{ Auth::check() ? route('attendance.index') : route('login') }}">
                            <img src="{{ asset('assets/images/hr-logo2.jpg') }}" class="header-brand-img desktop-logo"
                                alt="logo" style="width:50px;">
                            <img src="{{ asset('assets/images/hr-logo2.jpg') }}" class="header-brand-img light-logo1"
                                alt="logo" style="width:50px;">
                        </a>

                        <div class="d-flex order-lg-2 ms-auto header-right-icons">
                            <div class="dropdown  d-flex notifications">
                                <a class="nav-link icon" data-bs-toggle="dropdown"><i class="fe fe-bell"></i>
                                    @if($notifications->count() > 0)
                                        <span class="pulse"></span>
                                    @endif
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <div class="drop-heading border-bottom">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="mt-1 mb-0 fs-16 fw-semibold text-dark">Notifications</h6>
                                            <span
                                                class="badge bg-primary header-badge notification-badge">{{ $notifications->count() }}</span>
                                        </div>
                                    </div>
                                    <div class="notifications-menu vertical-scroll-cards">
                                        @forelse($notifications as $notification)
                                            <div class="d-flex align-items-start dropdown-item"
                                                id="notif-{{ $notification->id }}">
                                                <div class="flex-grow-1">
                                                    <h5 class="mb-0">
                                                        {{ $notification->data['message'] ?? '' }}
                                                    </h5>
                                                    <span class="text-sm text-nowrap">
                                                        {{ $notification->created_at->diffForHumans() }}
                                                    </span>
                                                    <div class="d-flex align-items-end justify-content-between mt-1">
                                                        <a href="javascript:void(0);"
                                                            onclick="markSingleAsRead('{{ $notification->id }}'); event.stopPropagation();"
                                                            class="text-success p-0 text-right">Mark as read</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-center p-3 text-muted">No notifications</p>
                                        @endforelse
                                    </div>
                                    <div class="dropdown-divider m-0"></div>
                                    <a href="#" class="dropdown-item text-center p-3 text-muted">View all
                                        Notification</a>
                                </div>
                            </div>
                            @auth
                                <div class="dropdown d-flex profile-1">
                                    <a href="javascript:void(0)" data-bs-toggle="dropdown" data-bs-offset="0,10"
                                        class="nav-link user-dropdown gap-2 d-flex align-items-center">
                                        <div class="avatar-container">
                                            <img src="{{ Auth::user()->avatar_url }}"
                                                onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=ffff&background=2ecc71'"
                                                alt="profile-user" class="profile-user avatar cover-image">
                                        </div>
                                        <div class="media-body d-lg-block d-none ps-1">
                                            <p class="mb-0 fw-semibold text-dark">{{ Auth::user()->name ?? 'Admin' }}</p>
                                            <small class="text-muted d-block"
                                                style="font-size: 10px; margin-top: -4px;">Administrator</small>
                                        </div>
                                        <i class="fe fe-chevron-down ms-1 text-muted fs-12"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end border-0 shadow-lg">
                                        <div class="drop-heading p-3 border-bottom bg-light rounded-top">
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="{{ Auth::user()->avatar_url }}"
                                                    onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=ffff&background=2ecc71'"
                                                    alt="profile-user" class="avatar avatar-md rounded-3">
                                                <div class="overflow-hidden">
                                                    <h6 class="text-dark fw-bold mb-0 text-truncate">
                                                        {{ Auth::user()->name ?? 'Admin' }}
                                                    </h6>
                                                    <p class="text-muted small mb-0 text-truncate">{{ Auth::user()->email }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="">
                                            <a class="dropdown-item rounded-3 mb-1" href="{{ route('profile.edit') }}">
                                                <i class="fe fe-user me-2 text-brand"></i>
                                                <span>My Profile</span>
                                            </a>
                                            <form action="{{ route('logout') }}" method="POST" id="logout-form"
                                                class="d-none">
                                                @csrf
                                            </form>
                                            <a class="dropdown-item rounded-3 logout-item" href="javascript:void(0)"
                                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                <i class="fe fe-log-out me-2"></i>
                                                <span class="fw-semibold">Sign out</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="nav-item">
                                    <!-- <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Login</a> -->
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
            <!-- /app-header -->

            <!--APP-SIDEBAR-->
            @auth
                <div class="sticky">
                    <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
                    <div class="app-sidebar">
                        <div class="side-header">
                            <a class="header-brand1" href="{{ route('attendance.index') }}">
                                <img src="{{ asset('assets/images/hr-logo2.jpg') }}" class="header-brand-img desktop-logo"
                                    alt="logo" style="width:50px;">
                                <img src="{{ asset('assets/images/hr-logo2.jpg') }}" class="header-brand-img toggle-logo"
                                    alt="logo" style="width:50px;">
                                <img src="{{ asset('assets/images/hr-logo2.jpg') }}" class="header-brand-img light-logo"
                                    alt="logo" style="width:50px;">
                                <img src="{{ asset('assets/images/hr-logo2.jpg') }}" class="header-brand-img light-logo1"
                                    alt="logo" style="width:50px;">
                            </a>
                        </div>
                        <div class="main-sidemenu">
                            <div class="slide-left disabled d-none" id="slide-left">

                            </div>
                            <ul class="side-menu mt-4">
                                <li class="slide">
                                    <a class="sidenav-menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                                        href="{{ route('dashboard') }}">
                                        <i class="side-menu__icon fe fe-grid"></i>
                                        <span class="side-menu__label">Dashboard</span>
                                    </a>
                                </li>
                                <li class="slide">
                                    <a class="sidenav-menu-item {{ request()->routeIs('employees.*') ? 'active' : '' }}"
                                        href="{{ route('employees.index') }}">
                                        <i class="side-menu__icon fe fe-users"></i>
                                        <span class="side-menu__label">Employees</span>
                                    </a>
                                </li>
                                <li class="slide">
                                    <a class="sidenav-menu-item {{ request()->routeIs('organizations.index') ? 'active' : '' }}"
                                        href="{{ route('organizations.index') }}">
                                        <i class="side-menu__icon fe fe-briefcase"></i>
                                        <span class="side-menu__label">Organizations</span>
                                    </a>
                                </li>
                                <li class="slide">
                                    <a class="sidenav-menu-item {{ request()->routeIs('agreements.index') ? 'active' : '' }}"
                                        href="{{ route('agreements.index') }}">
                                        <i class="side-menu__icon fe fe-file-text"></i>
                                        <span class="side-menu__label">Agreements</span>
                                    </a>
                                </li>
                                <li class="slide">
                                    <a class="sidenav-menu-item {{ request()->routeIs('attendance.index') || request()->routeIs('attendance.upload') ? 'active' : '' }}"
                                        href="{{ route('attendance.index') }}">
                                        <i class="side-menu__icon fe fe-calendar"></i>
                                        <span class="side-menu__label">Attendance</span>
                                    </a>
                                </li>
                                <li class="slide">
                                    <a class="sidenav-menu-item {{ request()->routeIs('leaves.index') ? 'active' : '' }}"
                                        href="{{ route('leaves.index') }}">
                                        <i class="side-menu__icon fe fe-user-x"></i>
                                        <span class="side-menu__label">Leaves</span>
                                    </a>
                                </li>
                                <li class="slide">
                                    <a class="sidenav-menu-item {{ request()->routeIs('designations.*') ? 'active' : '' }}"
                                        href="{{ route('designations.index') }}">
                                        <i class="side-menu__icon fe fe-tag"></i>
                                        <span class="side-menu__label">Designations</span>
                                    </a>
                                </li>
                                <li class="slide">
                                    <a class="sidenav-menu-item {{ request()->routeIs('wfh_requests.*') ? 'active' : '' }}"
                                        href="{{ route('wfh_requests.index') }}">
                                        <i class="side-menu__icon fe fe-home"></i>
                                        <span class="side-menu__label">WFH Requests</span>
                                    </a>
                                </li>
                                <li class="slide">
                                    <a class="sidenav-menu-item {{ request()->routeIs('task_reports.*') ? 'active' : '' }}"
                                        href="{{ route('task_reports.index') }}">
                                        <i class="side-menu__icon fe fe-clipboard"></i>
                                        <span class="side-menu__label">Task Reports</span>
                                    </a>
                                </li>
                                <li class="slide">
                                    <a class="sidenav-menu-item {{ request()->routeIs('reports.*') ? 'active' : '' }}"
                                        href="#">
                                        <i class="side-menu__icon fe fe-trending-up"></i>
                                        <span class="side-menu__label">Reports</span>
                                    </a>
                                </li>
                                <li class="slide mt-4 pt-4 border-top">
                                    <a class="sidenav-menu-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}"
                                        href="{{ route('profile.edit') }}">
                                        <i class="side-menu__icon fe fe-settings"></i>
                                        <span class="side-menu__label">Settings</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endauth
            <!--/APP-SIDEBAR-->

            <!-- app-content -->
            <div class="main-content app-content mt-0 @guest ps-0 @endguest">
                <div class="side-app">
                    <!-- CONTAINER -->
                    <div class="main-container container-fluid">
                        @if (session('success'))
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success',
                                        text: @json(session('success')),
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                });
                            </script>
                        @endif

                        @if (session('error'))
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: @json(session('error')),
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                });
                            </script>
                        @endif

                        @if ($errors->any())
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    const errorMessages = @json($errors->all());
                                    const errorText = errorMessages.join('\n');

                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        title: errorText,
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                });
                            </script>
                        @endif
                        @yield('content')
                    </div>
                    <!-- CONTAINER END -->
                </div>
            </div>
            <!-- app-content end -->
        </div>

        <!-- FOOTER -->

        <!-- FOOTER END -->
    </div>

    <!-- BACK-TO-TOP -->
    <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

    <!-- JQUERY JS -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>

    <!-- BOOTSTRAP JS -->
    <script src="{{ asset('assets/js/plugins/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

    <!-- SIDE-MENU JS -->
    <script src="https://cdn.jsdelivr.net/npm/perfect-scrollbar@1.5.5/dist/perfect-scrollbar.min.js"></script>
    <script src="{{ asset('assets/js/plugins/chart/Chart.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/sidemenu.js') }}"></script>

    <!-- sticky js -->
    <script src="{{ asset('assets/js/sticky.js') }}"></script>

    <!-- CUSTOM JS -->
    <script src="{{ asset('assets/js/circle-progress.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // fetchStats();
            checkNotifications();

            // Poll for notifications every 30 seconds
            setInterval(checkNotifications, 30000);

            // Password Eye Toggle
            $('input[type="password"]').each(function () {
                let $input = $(this);
                if ($input.parent().hasClass('password-toggle-wrapper')) return;

                $input.wrap('<div class="password-toggle-wrapper"></div>');
                let $icon = $('<i class="fa fa-eye-slash password-toggle-icon"></i>');
                $input.after($icon);

                $icon.on('click', function () {
                    if ($input.attr('type') === 'password') {
                        $input.attr('type', 'text');
                        $icon.removeClass('fa-eye-slash').addClass('fa-eye');
                    } else {
                        $input.attr('type', 'password');
                        $icon.removeClass('fa-eye').addClass('fa-eye-slash');
                    }
                });
            });
        });

        function checkNotifications() {
            axios.get('{{ route("dashboard.notifications") }}')
                .then(response => {
                    const notifications = response.data;

                    if (notifications.length > 0) {

                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                        });

                        Toast.fire({
                            iconHtml: '<i class="fa fa-bell" style="color:#f59e0b;font-size:18px;"></i>',
                            title: `You have ${notifications.length} new notifications`,
                            customClass: {
                                icon: 'no-border'
                            }
                        });
                    }
                })
                .catch(error => console.error('Error fetching notifications:', error));
        }

        function markSingleAsRead(id) {
            let url = "{{ route('dashboard.notifications.read', ':id') }}";
            url = url.replace(':id', id);

            axios.post(url)
                .then(response => {

                    const row = document.getElementById('notif-' + id);
                    if (row) {
                        row.remove();

                        let countEl = document.querySelector('.notification-badge');
                        if (countEl) {
                            let count = parseInt(countEl.innerText);
                            countEl.innerText = count - 1;

                            if (count - 1 <= 0) countEl.remove();
                        }
                    }

                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    </script>
    <script>
        $(document).ready(function () {
            // Initialize by class (for multiple tables on one page)
            $('.datatable-basic').each(function () {
                if (!$.fn.DataTable.isDataTable(this)) {
                    $(this).DataTable({
                        pageLength: 10,
                        language: {
                            search: "",
                            searchPlaceholder: "Search records...",
                            paginate: {
                                previous: '<i class="fe fe-chevron-left"></i>',
                                next: '<i class="fe fe-chevron-right"></i>'
                            }
                        },
                        drawCallback: function () {
                            $('.dataTables_paginate > .pagination').addClass('pagination-sm');
                        }
                    });
                }
            });

            // Initialize by ID (for single table pages using legacy ID)
            if ($('#basic-datatable').length && !$.fn.DataTable.isDataTable('#basic-datatable')) {
                $('#basic-datatable').DataTable({
                    pageLength: 10,
                    language: {
                        search: "",
                        searchPlaceholder: "Search records...",
                        paginate: {
                            previous: '<i class="fe fe-chevron-left"></i>',
                            next: '<i class="fe fe-chevron-right"></i>'
                        }
                    },
                    drawCallback: function () {
                        $('.dataTables_paginate > .pagination').addClass('pagination-sm');
                    }
                });
            }
        });
    </script>
    @yield('scripts')
    <script>
        $('.datepicker').datepicker({
            format: "dd-mm-yyyy",
            autoclose: true,
        });
    </script>
</body>

</html>