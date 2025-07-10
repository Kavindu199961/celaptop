<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Your Repair - CE Laptop Repair Center</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">


    <style>
        :root {
            --primary-color: rgb(6, 6, 132);
            --secondary-color: rgb(49, 46, 63);
            --accent-color: #2c8577;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --light-bg: #f8f9fa;
            --white: #ffffff;
            --text-muted: #6c757d;
            --border-color: #e9ecef;
        }
        
        * {
            box-sizing: border-box;
        }
        
        body {
            background: 
         
                url('/assets/img/bg.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            padding-top: 76px;
        }

        /* Navbar Styles */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
            font-size: 1.2rem;
        }
        
        .register-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(75, 184, 169, 0.3);
            font-size: 0.9rem;
        }
        
        .register-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(75, 184, 169, 0.4);
            color: white;
        }
        
        .login-btn {
            background: transparent;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
            padding: 8px 16px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-right: 10px;
            font-size: 0.9rem;
        }
        
        .login-btn:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }
        
        /* Main Container */
        .main-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            overflow: hidden;
            margin-bottom: 20px;
            max-width: 800px; /* Reduced from default container width */
            margin-left: auto;
            margin-right: auto;
        }
        
        /* Search Section */
        .search-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        
        .search-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .search-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 20px;
        }
        
        .search-form {
            max-width: 400px;
            margin: 0 auto;
        }
        
        .search-input {
            border: none;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 14px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            background: white;
        }
        
        .search-input:focus {
            outline: none;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
        
        .search-btn {
            background: var(--white);
            color: var(--primary-color);
            border: none;
            border-radius: 12px;
            padding: 12px 20px;
            font-weight: 600;
            margin-top: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            font-size: 0.95rem;
        }
        
        .search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        
        /* Content Area */
        .content-area {
            padding: 25px 15px;
            min-height: 400px;
        }
        
        /* Cards */
        .info-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .info-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }
        
        .card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        
        .card-title i {
            margin-right: 8px;
            color: var(--primary-color);
            font-size: 1rem;
        }
        
        /* Progress Section */
        .progress-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.05);
            height: fit-content;
            position: sticky;
            top: 90px;
        }
        
        .progress-step {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 12px;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .progress-step:not(:last-child)::after {
            content: '';
            position: absolute;
            left: 25px;
            bottom: -8px;
            width: 2px;
            height: 16px;
            background: var(--border-color);
        }
        
        .step-completed::after {
            background: var(--success-color) !important;
        }
        
        .step-active::after {
            background: var(--primary-color) !important;
        }
        
        .step-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 12px;
            transition: all 0.3s ease;
        }
        
        .step-pending .step-icon {
            background: #e9ecef;
            color: #6c757d;
        }
        
        .step-active .step-icon {
            background: var(--primary-color);
            color: white;
            animation: pulse 2s infinite;
        }
        
        .step-completed .step-icon {
            background: var(--success-color);
            color: white;
        }
        
        .step-content {
            flex: 1;
        }
        
        .step-title {
            font-weight: 600;
            margin-bottom: 3px;
            color: #333;
            font-size: 0.95rem;
        }
        
        .step-description {
            font-size: 0.8rem;
            color: var(--text-muted);
            line-height: 1.4;
        }
        
        .step-active .step-title {
            color: var(--primary-color);
        }
        
        .step-completed .step-title {
            color: var(--success-color);
        }
        
        /* Status Badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 18px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        .status-in-progress {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        
        .status-completed {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        /* Contact Button */
        .contact-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(75, 184, 169, 0.3);
            width: 100%;
            margin-top: 15px;
            font-size: 0.9rem;
        }
        
        .contact-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(75, 184, 169, 0.4);
        }
        
        /* Shop info styling */
        .shop-contact {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            padding: 3px 0;
            font-size: 0.9rem;
        }
        
        .shop-contact i {
            width: 18px;
            color: var(--primary-color);
            font-size: 0.9rem;
        }
        
        .device-info, .customer-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        
        .info-item {
            margin-bottom: 10px;
        }
        
        .info-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 3px;
            font-size: 0.85rem;
        }
        
        .info-value {
            color: #333;
            font-size: 0.9rem;
        }
        
        /* Login Modal - Smart Compact Design */
.bg-gradient-primary {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)) !important;
}

.login-modal .modal-content {
    border-radius: 10px;
    overflow: hidden;
    border: none;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
}

.login-modal .modal-header {
    border-bottom: none;
    padding: 1rem 1.2rem;
}

.login-modal .modal-title {
    font-weight: 600;
    font-size: 1.1rem; /* reduced size */
    margin-bottom: 0;
}

.login-modal .form-control-lg {
    padding: 10px 12px;
    font-size: 0.95rem;
    border-radius: 6px;
}

.login-modal .input-group-text {
    border-radius: 6px 0 0 6px !important;
    padding: 10px;
    font-size: 0.9rem;
}

.login-modal .toggle-password {
    border-radius: 0 6px 6px 0 !important;
    padding: 10px;
    font-size: 0.9rem;
}

.login-modal .btn-primary {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border: none;
    padding: 10px;
    font-size: 0.95rem;
    font-weight: 600;
    transition: all 0.3s ease;
    border-radius: 6px;
}

.login-modal .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

        
        /* Responsive Design */
        @media (max-width: 992px) {
            .progress-card {
                position: static;
                margin-bottom: 20px;
            }
            
            .search-title {
                font-size: 1.5rem;
            }
        }
        
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.1rem;
            }
            
            .register-btn, .login-btn {
                padding: 6px 12px;
                font-size: 0.8rem;
            }
            
            .search-title {
                font-size: 1.3rem;
            }
            
            .search-subtitle {
                font-size: 0.9rem;
            }
            
            .content-area {
                padding: 15px 10px;
            }
            
            .info-card, .progress-card {
                padding: 15px;
            }
            
            .card-title {
                font-size: 1rem;
            }
            
            .step-icon {
                width: 28px;
                height: 28px;
                font-size: 11px;
            }
            
            .step-title {
                font-size: 0.9rem;
            }
            
            .step-description {
                font-size: 0.75rem;
            }
        }
        
        @media (max-width: 576px) {
            body {
                padding-top: 70px;
            }
            
            .search-section {
                padding: 20px 15px;
            }
            
            .search-title {
                font-size: 1.2rem;
            }
            
            .search-input, .search-btn {
                padding: 10px 15px;
            }
            
            .info-card {
                padding: 15px 12px;
            }
            
            .shop-contact {
                font-size: 0.85rem;
            }
            
            .info-label {
                font-size: 0.8rem;
            }
            
            .info-value {
                font-size: 0.85rem;
            }
            
            .navbar-nav {
                flex-direction: row;
                flex-wrap: wrap;
                justify-content: flex-end;
            }
            
            .nav-item {
                margin-bottom: 5px;
            }
        }
        
        /* Animations */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .alert {
            border-radius: 8px;
            border: none;
            padding: 15px;
            margin-bottom: 15px;
            font-size: 0.9rem;
        }
        
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid var(--danger-color);
        }
        
        /* Modal adjustments */
        @media (max-width: 768px) {
            .modal-body .card-body {
                padding: 15px;
            }
            
            .modal-body .btn {
                padding: 8px 12px;
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-tools me-2"></i>Repair Center
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <button class="btn login-btn" data-bs-toggle="modal" data-bs-target="#loginModal">
                            <i class="fas fa-sign-in-alt me-2"></i>Sign In
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="btn register-btn"   data-bs-toggle="modal" data-bs-target="#registerModal">
                            <i class="fas fa-store me-2"></i>Do you have a repair shop?
                       </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container mt-3">
        <div class="main-container">
            <!-- Search Section -->
            <div class="search-section">
                <h1 class="search-title">
                    <i class="fas fa-search me-2"></i>Track Your Repair
                </h1>
                <p class="search-subtitle">Enter your customer number to check repair status</p>
                
                <form class="search-form" method="GET">
                    <div class="mb-2">
                        <input type="text" class="form-control search-input" 
                               name="tracking_number" placeholder="Enter Customer Number (e.g., XX-0-0000)" 
                               value="{{ request('tracking_number') }}" required>
                    </div>
                    <button type="submit" class="btn search-btn w-100">
                        <i class="fas fa-search me-2"></i>Track My Repair
                    </button>
                </form>
            </div>

            <!-- Content Area -->
            <div class="content-area">
                @if(request()->has('tracking_number'))
                    @if(!$repair)
                        <div class="alert alert-danger fade-in">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            No repair found with customer number: {{ request('tracking_number') }}
                        </div>
                    @else
                        <div class="row">
                            <!-- Left Side - Repair Progress -->
                            <div class="col-lg-6 col-md-12">
                                <div class="progress-card fade-in">
                                    <h4 class="card-title">
                                        <i class="fas fa-tasks me-2"></i>Repair Progress
                                    </h4>
                                    
                                    @foreach($steps as $step)
                                        <div class="progress-step 
                                            {{ $step['completed'] ? 'step-completed' : '' }}
                                            {{ $step['active'] ? 'step-active' : '' }}
                                            {{ (!$step['completed'] && !$step['active']) ? 'step-pending' : '' }}">
                                            <div class="step-icon">
                                                @if($step['completed'])
                                                    <i class="fas fa-check"></i>
                                                @else
                                                    {{ $loop->iteration }}
                                                @endif
                                            </div>
                                            <div class="step-content">
                                                <div class="step-title">{{ $step['label'] }}</div>
                                                <div class="step-description">{{ $step['description'] }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                    
                                    @if($shopDetails)
                                        <button class="contact-btn" onclick="window.location.href='tel:{{ $shopDetails->hotline }}'">
                                            <i class="fas fa-phone me-2"></i>Contact Shop
                                        </button>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Right Side - Shop Info & Repair Details -->
                            <div class="col-lg-6 col-md-12">
                                <!-- Shop Information -->
                                @if($shopDetails)
                                    <div class="info-card fade-in">
                                        <h4 class="card-title">
                                            <i class="fas fa-store me-2"></i>{{ $shopDetails->shop_name ?? 'CE Laptop Repair Center' }}
                                        </h4>
                                        <div class="shop-contact">
                                            <i class="fas fa-map-marker-alt me-2"></i>
                                            <span>{{ $shopDetails->address }}</span>
                                        </div>
                                        <div class="shop-contact">
                                            <i class="fas fa-phone me-2"></i>
                                            <span>{{ $shopDetails->hotline }}</span>
                                        </div>
                                        <div class="shop-contact">
                                            <i class="fas fa-envelope me-2"></i>
                                            <span>{{ $shopDetails->email }}</span>
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Repair Details -->
                                <div class="info-card fade-in">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h4 class="card-title mb-0">
                                            <i class="fas fa-ticket-alt me-2"></i>Repair #{{ $repair->customer_number }}
                                        </h4>
                                        <span class="status-badge 
                                            @if($status === 'completed') status-completed
                                            @elseif($repair->status === 'ready') status-completed
                                            @else status-in-progress @endif">
                                            @if($status === 'completed' || $repair->status === 'ready')
                                                <i class="fas fa-check-circle me-1"></i>Completed
                                            @else
                                                <i class="fas fa-cog me-1"></i>In Progress
                                            @endif
                                        </span>
                                    </div>
                                    
                                    <p class="text-muted mb-3" style="font-size: 0.85rem;">
                                        <i class="fas fa-calendar-alt me-2"></i>
                                        Received on {{ $repair->created_at->format('M j, Y') }}
                                    </p>
                                    
                                    <div class="device-info">
                                        <h5 class="mb-2" style="font-size: 1rem;">
                                            <i class="fas fa-laptop me-2"></i>Device Information
                                        </h5>
                                        <div class="info-item">
                                            <div class="info-label">Device</div>
                                            <div class="info-value">{{ $repair->device }}</div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label">Serial Number</div>
                                            <div class="info-value">{{ $repair->serial_number }}</div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label">Reported Issue</div>
                                            <div class="info-value">{{ $repair->fault }}</div>
                                        </div>
                                    </div>
                                    
                                    <div class="customer-info">
                                        <h5 class="mb-2" style="font-size: 1rem;">
                                            <i class="fas fa-user me-2"></i>Customer Information
                                        </h5>
                                        <div class="info-item">
                                            <div class="info-label">Name</div>
                                            <div class="info-value">{{ $repair->customer_name }}</div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label">Contact</div>
                                            <div class="info-value">{{ $repair->contact }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-search fa-2x text-muted mb-3" style="opacity: 0.5;"></i>
                        <h4 class="text-muted" style="font-size: 1.2rem;">Enter your customer number to track your repair</h4>
                        <p class="text-muted" style="font-size: 0.9rem;">Check the status of your device repair in real-time</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Login Modal -->
    <div class="modal fade login-modal" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title" id="loginModalLabel">
                    <i class="fas fa-sign-in-alt me-2"></i>Welcome Back!
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf
                    
                    <!-- <div class="text-center mb-4">
                        <div class="avatar-lg mx-auto mb-3">
                            <div class="rounded-circle bg-light p-2 shadow-sm" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user-circle text-primary" style="font-size: 3rem;"></i>
                            </div>
                        </div>
                        <h4 class="text-dark">Sign in to continue</h4>
                    </div> -->
                    
                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-envelope text-muted"></i>
                            </span>
                            <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="Enter your email" required autofocus>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-lock text-muted"></i>
                            </span>
                            <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Enter your password" required>
                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="text-end mt-2">
                          
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100" id="signInBtn">
                    <span id="signInSpinner" class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                    Sign In
                    </button>
                    
                    <!-- <div class="d-flex align-items-center my-3">
                        <hr class="flex-grow-1">
                        <span class="mx-2 text-muted">or</span>
                        <hr class="flex-grow-1">
                    </div> -->
                    
                    <div class="text-center">
                        <p class="mb-0">Don't have an account? 
                            <a href="{{ route('register') }}" class="text-decoration-none fw-bold text-primary">Sign up</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <!-- Register Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white;">
                    <h5 class="modal-title" id="registerModalLabel">
                        <i class="fas fa-store me-2"></i>Register Your Repair Shop
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-tools fa-2x text-primary mb-2"></i>
                        <h4 style="font-size: 1.2rem;">Do you have a repair shop?</h4>
                        <p class="text-muted" style="font-size: 0.9rem;">Join our network of trusted repair professionals and start managing your repairs online.</p>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-user-plus fa-2x text-primary mb-2"></i>
                                    <h5 style="font-size: 1rem;">New Registration</h5>
                                    <p class="text-muted" style="font-size: 0.85rem;">Register your repair shop and get access to our management system.</p>
                                    <a href="{{ route('register') }}" class="btn btn-primary" style="padding: 8px 16px; font-size: 0.85rem;">Register Now</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-headset fa-2x text-success mb-2"></i>
                                    <h5 style="font-size: 1rem;">Contact Admin</h5>
                                    <p class="text-muted" style="font-size: 0.85rem;">Need help with registration? Our admin team is here to assist you.</p>
                                    <a href="https://wa.me/94765645303" target="_blank" class="btn btn-success" style="padding: 8px 16px; font-size: 0.85rem;">
                                        <i class="fab fa-whatsapp"></i> Contact Admin
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3 p-3 bg-light rounded">
                        <h6 style="font-size: 0.95rem;"><i class="fas fa-star me-2"></i>Benefits of Joining:</h6>
                        <ul class="list-unstyled" style="font-size: 0.85rem;">
                            <li><i class="fas fa-check text-success me-2"></i>Professional dashboard</li>
                            <li><i class="fas fa-check text-success me-2"></i>Online repair tracking system</li>
                            <li><i class="fas fa-check text-success me-2"></i>Customer management</li>
                            <li><i class="fas fa-check text-success me-2"></i>All Repair management</li>
                            <li><i class="fas fa-check text-success me-2"></i>Invoice management</li>
                            <li><i class="fas fa-check text-success me-2"></i>Stock management</li>
                            <li><i class="fas fa-check text-success me-2"></i>Shop management</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add smooth scrolling and animations
        document.addEventListener('DOMContentLoaded', function() {
            // Animate cards on scroll
            const cards = document.querySelectorAll('.info-card, .progress-card');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('fade-in');
                    }
                });
            });
            
            cards.forEach(card => {
                observer.observe(card);
            });

            // Handle login form submission
            const loginForm = document.getElementById('loginForm');
            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    // You can add form validation here
                    console.log('Login form submitted');
                    // e.preventDefault(); // Uncomment to prevent actual submission for demo
                });
            }
        });


            document.addEventListener('DOMContentLoaded', function() {
                // Toggle password visibility
                const togglePassword = document.querySelector('.toggle-password');
                if (togglePassword) {
                    togglePassword.addEventListener('click', function() {
                        const password = document.getElementById('password');
                        const icon = this.querySelector('i');
                        if (password.type === 'password') {
                            password.type = 'text';
                            icon.classList.remove('fa-eye');
                            icon.classList.add('fa-eye-slash');
                        } else {
                            password.type = 'password';
                            icon.classList.remove('fa-eye-slash');
                            icon.classList.add('fa-eye');
                        }
                    });
                }
            });

            // Example reset after failure
        setTimeout(() => {
        const btn = document.getElementById('signInBtn');
        btn.innerHTML = '<span id="signInSpinner" class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>Sign In';
        btn.removeAttribute('disabled');
        }, 3000); // adjust time or place inside your error handler


        document.addEventListener('DOMContentLoaded', function() {
        // Check for success message
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Registration Successful',
                text: '{{ session('success') }}',
                confirmButtonColor: '#0d6efd',
                iconColor: '#28a745',
                timer: 5000,
                timerProgressBar: true,
                showConfirmButton: true
            });
        @endif

        // Check for error message
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Registration Error',
                text: '{{ session('error') }}',
                confirmButtonColor: '#0d6efd',
                iconColor: '#dc3545',
                showConfirmButton: true
            });
        @endif
    });

    </script>
    <!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>