<!doctype html>
<html lang="en" dir="ltr">
<head>
    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Attendance Application">
    <meta name="author" content="Techne Infosys">

    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/brand/favicon.ico') }}">

    <!-- TITLE -->
    <title>Attendance - @yield('title')</title>

    <!-- BOOTSTRAP CSS -->
    <link id="style" href="{{ asset('assets/css/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- STYLE CSS -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

    <!-- Plugins CSS -->
    <link href="{{ asset('assets/css/plugins.css') }}" rel="stylesheet">

    <!--- FONT-ICONS CSS -->
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">

    @yield('styles')
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
                        <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-bs-toggle="sidebar" href="javascript:void(0)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-grid status_toggle middle sidebar-toggle"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                        </a>
                        @else
                        <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-bs-toggle="sidebar" href="javascript:void(0)" style="border:none!important;">
                        </a>
                            @endauth
                        <!-- LOGO -->
                        <a class="logo-horizontal " href="{{ Auth::check() ? route('attendance.index') : route('login') }}">
                            <img src="{{ asset('assets/images/attendance.webp') }}" class="header-brand-img desktop-logo" alt="logo" style="width:50px;">
                            <img src="{{ asset('assets/images/attendance.webp') }}" class="header-brand-img light-logo1" alt="logo" style="width:50px;">
                        </a>
                        <div class="d-flex order-lg-2 ms-auto header-right-icons">
                            @auth
                            <div class="dropdown d-flex profile-1">
                                <a href="javascript:void(0)" data-bs-toggle="dropdown" class="nav-link user-dropdown">
                                    <img src="{{ Auth::user()->avatar_url }}" alt="profile-user" class="profile-user avatar cover-image">
                                    <div class="media-body d-lg-block d-none box-col-none ps-2">
                                        <p class="mb-0">{{ Auth::user()->name ?? 'Admin' }}</p>
                                    </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <div class="drop-heading">
                                        <div class="text-center">
                                            <h5 class="text-dark mb-0 fs-14 fw-semibold">{{ Auth::user()->name ?? 'Admin' }}</h5>
                                            <small class="text-muted">{{ Auth::user()->email }}</small>
                                        </div>
                                    </div>
                                    <div class="dropdown-divider m-0"></div>
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="dropdown-icon fe fe-user"></i> Profile 
                                    </a>
                                    <div class="dropdown-divider m-0"></div>
                                    <form action="{{ route('logout') }}" method="POST" id="logout-form" class="d-none">
                                        @csrf
                                    </form>
                                    <a class="dropdown-item" href="javascript:void(0)" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="dropdown-icon fe fe-alert-circle"></i> Sign out
                                    </a>
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
                            <img src="{{ asset('assets/images/attendance.webp') }}" class="header-brand-img desktop-logo" alt="logo" style="width:50px;">
                            <img src="{{ asset('assets/images/attendance.webp') }}" class="header-brand-img toggle-logo" alt="logo" style="width:50px;">
                            <img src="{{ asset('assets/images/attendance.webp') }}" class="header-brand-img light-logo" alt="logo" style="width:50px;">
                            <img src="{{ asset('assets/images/attendance.webp') }}" class="header-brand-img light-logo1" alt="logo" style="width:50px;">
                        </a>
                    </div>
                    <div class="main-sidemenu">
                        <div class="slide-left disabled d-none" id="slide-left">
                           
                        </div>
                        <ul class="side-menu mt-4">
                            <li class="sub-category">
                            </li>
                            <li class="slide">
                                <a class="sidenav-menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                    <i class="side-menu__icon fe fe-grid"></i>
                                    <span class="side-menu__label">Dashboard</span>
                                </a>
                            </li>
                            <li class="slide">
                                <a class="sidenav-menu-item {{ request()->routeIs('employees.*') ? 'active' : '' }}" href="{{ route('employees.index') }}">
                                    <i class="side-menu__icon fe fe-users"></i>
                                    <span class="side-menu__label">Employees</span>
                                </a>
                            </li>
                            <li class="slide">
                                <a class="sidenav-menu-item {{ request()->routeIs('attendance.index') ? 'active' : '' }}" href="{{ route('attendance.index') }}">
                                    <i class="side-menu__icon fe fe-home"></i>
                                    <span class="side-menu__label">Attendance</span>
                                </a>
                            </li>
                            <li class="slide">
                                <a class="sidenav-menu-item {{ request()->routeIs('attendance.upload') ? 'active' : '' }}" href="{{ route('attendance.upload') }}">
                                    <i class="side-menu__icon fe fe-upload"></i>
                                    <span class="side-menu__label">Upload Logs</span>
                                </a>
                            </li>
                            <li class="slide">
                                <a class="sidenav-menu-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                                    <i class="side-menu__icon fe fe-user"></i>
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
                        @yield('content')
                    </div>
                    <!-- CONTAINER END -->
                </div>
            </div>
            <!-- app-content end -->
        </div>

        <!-- FOOTER -->
        <footer class="footer">
            <div class="container">
                <div class="row align-items-center flex-row-reverse">
                    <div class="col-md-12 col-sm-12 text-center">
                        Copyright © {{ date('Y') }} <a href="javascript:void(0)">Attendance App</a>.
                    </div>
                </div>
            </div>
        </footer>
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
    <script src="{{ asset('assets/js/sidemenu.js') }}"></script>

    <!-- sticky js -->
    <script src="{{ asset('assets/js/sticky.js') }}"></script>

    <!-- CUSTOM JS -->
    <script src="{{ asset('assets/js/circle-progress.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>

    @yield('scripts')
</body>
</html>
