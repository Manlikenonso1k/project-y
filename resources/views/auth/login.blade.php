<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Ecommerce</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <style>
        :root {
            --brand: #1d4ed8;
            --brand-2: #0ea5e9;
            --ink: #16213a;
            --muted: #64748b;
            --surface: #f5f8ff;
            --danger: #ef4444;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
            background: linear-gradient(135deg, var(--brand) 0%, var(--brand-2) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Animated background shapes */
        body::before,
        body::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            pointer-events: none;
        }

        body::before {
            width: 500px;
            height: 500px;
            top: -100px;
            right: -100px;
            animation: float 6s ease-in-out infinite;
        }

        body::after {
            width: 300px;
            height: 300px;
            bottom: -50px;
            left: -50px;
            animation: float 8s ease-in-out infinite reverse;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(30px);
            }
        }

        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 420px;
            padding: 0 20px;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(22, 33, 58, 0.15);
            overflow: hidden;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .login-header {
            background: linear-gradient(135deg, var(--brand) 0%, var(--brand-2) 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }

        .login-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .login-header p {
            font-size: 14px;
            opacity: 0.9;
            margin: 0;
        }

        .login-body {
            padding: 40px 30px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
            color: var(--ink);
            letter-spacing: 0.3px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--brand);
            box-shadow: 0 0 0 4px rgba(29, 78, 216, 0.1);
            background: rgba(29, 78, 216, 0.02);
        }

        .form-group input::placeholder {
            color: var(--muted);
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            font-size: 13px;
        }

        .form-check {
            display: flex;
            align-items: center;
            margin: 0;
        }

        .form-check input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-right: 8px;
            cursor: pointer;
            border: 2px solid #e2e8f0;
            border-radius: 4px;
            accent-color: var(--brand);
        }

        .form-check label {
            margin: 0;
            cursor: pointer;
            color: var(--ink);
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .forgot-link {
            color: var(--brand);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .forgot-link:hover {
            color: var(--brand-2);
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            padding: 13px 24px;
            background: linear-gradient(120deg, var(--brand) 0%, var(--brand-2) 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 0.3px;
            text-transform: uppercase;
            box-shadow: 0 8px 20px rgba(29, 78, 216, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(29, 78, 216, 0.4);
            background: linear-gradient(120deg, #1a3fa0 0%, #0d8ab8 100%);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .login-footer {
            text-align: center;
            padding: 24px 30px;
            border-top: 1px solid #f1f5f9;
            background: #f8fafc;
        }

        .login-footer p {
            margin: 0;
            font-size: 13px;
            color: var(--muted);
        }

        .error-alert {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 24px;
            font-size: 13px;
            display: none;
        }

        .error-alert.show {
            display: block;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .demo-credentials {
            background: #dbeafe;
            border: 1px solid #93c5fd;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 24px;
            font-size: 12px;
            color: #1e40af;
        }

        .demo-credentials strong {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
        }

        .demo-credentials code {
            background: rgba(0, 0, 0, 0.05);
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'Monaco', 'Courier New', monospace;
        }

        @media (max-width: 576px) {
            .login-card {
                border-radius: 16px;
            }

            .login-header {
                padding: 30px 20px;
            }

            .login-header h1 {
                font-size: 24px;
            }

            .login-body {
                padding: 30px 20px;
            }

            .login-footer {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1>Welcome Back</h1>
                <p>Admin Dashboard</p>
            </div>

            <div class="login-body">
                <div class="demo-credentials">
                    <strong>📝 Demo Credentials:</strong>
                    Email: <code>admin@example.com</code><br>
                    Password: <code>password123</code>
                </div>

                @if ($errors->any())
                    <div class="error-alert show">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            placeholder="admin@example.com"
                            required 
                            autofocus
                        >
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="••••••••"
                            required
                        >
                    </div>

                    <div class="remember-forgot">
                        <div class="form-check">
                            <input 
                                type="checkbox" 
                                id="remember" 
                                name="remember"
                            >
                            <label for="remember">Remember me</label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-link">Forgot Password?</a>
                        @endif
                    </div>

                    <button type="submit" class="btn-login">Sign In</button>
                </form>
            </div>

            <div class="login-footer">
                <p>© 2026 Ecommerce Platform. All rights reserved.</p>
            </div>
        </div>
    </div>

    <script>
        // Add some interactivity
        const inputs = document.querySelectorAll('input[type="email"], input[type="password"]');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.parentElement.style.opacity = '1';
            });
        });
    </script>
</body>
</html>
