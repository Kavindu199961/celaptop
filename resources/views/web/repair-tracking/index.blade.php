<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Your Repair - CE Laptop Repair Center</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color:rgb(6, 6, 132);
            --secondary-color:rgb(43, 133, 121);
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
                linear-gradient(135deg, rgba(169, 176, 207, 0.8) 0%, rgba(118, 75, 162, 0.8) 100%),
                url('your-image-path.jpg') no-repeat center center fixed;
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
            font-size: 1.5rem;
        }
        
        .register-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(75, 184, 169, 0.3);
        }
        
        .register-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(75, 184, 169, 0.4);
            color: white;
        }
        
        /* Main Container */
        .main-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            overflow: hidden;
        }
        
        /* Search Section */
        .search-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 40px;
            text-align: center;
        }
        
        .search-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .search-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 30px;
        }
        
        .search-form {
            max-width: 400px;
            margin: 0 auto;
        }
        
        .search-input {
            border: none;
            border-radius: 15px;
            padding: 15px 20px;
            font-size: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            background: white;
        }
        
        .search-input:focus {
            outline: none;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .search-btn {
            background: var(--white);
            color: var(--primary-color);
            border: none;
            border-radius: 15px;
            padding: 15px 30px;
            font-weight: 600;
            margin-top: 15px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }
        
        /* Content Area */
        .content-area {
            padding: 40px;
            min-height: 500px;
        }
        
        /* Cards */
        .info-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }
        
        .card-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        
        .card-title i {
            margin-right: 10px;
            color: var(--primary-color);
        }
        
        /* Progress Section */
        .progress-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(0, 0, 0, 0.05);
            height: fit-content;
            position: sticky;
            top: 100px;
        }
        
        .progress-step {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 10px;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .progress-step:not(:last-child)::after {
            content: '';
            position: absolute;
            left: 29px;
            bottom: -10px;
            width: 2px;
            height: 20px;
            background: var(--border-color);
        }
        
        .step-completed::after {
            background: var(--success-color) !important;
        }
        
        .step-active::after {
            background: var(--primary-color) !important;
        }
        
        .step-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 14px;
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
            margin-bottom: 5px;
            color: #333;
        }
        
        .step-description {
            font-size: 14px;
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
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
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
            padding: 15px 30px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(75, 184, 169, 0.3);
            width: 100%;
            margin-top: 20px;
        }
        
        .contact-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(75, 184, 169, 0.4);
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .search-title {
                font-size: 2rem;
            }
            
            .content-area {
                padding: 20px;
            }
            
            .info-card, .progress-card {
                padding: 20px;
            }
            
            .progress-card {
                position: static;
                margin-top: 20px;
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
            border-radius: 10px;
            border: none;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid var(--danger-color);
        }
        
        /* Shop info styling */
        .shop-contact {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            padding: 5px 0;
        }
        
        .shop-contact i {
            width: 20px;
            color: var(--primary-color);
        }
        
        .device-info, .customer-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .info-item {
            margin-bottom: 12px;
        }
        
        .info-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 4px;
        }
        
        .info-value {
            color: #333;
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
                        <button class="btn register-btn ms-2" data-bs-toggle="modal" data-bs-target="#registerModal">
                            <i class="fas fa-store me-2"></i>Register Your Shop
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container mt-4">
        <div class="main-container">
            <!-- Search Section -->
            <div class="search-section">
                <h1 class="search-title">
                    <i class="fas fa-search me-3"></i>Track Your Repair
                </h1>
                <p class="search-subtitle">Enter your customer number to check repair status</p>
                
                <form class="search-form" method="GET">
                    <div class="mb-3">
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
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="card-title mb-0">
                                            <i class="fas fa-ticket-alt me-2"></i>Repair Ticket #{{ $repair->customer_number }}
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
                                    
                                    <p class="text-muted mb-3">
                                        <i class="fas fa-calendar-alt me-2"></i>
                                        Received on {{ $repair->created_at->format('M j, Y') }}
                                    </p>
                                    
                                    <div class="device-info">
                                        <h5 class="mb-3">
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
                                        <h5 class="mb-3">
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
                    <div class="text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-4" style="opacity: 0.5;"></i>
                        <h4 class="text-muted">Enter your customer number to track your repair</h4>
                        <p class="text-muted">Check the status of your device repair in real-time</p>
                    </div>
                @endif
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
                    <div class="text-center mb-4">
                        <i class="fas fa-tools fa-3x text-primary mb-3"></i>
                        <h4>Do you have a repair shop?</h4>
                        <p class="text-muted">Join our network of trusted repair professionals and start managing your repairs online.</p>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-user-plus fa-2x text-primary mb-3"></i>
                                    <h5>New Registration</h5>
                                    <p class="text-muted">Register your repair shop and get access to our management system.</p>
                                    <button class="btn btn-primary">Register Now</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-headset fa-2x text-success mb-3"></i>
                                    <h5>Contact Admin</h5>
                                    <p class="text-muted">Need help with registration? Our admin team is here to assist you.</p>
                                    <a href="https://wa.me/94765645303" target="_blank" class="btn btn-success">
                                        <i class="fab fa-whatsapp"></i> Contact Admin
                                        </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 p-3 bg-light rounded">
                        <h6><i class="fas fa-star me-2"></i>Benefits of Joining:</h6>
                        <ul class="list-unstyled">
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
        });
    </script>
</body>
</html>