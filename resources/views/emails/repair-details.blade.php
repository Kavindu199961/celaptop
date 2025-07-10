<!DOCTYPE html>
<html>
<head>
    <title>Your Repair Details</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #444;
            max-width: 600px;
            margin: 0 auto;
            padding: 0;
            background-color: #f9f9f9;
        }
        .container {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            margin: 20px 0;
        }
        .header {
            background: linear-gradient(135deg, #6e8efb, #a777e3);
            padding: 30px 20px;
            text-align: center;
            color: white;
        }
        .content {
            padding: 30px;
        }
        .detail-box {
            background: #f5f7ff;
            border-radius: 6px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .detail-item {
            display: flex;
            margin-bottom: 12px;
        }
        .detail-label {
            font-weight: bold;
            color: #555;
            min-width: 120px;
        }
        .status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
            background: #e1f5fe;
            color: #0288d1;
        }
        .tracking-number {
            font-size: 18px;
            font-weight: bold;
            color: #6e8efb;
            text-align: center;
            margin: 20px 0;
            padding: 15px;
            border: 2px dashed #e1f5fe;
            border-radius: 8px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #999;
            padding: 20px;
        }
        .signature {
            margin-top: 30px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Your Repair Details</h2>
        </div>
        
        <div class="content">
            <p>Hello {{ $repair->customer_name }},</p>
            
            <p>Here's your repair information. Keep this for tracking:</p>
            
            <div class="tracking-number">
                Your Repair ID: <strong>{{ $repair->customer_number }}</strong>
            </div>
            
            <div class="detail-box">
                <div class="detail-item">
                    <span class="detail-label">Device:</span>
                    <span>{{ $repair->device }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Serial No:</span>
                    <span>{{ $repair->serial_number }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Issue:</span>
                    <span>{{ $repair->fault }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Status:</span>
                    <span class="status">{{ ucfirst(str_replace('_', ' ', $repair->status)) }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Est. Price:</span>
                    <span>
                        @if($repair->repair_price)
                            ${{ number_format($repair->repair_price, 2) }}
                        @else
                            Pending assessment
                        @endif
                    </span>
                </div>
            </div>
            
            <p>We'll notify you when your device is ready for collection.</p>
            
        </div>
    </div>
    
    <div class="footer">
        <p>This is an automated message. Please contact us for any questions.</p>
    </div>
</body>
</html>