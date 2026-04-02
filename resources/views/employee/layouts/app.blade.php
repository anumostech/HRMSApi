<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Employee Portal')</title>
    <link id="style" href="{{ asset('assets/css/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/css/bootstrap-datepicker.min.css">
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

    @stack('styles')
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #2ecc71;
            --sidebar-w: 260px;
        }

        .fe {
            line-height: 1.5 !important;
        }

        /* ── Sidebar ── */
        .emp-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: #496249;
            display: flex;
            flex-direction: column;
            z-index: 1000;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15);
        }

        .emp-sidebar-brand {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .emp-sidebar-brand h5 {
            color: white;
            font-weight: 700;
            margin: 0;
            font-size: 1.1rem;
        }

        .emp-sidebar-brand small {
            color: rgba(255, 255, 255, 0.65);
            font-size: 0.78rem;
        }

        .emp-sidebar-nav {
            flex: 1;
            padding: 1rem 0;
        }

        .emp-nav-item {
            padding: 0 0.75rem;
            margin-bottom: 0.25rem;
        }

        .emp-nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.7rem 1rem;
            color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .emp-nav-link:hover,
        .emp-nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
        }

        .emp-nav-link svg {
            flex-shrink: 0;
        }

        .emp-sidebar-footer {
            padding: 1rem 0.75rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* ── Main ── */
        .emp-main {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .emp-topbar {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 0.875rem 1.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
        }

        .emp-topbar-title {
            font-weight: 700;
            color: #1a1a2e;
            font-size: 1.05rem;
            margin: 0;
        }

        .emp-user-badge {
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .emp-user-badge img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e0e7ff;
        }

        .emp-user-badge .emp-user-name {
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
        }

        .emp-user-badge .emp-user-role {
            font-size: 0.75rem;
            color: #9ca3af;
        }

        .emp-content {
            padding: 1.75rem;
            flex: 1;
        }

        /* Cards */
        .emp-card {
            background: white;
            border-radius: 14px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f0f0f0;
        }

        .emp-card-header {
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .emp-card-header span {
            width: 4px;
            height: 20px;
            background: var(--primary);
            border-radius: 2px;
        }

        /* Alert banners */
        .alert-success-custom {
            background: #f5f3ff;
            border: 1px solid #ddd6fe;
            color: #4338ca;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .emp-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }

            .emp-main {
                margin-left: 0;
            }
        }

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
            color: var(--primary);
        }
    </style>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link href="{{ asset('assets/css/plugins.css') }}" rel="stylesheet">
</head>

<body>

    <!-- Sidebar -->
    <aside class="emp-sidebar">
        <div class="emp-sidebar-brand">
            <div class="d-flex align-items-center gap-2 mb-1 justify-content-center">
                <img src="{{ asset('assets/images/hr-log.png') }}" class="header-brand-img desktop-logo" alt="logo"
                    style="width:50px;">
            </div>
        </div>

        <nav class="emp-sidebar-nav">
            <div class="emp-nav-item">
                <a href="{{ route('employee.dashboard') }}"
                    class="emp-nav-link {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                        viewBox="0 0 16 16">
                        <path d="M8 3.293l5 5V13a1 1 0 0 1-1 1H9v-3H7v3H3a1 1 0 0 1-1-1V8.293l6-5z" />
                    </svg>
                    Dashboard
                </a>
            </div>
            <div class="emp-nav-item">
                <a href="{{ route('employee.wfh.index') }}"
                    class="emp-nav-link {{ request()->routeIs('employee.wfh.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                        viewBox="0 0 16 16">
                        <path
                            d="M3 0a1 1 0 0 0-1 1v1H1a1 1 0 0 0-1 1v1h16V3a1 1 0 0 0-1-1h-1V1a1 1 0 0 0-2 0v1H4V1a1 1 0 0 0-1-1zM0 6v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6H0z" />
                    </svg>
                    WFH Request
                </a>
            </div>
            <div class="emp-nav-item">
                <a href="{{ route('employee.leaves.index') }}"
                    class="emp-nav-link {{ request()->routeIs('employee.leaves.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                        viewBox="0 0 16 16">
                        <path
                            d="M3 0a1 1 0 0 0-1 1v1H1a1 1 0 0 0-1 1v1h16V3a1 1 0 0 0-1-1h-1V1a1 1 0 0 0-2 0v1H4V1a1 1 0 0 0-1-1zM0 6v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6H0z" />
                    </svg>
                    My Leaves
                </a>
            </div>
            <div class="emp-nav-item">
                <a href="{{ route('employee.task_reports.index') }}"
                    class="emp-nav-link {{ request()->routeIs('employee.task_reports.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                        viewBox="0 0 16 16">
                        <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
                        <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
                        <path d="M10.854 7.854a.5.5 0 0 0-.708-.708L7.5 9.793 6.354 8.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0l3-3z"/>
                    </svg>
                    Task Reports
                </a>
            </div>
            <div class="emp-nav-item">
                <a href="{{ route('employee.profile') }}"
                    class="emp-nav-link {{ request()->routeIs('employee.profile') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                        viewBox="0 0 16 16">
                        <path
                            d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm.256 7a4.474 4.474 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10c.26 0 .507.009.74.025a4.548 4.548 0 0 1 .23-.025c-.196.24-.373.497-.527.77H3.5a.5.5 0 0 0-.5.5v.5a3.5 3.5 0 0 0 5.256 3.025z" />
                        <path
                            d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7zm.354-5.854 1.5 1.5a.5.5 0 0 1-.708.708L13 11.707l-.646.647a.5.5 0 0 1-.708-.708l1-1a.5.5 0 0 1 .708 0z" />
                    </svg>
                    My Profile
                </a>
            </div>
        </nav>

        <div class="emp-sidebar-footer">
            <form method="POST" action="{{ route('employee.logout') }}">
                @csrf
                <button type="submit" class="emp-nav-link w-100 border-0 bg-transparent" style="cursor:pointer;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                        viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z" />
                        <path fill-rule="evenodd"
                            d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z" />
                    </svg>
                    Sign Out
                </button>
            </form>
        </div>
    </aside>

    <!-- Main -->
    <div class="emp-main">
        <header class="emp-topbar">
            <h1 class="emp-topbar-title">@yield('page-title', 'Employee Portal')</h1>
            <div class="emp-user-badge">
                <img src="{{ Auth::guard('employee')->user()->avatar_url }}" alt="Avatar">
                <div>
                    <div class="emp-user-name">{{ Auth::guard('employee')->user()->first_name }}</div>
                    <div class="emp-user-role">{{ Auth::guard('employee')->user()->designation?->name ?? 'Employee' }}</div>
                </div>
            </div>
        </header>

        <main class="emp-content">
            @yield('content')
        </main>
    </div>

    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/js/bootstrap-datepicker.min.js"></script>
    <script>
        $('.datepicker').datepicker({
            format: "dd-mm-yyyy",
            autoclose: true,
        });
    </script>
    <script>
        $(function () {
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
    </script>
    @stack('scripts')
</body>

</html>