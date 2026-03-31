@extends('layouts.app')

@section('title', 'Login')

@section('content')
@push('styles')
<style>
    .footer,
    .app-header,
    .app-sidebar {
        display: none !important;
    }

    .main-content {
        margin-left: 0 !important;
        padding-top: 0 !important;
    }

    .auth-page {
        background: #f8fafc;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .login-box {
        background: white;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
        display: flex;
        width: 1000px;
        max-width: 100%;
        min-height: 600px;
    }

    .login-left {
        flex: 1;
        background: linear-gradient(135deg, #1a9e52, #2ecc71);
        padding: 60px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        color: white;
        position: relative;
    }

    .login-right {
        width: 450px;
        padding: 60px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .login-logo {
        width: 80px;
        height: 80px;
        background: white;
        border-radius: 20px;
        padding: 10px;
        margin-bottom: 30px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .login-logo img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .form-control {
        height: 50px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding-left: 45px !important;
        font-size: 14px;
    }

    .form-group {
        position: relative;
        margin-bottom: 20px;
    }

    .form-group i:first-child {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 16px;
        z-index: 5;
    }

    .btn-login {
        height: 50px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 16px;
        background: #2ecc71 !important;
        border-color: #2ecc71 !important;
        color: white !important;
        transition: all 0.3s ease;
    }

    .btn-login:hover {
        background: #23834b !important;
        border-color: #23834b !important;
        transform: translateY(-2px);
    }

    .br-12 { border-radius: 12px !important; }

    @media (max-width: 991px) {
        .login-left {
            display: none;
        }
        .login-box {
            width: 450px;
            min-height: auto;
        }
        .login-right {
            width: 100%;
            padding: 40px;
        }
    }
</style>
@endpush

<div class="auth-page mt-0">
    <div class="login-box shadow-lg border-0">
        <div class="login-left">
            <div class="login-logo">
                <img src="{{ asset('assets/images/hr-logo2.jpg') }}" alt="Logo">
            </div>
            <h1 class="fw-bold mb-3 display-6">Human Resource Management</h1>
            <p class="fs-5 opacity-75 mb-0">Seamlessly manage attendance, employees, and organization reports in one place.</p>
            
            <div class="mt-auto">
                <p class="mb-0 small opacity-50">&copy; {{ date('Y') }} . All rights reserved.</p>
            </div>
        </div>
        <div class="login-right">
            <div class="login-logo d-lg-none mb-4 mx-auto">
                <img src="{{ asset('assets/images/hr-logo2.jpg') }}" alt="Logo">
            </div>
            <div class="mb-5 text-center text-md-start">
                <h3 class="fw-bold text-dark mb-1">Welcome Back</h3>
                <p class="text-muted">Please enter your credentials to sign in.</p>
            </div>

            @if($errors->any())
            <div class="alert alert-danger br-12 border-0 mb-4">
                <ul class="mb-0 small p-0 ms-3">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('authenticate') }}">
                @csrf
                <div class="form-group">
                    <i class="fe fe-mail"></i>
                    <input type="email" class="form-control" name="email" placeholder="Email Address" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="form-group">
                    <i class="fe fe-lock"></i>
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                </div>

                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label text-muted small" for="remember">
                            Remember me
                        </label>
                    </div>
                    <a href="{{ route('password.request') }}" class="small text-primary text-decoration-none fw-semibold">Forgot Password?</a>
                </div>

                <button type="submit" class="btn btn-primary w-100 btn-login">Sign In</button>
            </form>
        </div>
    </div>
</div>
@endsection