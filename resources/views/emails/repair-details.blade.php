<!DOCTYPE html>
<html>
<head>
    <title>Your Repair Details</title>
    <style>
        body {
            font-family: 'Inter', 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.6;
            color: #1a1a1a;
            max-width: 650px;
            margin: 0 auto;
            padding: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .email-wrapper {
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            position: relative;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }
        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }
        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 16px;
            position: relative;
            z-index: 1;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 25px;
            color: #2d3748;
        }
        .customer-number-card {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border-radius: 16px;
            padding: 25px;
            margin: 30px 0;
            text-align: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(79, 172, 254, 0.3);
        }
        .customer-number-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.1) 50%, transparent 70%);
            animation: shimmer 3s infinite;
        }
        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        .customer-number-label {
            color: rgba(255,255,255,0.9);
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .customer-number-value {
            color: white;
            font-size: 32px;
            font-weight: 800;
            letter-spacing: 2px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
            position: relative;
            z-index: 1;
        }
        .details-grid {
            display: grid;
            gap: 20px;
            margin: 30px 0;
        }
        .detail-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            transition: all 0.3s ease;
            position: relative;
        }
        .detail-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            border-color: #667eea;
        }
        .detail-row {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        .detail-row:last-child {
            margin-bottom: 0;
        }
        .detail-icon {
            width: 20px;
            height: 20px;
            margin-right: 12px;
            opacity: 0.7;
        }
        .detail-label {
            font-weight: 600;
            color: #4a5568;
            min-width: 100px;
            font-size: 14px;
        }
        .detail-value {
            color: #2d3748;
            font-weight: 500;
            flex: 1;
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            background: linear-gradient(135deg, #48bb78, #38a169);
            color: white;
            box-shadow: 0 4px 12px rgba(72, 187, 120, 0.3);
        }
        .price-highlight {
            font-size: 18px;
            font-weight: 700;
            color: #667eea;
        }
        .info-section {
            background: linear-gradient(135deg, #edf2f7, #e2e8f0);
            border-radius: 12px;
            padding: 20px;
            margin: 30px 0;
            border-left: 4px solid #667eea;
        }
        .footer {
            background: #f7fafc;
            padding: 25px 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        .footer p {
            margin: 0;
            color: #718096;
            font-size: 14px;
        }
        .contact-info {
            margin-top: 15px;
            font-size: 13px;
            color: #a0aec0;
        }
        @media (max-width: 600px) {
            .email-wrapper {
                padding: 10px;
            }
            .content, .header {
                padding: 25px 20px;
            }
            .customer-number-value {
                font-size: 24px;
            }
            .detail-row {
                flex-direction: column;
                align-items: flex-start;
            }
            .detail-label {
                min-width: auto;
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="container">
            <div class="header">
                <h1>🔧 Repair Status Update</h1>
                <p>Your device is in expert hands</p>
            </div>
            
            <div class="content">
                <div class="greeting">
                    Hello <strong>{{ $repair->customer_name }}</strong>,
                </div>
                
                <p>Your repair request has been received and is being processed. Here are your complete repair details:</p>
                
                <div class="customer-number-card">
                    <div class="customer-number-label">Your Repair Tracking ID</div>
                    <div class="customer-number-value">{{ $repair->customer_number }}</div>
                </div>
                
                <div class="details-grid">
                    <div class="detail-card">
                        <div class="detail-row">
                            <svg class="detail-icon" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M17,17H7V7H17M21,11V9A2,2 0 0,0 19,7H17V5A2,2 0 0,0 15,3H9A2,2 0 0,0 7,5V7H5A2,2 0 0,0 3,9V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V13H19V19H5V9H7V11A2,2 0 0,0 9,13H15A2,2 0 0,0 17,11V9H19V11H21M15,5V11H9V5H15Z"/>
                            </svg>
                            <span class="detail-label">Device:</span>
                            <span class="detail-value">{{ $repair->device }}</span>
                        </div>
                        <div class="detail-row">
                            <svg class="detail-icon" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2M12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20A8,8 0 0,0 20,12A8,8 0 0,0 12,4M12,6A6,6 0 0,1 18,12A6,6 0 0,1 12,18A6,6 0 0,1 6,12A6,6 0 0,1 12,6M12,8A4,4 0 0,0 8,12A4,4 0 0,0 12,16A4,4 0 0,0 16,12A4,4 0 0,0 12,8Z"/>
                            </svg>
                            <span class="detail-label">Serial No:</span>
                            <span class="detail-value">{{ $repair->serial_number }}</span>
                        </div>
                        <div class="detail-row">
                            <svg class="detail-icon" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M13,13H11V7H13M11,15H13V17H11M15.73,3H8.27L3,8.27V15.73L8.27,21H15.73L21,15.73V8.27L15.73,3Z"/>
                            </svg>
                            <span class="detail-label">Issue:</span>
                            <span class="detail-value">{{ $repair->fault }}</span>
                        </div>
                        <div class="detail-row">
                            <svg class="detail-icon" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2M12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20A8,8 0 0,0 20,12A8,8 0 0,0 12,4M12,6A6,6 0 0,1 18,12A6,6 0 0,1 12,18A6,6 0 0,1 6,12A6,6 0 0,1 12,6M12,8A4,4 0 0,0 8,12A4,4 0 0,0 12,16A4,4 0 0,0 16,12A4,4 0 0,0 12,8Z"/>
                            </svg>
                            <span class="detail-label">Status:</span>
                            <span class="detail-value">
                                <span class="status-badge">{{ ucfirst(str_replace('_', ' ', $repair->status)) }}</span>
                            </span>
                        </div>
                        <div class="detail-row">
                            <svg class="detail-icon" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M7,15H9C9,16.08 10.37,17 12,17C13.63,17 15,16.08 15,15C15,13.9 13.96,13.5 11.76,12.97C9.64,12.44 7,11.78 7,9C7,7.21 8.47,5.69 10.5,5.18V3H13.5V5.18C15.53,5.69 17,7.21 17,9H15C15,7.92 13.63,7 12,7C10.37,7 9,7.92 9,9C9,10.1 10.04,10.5 12.24,11.03C14.36,11.56 17,12.22 17,15C17,16.79 15.53,18.31 13.5,18.82V21H10.5V18.82C8.47,18.31 7,16.79 7,15Z"/>
                            </svg>
                            <span class="detail-label">Est. Price:</span>
                            <span class="detail-value price-highlight">
                                @if($repair->repair_price)
                                    ${{ number_format($repair->repair_price, 2) }}
                                @else
                                    Pending assessment
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="info-section">
                    <p><strong>📱 What happens next?</strong></p>
                    <p>Our technicians will assess your device and provide updates on the repair progress. You'll receive notifications at each stage, and we'll contact you as soon as your device is ready for collection.</p>
                </div>
                
                <p style="margin-top: 30px; color: #4a5568;">
                    <strong>💡 Pro tip:</strong> Save your tracking ID <strong>{{ $repair->customer_number }}</strong> for quick reference when contacting us.
                </p>
            </div>
            
            <div class="footer">
                <p><strong>Questions about your repair?</strong></p>
                <p>Contact our support team with your tracking ID for instant assistance.</p>
                <div class="contact-info">
                    This is an automated message • Please keep this email for your records
                </div>
            </div>
        </div>
    </div>
</body>
</html>