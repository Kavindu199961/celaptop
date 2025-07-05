<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | CE Laptop Repair</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary-color: #059669;
            --accent-color: #f59e0b;
            --text-dark: #1f2937;
            --text-muted: #6b7280;
            --bg-light: rgba(255, 255, 255, 0.95);
            --shadow-light: rgba(0, 0, 0, 0.1);
            --shadow-medium: rgba(0, 0, 0, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-image: linear-gradient(135deg, rgba(0, 0, 0, 0.8), rgba(65, 67, 66, 0.7)),
                  url('/assets/img/bg.jpg');
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            color: var(--text-dark);
            line-height: 1.6;
        }

        .register-wrapper {
            width: 100%;
            max-width: 450px;
            margin: 20px;
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .register-card {
            background: var(--bg-light);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            box-shadow: 0 20px 40px var(--shadow-medium);
            padding: 2.5rem;
            transition: all 0.3s ease;
        }

        .register-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 50px var(--shadow-medium);
        }

        .brand-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .brand-logo {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.3);
        }

        .brand-logo i {
            font-size: 2rem;
            color: white;
        }

        .brand-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .brand-subtitle {
            color: var(--text-muted);
            font-size: 0.95rem;
            font-weight: 400;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .input-group {
            position: relative;
        }

        .input-group .form-control {
            padding: 0.875rem 1rem 0.875rem 3rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: white;
        }

        .input-group .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.1);
            outline: none;
        }

        .input-group .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            z-index: 3;
            font-size: 1.1rem;
        }

        .btn-register {
            width: 100%;
            padding: 0.875rem;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            margin-top: 1rem;
            position: relative;
            overflow: hidden;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.4);
        }

        .btn-register:active {
            transform: translateY(0);
        }

        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
        }

        .login-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .login-link a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .alert {
            border-radius: 12px;
            margin-bottom: 1.5rem;
            border: none;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        .text-danger {
            font-size: 0.85rem;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .register-wrapper {
                margin: 15px;
            }

            .register-card {
                padding: 2rem 1.5rem;
            }

            .brand-title {
                font-size: 1.3rem;
            }
        }
    </style>
</head>
<body>

    <div class="container-fluid d-flex align-items-center justify-content-center min-vh-100">
        <div class="register-wrapper">
            <div class="register-card">
                <div class="brand-section">
                    <div class="brand-logo">
                        <i class="bi bi-laptop"></i>
                    </div>
                    <h1 class="brand-title">Create Account</h1>
                    <p class="brand-subtitle">Join our laptop repair community</p>
                </div>

                <form method="POST" action="{{ route('register') }}" id="registerForm">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <!-- Name -->
                    <div class="form-group">
                        <label for="name" class="form-label">
                            <i class="bi bi-person me-1"></i> Full Name
                        </label>
                        <div class="input-group">
                            <i class="bi bi-person input-icon"></i>
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                                   placeholder="Enter your full name" value="{{ old('name') }}" required autofocus>
                        </div>
                        @error('name')
                            <div class="text-danger mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope me-1"></i> Email Address
                        </label>
                        <div class="input-group">
                            <i class="bi bi-envelope input-icon"></i>
                            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   placeholder="Enter your email address" value="{{ old('email') }}" required>
                        </div>
                        @error('email')
                            <div class="text-danger mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="bi bi-lock me-1"></i> Password
                        </label>
                        <div class="input-group">
                            <i class="bi bi-lock input-icon"></i>
                            <input type="password" id="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Create a strong password" required>
                        </div>
                        @error('password')
                            <div class="text-danger mt-1" id="passwordError">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @else
                            <div class="text-danger mt-1" id="passwordError" style="display: none;"></div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label for="password-confirm" class="form-label">
                            <i class="bi bi-lock-fill me-1"></i> Confirm Password
                        </label>
                        <div class="input-group">
                            <i class="bi bi-lock-fill input-icon"></i>
                            <input type="password" id="password-confirm" name="password_confirmation"
                                   class="form-control" placeholder="Confirm your password" required>
                        </div>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn btn-register" id="registerBtn">
                        <span class="btn-text"><i class="bi bi-person-plus me-2"></i>Create Account</span>
                    </button>

                    <!-- Login Link -->
                    <div class="login-link">
                        Already have an account? <a href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right me-1"></i>Sign In</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const blockedDomains = ['mailinator.com', 'tempmail.com', '10minutemail.com', 'example.com', 'test.com'];

        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('registerBtn');
            const btnText = btn.querySelector('.btn-text');
            btnText.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating Account...';
            btn.disabled = true;
            setTimeout(() => {
                if (btn.disabled) {
                    btnText.innerHTML = '<i class="bi bi-person-plus me-2"></i>Create Account';
                    btn.disabled = false;
                }
            }, 3000);
        });

        // Real-time password validation
        document.getElementById('password').addEventListener('input', function () {
            const value = this.value;
            const errorBox = document.querySelector('#passwordError');
            const regex = {
                lowercase: /[a-z]/,
                uppercase: /[A-Z]/,
                number: /[0-9]/,
                special: /[@$!%*#?&]/,
                length: /.{8,}/
            };
            let errors = [];
            if (!regex.lowercase.test(value)) errors.push("1 lowercase letter");
            if (!regex.uppercase.test(value)) errors.push("1 uppercase letter");
            if (!regex.number.test(value)) errors.push("1 number");
            if (!regex.special.test(value)) errors.push("1 special character");
            if (!regex.length.test(value)) errors.push("minimum 8 characters");

            if (errors.length > 0) {
                errorBox.innerHTML = `<i class="bi bi-exclamation-circle me-1"></i>Password must include: ${errors.join(', ')}`;
                errorBox.style.display = "block";
                this.style.borderColor = '#ef4444';
            } else {
                errorBox.style.display = "none";
                this.style.borderColor = '#10b981';
            }
        });

        // Email warning
        document.getElementById('email').addEventListener('blur', function () {
            const value = this.value.toLowerCase();
            const domain = value.split('@')[1];
            if (blockedDomains.includes(domain)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid Email Domain',
                    text: 'Please use a real email address. Disposable emails are not allowed.',
                    iconColor: '#f59e0b',
                    confirmButtonColor: '#0d6efd'
                });
            }
        });

        // Flash messages
        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Registration Successful',
            text: '{{ session('success') }}',
            confirmButtonColor: '#0d6efd',
            iconColor: '#28a745'
        });
        @endif

        @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Registration Error',
            text: '{{ session('error') }}',
            confirmButtonColor: '#0d6efd',
            iconColor: '#dc3545'
        });
        @endif
    </script>
</body>
</html>
