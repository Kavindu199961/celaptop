<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Doctor Channeling Center</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
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
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.8), rgba(5, 150, 105, 0.7)), 
                        url('https://images.unsplash.com/photo-1559757148-5c350d0d3c56?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') center/cover no-repeat fixed;
            min-height: 100vh;
            color: var(--text-dark);
            line-height: 1.6;
        }
        
        .login-wrapper {
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
        
        .login-card {
            background: var(--bg-light);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            box-shadow: 0 20px 40px var(--shadow-medium);
            padding: 2.5rem;
            transition: all 0.3s ease;
        }
        
        .login-card:hover {
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
        
        .btn-login {
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
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.4);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .btn-login .spinner-border {
            width: 1rem;
            height: 1rem;
            margin-right: 0.5rem;
        }
        
        .forgot-link {
            text-align: right;
            margin-top: -0.5rem;
            margin-bottom: 1rem;
        }
        
        .forgot-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .forgot-link a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }
        
        .divider {
            position: relative;
            margin: 1.5rem 0;
            text-align: center;
        }
        
        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e5e7eb;
        }
        
        .divider span {
            background: var(--bg-light);
            padding: 0 1rem;
            color: var(--text-muted);
            font-size: 0.85rem;
        }
        
        .social-login {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .social-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            background: white;
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .social-btn:hover {
            background: #f9fafb;
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-2px);
        }
        
        .social-btn i {
            margin-right: 0.5rem;
            font-size: 1.1rem;
        }
        
        .register-link {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
        }
        
        .register-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        
        .register-link a:hover {
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
        
        .floating-elements {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }
        
        .floating-icon {
            position: absolute;
            color: rgba(255, 255, 255, 0.1);
            animation: float 6s ease-in-out infinite;
        }
        
        .floating-icon:nth-child(1) { top: 10%; left: 10%; animation-delay: 0s; }
        .floating-icon:nth-child(2) { top: 20%; right: 10%; animation-delay: 1s; }
        .floating-icon:nth-child(3) { bottom: 30%; left: 15%; animation-delay: 2s; }
        .floating-icon:nth-child(4) { bottom: 10%; right: 20%; animation-delay: 3s; }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        /* Mobile Responsive */
        @media (max-width: 576px) {
            .login-wrapper {
                margin: 15px;
            }
            
            .login-card {
                padding: 2rem 1.5rem;
            }
            
            .brand-title {
                font-size: 1.3rem;
            }
            
            .social-login {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 768px) {
            body {
                background-attachment: scroll;
            }
        }
    </style>
</head>
<body>
    <!-- Floating Medical Icons -->
    <div class="floating-elements">
        <i class="bi bi-heart-pulse floating-icon" style="font-size: 3rem;"></i>
        <i class="bi bi-capsule floating-icon" style="font-size: 2.5rem;"></i>
        <i class="bi bi-hospital floating-icon" style="font-size: 2.8rem;"></i>
        <i class="bi bi-shield-check floating-icon" style="font-size: 2.6rem;"></i>
    </div>

    <div class="container-fluid d-flex align-items-center justify-content-center min-vh-100">
        <div class="row justify-content-center w-100">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-4">
                <div class="login-wrapper">
                    <div class="login-card">
                        <!-- Brand Section -->
                        <div class="brand-section">
                            <div class="brand-logo">
                                <i class="bi bi-hospital"></i>
                            </div>
                            <h1 class="brand-title">Micro Channeling Center</h1>
                            <p class="brand-subtitle">Your Health, Our Priority</p>
                        </div>

                        <!-- Login Form -->
                        <form method="POST" action="{{ route('login') }}" id="loginForm">
                            @csrf
                            
                            <!-- Error Messages -->
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    {{ $errors->first() }}
                                </div>
                            @endif
                            
                            <!-- Email Field -->
                            <div class="form-group">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope me-1"></i>
                                    Email Address
                                </label>
                                <div class="input-group">
                                    <i class="bi bi-envelope input-icon"></i>
                                    <input type="email" 
                                           id="email" 
                                           name="email" 
                                           class="form-control" 
                                           placeholder="Enter your email address"
                                           value="{{ old('email') }}" 
                                           required>
                                </div>
                                @error('email')
                                    <div class="text-danger mt-1" style="font-size: 0.85rem;">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <!-- Password Field -->
                            <div class="form-group">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock me-1"></i>
                                    Password
                                </label>
                                <div class="input-group">
                                    <i class="bi bi-lock input-icon"></i>
                                    <input type="password" 
                                           id="password" 
                                           name="password" 
                                           class="form-control" 
                                           placeholder="Enter your password"
                                           required>
                                </div>
                                @error('password')
                                    <div class="text-danger mt-1" style="font-size: 0.85rem;">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <!-- Forgot Password -->
                            <!-- <div class="forgot-link">
                                <a href="#" onclick="showForgotPassword()">
                                    <i class="bi bi-question-circle me-1"></i>
                                    Forgot your password?
                                </a>
                            </div>
                             -->
                            <!-- Login Button -->
                            <button type="submit" class="btn btn-login" id="loginBtn">
                                <span class="btn-text">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>
                                    Sign In
                                </span>
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Form Animation and Validation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('loginBtn');
            const btnText = btn.querySelector('.btn-text');
            
            // Add loading state
            btnText.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Signing In...';
            btn.disabled = true;
            
            // Remove loading state after 3 seconds if form doesn't submit
            setTimeout(() => {
                if (btn.disabled) {
                    btnText.innerHTML = '<i class="bi bi-box-arrow-in-right me-2"></i>Sign In';
                    btn.disabled = false;
                }
            }, 3000);
        });

        // Input Focus Effects
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });

        // Forgot Password Alert
        function showForgotPassword() {
            alert('Please contact the administration at admin@doctorchannelingcenter.com or call +94 11 234 5678 to reset your password.');
        }

        // Add subtle parallax effect on scroll (mobile-friendly)
        if (window.innerWidth > 768) {
            window.addEventListener('scroll', function() {
                const scrolled = window.pageYOffset;
                document.body.style.backgroundPosition = `center ${scrolled * 0.5}px`;
            });
        }

        // Enhanced form validation
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('input', function() {
                if (this.validity.valid) {
                    this.style.borderColor = '#10b981';
                } else {
                    this.style.borderColor = '#ef4444';
                }
            });
        });
    </script>
</body>
</html>