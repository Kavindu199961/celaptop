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
            --primary-color: rgb(6, 6, 132);
            --primary-dark: rgb(3, 3, 78);
            --secondary-color: rgb(49, 46, 63);
            --accent-color: #f59e0b;
            --text-dark: #1f2937;
            --text-muted: #6b7280;
            --bg-light: rgba(255, 255, 255, 0.98);
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
            background-image: linear-gradient(135deg, rgba(0, 0, 0, 0.85), rgba(49, 46, 63, 0.85)),
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
            max-width: 900px; /* Increased width for two columns */
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
            box-shadow: 0 8px 25px rgba(6, 6, 132, 0.3);
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
            box-shadow: 0 0 0 0.2rem rgba(6, 6, 132, 0.1);
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
            box-shadow: 0 8px 25px rgba(6, 6, 132, 0.4);
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

        /* Payment Section */
        .payment-section {
            background: rgba(6, 6, 132, 0.05);
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 1.5rem;
            border: 1px dashed var(--primary-color);
        }

        .payment-title {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }

        .payment-title i {
            margin-right: 0.5rem;
        }

        .bank-details {
            background: rgba(6, 6, 132, 0.08);
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .bank-details-title {
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
        }

        .bank-details-list {
            list-style: none;
            padding-left: 0;
        }

        .bank-details-list li {
            margin-bottom: 0.5rem;
            display: flex;
        }

        .bank-details-list li strong {
            min-width: 120px;
            display: inline-block;
        }

        .contact-note {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-top: 1rem;
            text-align: center;
            font-style: italic;
        }

        /* File Upload */
        .file-upload {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1.5rem;
            border: 2px dashed #e5e7eb;
            border-radius: 12px;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-upload:hover {
            border-color: var(--primary-color);
        }

        .file-upload i {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .file-upload input {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .file-name {
            margin-top: 0.5rem;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        /* Payment Method Tabs */
        .payment-method-tabs {
            display: flex;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .payment-method-tab {
            flex: 1;
            text-align: center;
            padding: 0.75rem;
            background: #e5e7eb;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .payment-method-tab.active {
            background: var(--primary-color);
            color: white;
        }

        .payment-method-content {
            display: none;
        }

        .payment-method-content.active {
            display: block;
        }

        /* Two Column Layout */
        .payment-container {
            display: flex;
            gap: 1.5rem;
        }

        .payment-info {
            flex: 1;
            background: rgba(6, 6, 132, 0.03);
            border-radius: 12px;
            padding: 1.5rem;
        }

        .payment-details {
            flex: 1;
        }

        .payment-summary {
            background: rgba(6, 6, 132, 0.05);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
        }

        .summary-item.total {
            font-weight: 600;
            font-size: 1.1rem;
            border-top: 1px solid rgba(6, 6, 132, 0.1);
            padding-top: 0.75rem;
            margin-top: 0.75rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .payment-container {
                flex-direction: column;
            }
        }

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

        .password-requirements {
    background-color: rgba(6, 6, 132, 0.03);
    border-radius: 8px;
    padding: 0.75rem;
    margin-top: 0.5rem;
}

.password-requirements ul {
    margin-top: 0.5rem;
    margin-bottom: 0;
}

.password-requirements .req-item {
    margin-bottom: 0.25rem;
    font-size: 0.8rem;
    display: flex;
    align-items: center;
}

.password-requirements .req-item.valid {
    color: #28a745;
}

.password-requirements .req-item.valid i {
    color: #28a745 !important;
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

                <form method="POST" action="{{ route('register') }}" id="registerForm" enctype="multipart/form-data">
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

                    <!-- Password Requirements -->
                    <div class="password-requirements mb-2">
                        <small class="text-muted">Password must contain:</small>
                        <ul class="list-unstyled" id="passwordRequirements">
                            <li class="req-item" data-req="length"><i class="bi bi-x-circle-fill text-danger me-1"></i> <span>At least 8 characters</span></li>
                            <li class="req-item" data-req="lowercase"><i class="bi bi-x-circle-fill text-danger me-1"></i> <span>1 lowercase letter</span></li>
                            <li class="req-item" data-req="uppercase"><i class="bi bi-x-circle-fill text-danger me-1"></i> <span>1 uppercase letter</span></li>
                            <li class="req-item" data-req="number"><i class="bi bi-x-circle-fill text-danger me-1"></i> <span>1 number</span></li>
                            <li class="req-item" data-req="special"><i class="bi bi-x-circle-fill text-danger me-1"></i> <span>1 special character (@$!%*#?&)</span></li>
                        </ul>
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

                    <!-- Payment Section -->
                    <div class="payment-section">
                        <h3 class="payment-title">
                            <i class="bi bi-credit-card"></i> Payment Information
                        </h3>

                        <div class="payment-container">
                            <!-- Left Column - Payment Details -->
                            <div class="payment-info">
                                <div class="payment-summary">
                                    <h5 class="mb-3"><i class="bi bi-receipt me-2"></i>Order Summary</h5>
                                    <div class="summary-item">
                                        <span>Registration Fee</span>
                                        <span>LKR 50,000.00</span>
                                    </div>
                                    <div class="summary-item">
                                        <span>Service Charge</span>
                                        <span>LKR 5,000.00</span>
                                    </div>
                                    <div class="summary-item total">
                                        <span>Total Amount</span>
                                        <span>LKR 55,000.00</span>
                                    </div>
                                </div>

                                <!-- Payment Method -->
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="bi bi-wallet2 me-1"></i> Payment Method
                                    </label>
                                    
                                    <div class="payment-method-tabs">
                                        <div class="payment-method-tab active" data-tab="bank_transfer">
                                            Bank Transfer
                                        </div>
                                        <div class="payment-method-tab" data-tab="cash_deposit">
                                            Cash Deposit
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" name="payment_method" id="payment_method" value="bank_transfer">
                                    
                                    <!-- Bank Transfer Content -->
                                    <div class="payment-method-content active" id="bank_transfer_content">
                                        <div class="bank-details">
                                            <h4 class="bank-details-title">
                                                <i class="bi bi-bank"></i> Our Bank Details
                                            </h4>
                                            <ul class="bank-details-list">
                                                <li><strong>Bank Name:</strong> Commercial Bank</li>
                                                <li><strong>Account Name:</strong> CE Laptop Repair</li>
                                                <li><strong>Account Number:</strong> 1234567890</li>
                                                <li><strong>Branch:</strong> Colombo Main</li>
                                            </ul>
                                        </div>
                                    </div>
                                    
                                    <!-- Cash Deposit Content -->
                                    <div class="payment-method-content" id="cash_deposit_content">
                                        <div class="alert alert-info">
                                            <i class="bi bi-info-circle me-2"></i>
                                            Please visit our shop to complete your cash deposit payment.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column - User Payment Details -->
                            <div class="payment-details">
                                <!-- Amount (hidden field) -->
                                <input type="hidden" name="amount" value="55000">
                                
                                <!-- Bank Name -->
                                <div class="form-group">
                                    <label for="bank_name" class="form-label">
                                        <i class="bi bi-bank2 me-1"></i> Your Bank Name
                                    </label>
                                    <div class="input-group">
                                        <i class="bi bi-bank2 input-icon"></i>
                                        <input type="text" id="bank_name" name="bank_name" class="form-control @error('bank_name') is-invalid @enderror"
                                               placeholder="Enter your bank name" value="{{ old('bank_name') }}">
                                    </div>
                                    @error('bank_name')
                                        <div class="text-danger mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Account Number -->
                                <div class="form-group">
                                    <label for="account_number" class="form-label">
                                        <i class="bi bi-credit-card-2-back me-1"></i> Your Account Number
                                    </label>
                                    <div class="input-group">
                                        <i class="bi bi-credit-card-2-back input-icon"></i>
                                        <input type="text" id="account_number" name="account_number" class="form-control @error('account_number') is-invalid @enderror"
                                               placeholder="Enter your account number" value="{{ old('account_number') }}">
                                    </div>
                                    @error('account_number')
                                        <div class="text-danger mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Bank Slip Upload -->
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="bi bi-file-earmark-arrow-up me-1"></i> Upload Bank Slip
                                    </label>
                                    <div class="file-upload" id="fileUpload">
                                        <i class="bi bi-cloud-arrow-up"></i>
                                        <span>Click to upload slip (PDF, PNG, JPG)</span>
                                        <span class="file-name" id="fileName">No file chosen</span>
                                        <input type="file" id="slip" name="slip" accept=".pdf,.png,.jpg,.jpeg">
                                    </div>
                                    @error('slip')
                                        <div class="text-danger mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Remarks -->
                                <div class="form-group">
                                    <label for="remarks" class="form-label">
                                        <i class="bi bi-chat-left-text me-1"></i> Remarks
                                    </label>
                                    <textarea id="remarks" name="remarks" class="form-control @error('remarks') is-invalid @enderror"
                                              placeholder="Add shop name and your name for reference" required>{{ old('remarks') }}</textarea>
                                    @error('remarks')
                                        <div class="text-danger mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="contact-note">
                            <i class="bi bi-telephone me-1"></i> Payment not approved? Contact us: 0765645303
                        </div>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn btn-register" id="registerBtn">
                        <span class="btn-text"><i class="bi bi-person-plus me-2"></i>Complete Registration</span>
                    </button>

                    <!-- Login Link -->
                    <div class="login-link">
                        Already have an account? <a href="/"><i class="bi bi-box-arrow-in-right me-1"></i>Sign In</a>
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
            btnText.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
            btn.disabled = true;
            setTimeout(() => {
                if (btn.disabled) {
                    btnText.innerHTML = '<i class="bi bi-person-plus me-2"></i>Complete Registration';
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
            if (domain && blockedDomains.includes(domain)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid Email Domain',
                    text: 'Please use a real email address. Disposable emails are not allowed.',
                    iconColor: '#f59e0b',
                    confirmButtonColor: '#0d6efd'
                });
            }
        });

        // File upload display
        document.getElementById('slip').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'No file chosen';
            document.getElementById('fileName').textContent = fileName;
        });

        // Payment method tabs
        const tabs = document.querySelectorAll('.payment-method-tab');
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs
                tabs.forEach(t => t.classList.remove('active'));
                
                // Add active class to clicked tab
                this.classList.add('active');
                
                // Get tab ID
                const tabId = this.getAttribute('data-tab');
                
                // Hide all content
                document.querySelectorAll('.payment-method-content').forEach(content => {
                    content.classList.remove('active');
                });
                
                // Show selected content
                document.getElementById(tabId + '_content').classList.add('active');
                
                // Update hidden input
                document.getElementById('payment_method').value = tabId;
            });
        });

        document.getElementById('password').addEventListener('input', function() {
            const value = this.value;
            const errorBox = document.querySelector('#passwordError');
            const requirements = {
                length: value.length >= 8,
                lowercase: /[a-z]/.test(value),
                uppercase: /[A-Z]/.test(value),
                number: /[0-9]/.test(value),
                special: /[@$!%*#?&]/.test(value)
            };
            
            // Update requirement indicators
            Object.keys(requirements).forEach(key => {
                const item = document.querySelector(`.req-item[data-req="${key}"]`);
                const icon = item.querySelector('i');
                const text = item.querySelector('span');
                
                if (requirements[key]) {
                    item.classList.add('valid');
                    icon.classList.remove('bi-x-circle-fill', 'text-danger');
                    icon.classList.add('bi-check-circle-fill', 'text-success');
                } else {
                    item.classList.remove('valid');
                    icon.classList.remove('bi-check-circle-fill', 'text-success');
                    icon.classList.add('bi-x-circle-fill', 'text-danger');
                }
            });
            
            // Check if all requirements are met
            const allValid = Object.values(requirements).every(v => v);
            
            if (value.length > 0 && !allValid) {
                errorBox.innerHTML = '<i class="bi bi-exclamation-circle me-1"></i>Please meet all password requirements';
                errorBox.style.display = "block";
                this.style.borderColor = '#ef4444';
            } else if (value.length > 0 && allValid) {
                errorBox.style.display = "none";
                this.style.borderColor = '#10b981';
            } else {
                errorBox.style.display = "none";
                this.style.borderColor = '#e5e7eb';
            }
        });

        // Confirm password validation
        document.getElementById('password-confirm').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (confirmPassword.length > 0 && password !== confirmPassword) {
                this.style.borderColor = '#ef4444';
            } else if (confirmPassword.length > 0) {
                this.style.borderColor = '#10b981';
            } else {
                this.style.borderColor = '#e5e7eb';
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