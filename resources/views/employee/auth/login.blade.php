<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Login</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link id="style" href="{{ asset('assets/css/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        :root { --primary: #54ee76; --primary-dark: #2ecc71; }

        body {
            font-family:'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1e1b4b 0%, #54ee76 50%, #2ecc71 100%);
        }

        .login-wrapper {
            width: 100%;
            max-width: 430px;
            padding: 1rem;
        }

        .login-card {
            background: rgba(255,255,255,0.97);
            border-radius: 20px;
            padding: 2.5rem 2.5rem 2rem;
            box-shadow: 0 25px 60px rgba(0,0,0,0.3);
        }

        .login-brand {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-brand .brand-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
        }

        .login-brand .brand-icon svg { color: white; }

        .login-brand h4 {
            font-weight: 700;
            color: #1a1a2e;
            margin: 0;
            font-size: 1.4rem;
        }

        .login-brand p {
            color: #6c757d;
            margin: 0.25rem 0 0;
            font-size: 0.9rem;
        }

        .badge-portal {
            display: inline-block;
            background: #e8f5e9;
            color: var(--primary);
            border: 1px solid #ddd6fe;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 4px 14px;
            letter-spacing: 0.5px;
            margin-bottom: 1rem;
        }

        .form-label { font-weight: 600; color: #374151; font-size: 0.875rem; margin-bottom: 0.4rem; }

        .form-control {
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            padding: 0.7rem 1rem;
            font-size: 0.9rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
        }

        .input-icon-wrapper { position: relative; }
        .input-icon-wrapper .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            z-index: 10;
        }
        .input-icon-wrapper .form-control { padding-left: 2.6rem; }

        .btn-login {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            border-radius: 10px;
            color: white;
            font-weight: 600;
            padding: 0.75rem;
            width: 100%;
            font-size: 0.95rem;
            letter-spacing: 0.3px;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }
        .btn-login:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(99, 102, 241, 0.4); }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 10px;
            color: #dc2626;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
        }

        .divider { text-align: center; color: #9ca3af; font-size: 0.8rem; margin: 1rem 0; }

        .footer-link { text-align: center; margin-top: 1rem; }
        .footer-link a { color: var(--primary); font-size: 0.85rem; text-decoration: none; font-weight: 500; }
        .footer-link a:hover { text-decoration: underline; }

        /* Password Eye Icon Styles */
        .password-toggle-wrapper { position: relative; }
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
        .password-toggle-icon:hover { color: var(--primary); }

    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
<div class="login-wrapper">
    <div class="login-card">
        <div class="login-brand">
            <div class="brand-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="white" viewBox="0 0 16 16">
                    <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                </svg>
            </div>
            <!-- <span class="badge-portal">Employee Portal</span> -->
            <h4>Welcome Back</h4>
            <p>Sign in to your employee account</p>
        </div>

        @if ($errors->any())
        <div class="alert-error">
            <strong>Login failed:</strong> {{ $errors->first() }}
        </div>
        @endif

        @if (session('error'))
        <div class="alert-error">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('employee.authenticate') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Company Email</label>
                <div class="input-icon-wrapper">
                    <span class="input-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2zm13 2.383-4.758 2.855L15 11.114V5.383zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.471A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741z"/>
                        </svg>
                    </span>
                    <input type="email" name="company_email" class="form-control @error('company_email') is-invalid @enderror"
                        value="{{ old('company_email') }}" placeholder="you@company.com" required autofocus>
                </div>
                @error('company_email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-icon-wrapper">
                    <span class="input-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 1a3 3 0 0 0-3 3v3H4a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-1V4a3 3 0 0 0-3-3zm-2 6V4a2 2 0 1 1 4 0v3H6zm2 2a1 1 0 0 1 .993.883L9 10v1a1 1 0 0 1-2 0v-1a1 1 0 0 1 1-1z"/>
                        </svg>
                    </span>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                        placeholder="••••••••" required>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember" style="font-size:0.85rem; color:#6c757d;">Remember me</label>
                </div>
            </div>

            <button type="submit" class="btn-login">
                Sign In 
            </button>
        </form>

        <div class="footer-link">
            <!-- <a href="{{ route('login') }}">← Admin Login</a> -->
        </div>
    </div>
</div>
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script>
    $(function() {
        // Password Eye Toggle
        $('input[type="password"]').each(function() {
            let $input = $(this);
            if ($input.parent().hasClass('password-toggle-wrapper')) return;
            
            $input.wrap('<div class="password-toggle-wrapper"></div>');
            let $icon = $('<i class="fa fa-eye-slash password-toggle-icon"></i>');
            $input.after($icon);
            
            $icon.on('click', function() {
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
</body>
</html>
