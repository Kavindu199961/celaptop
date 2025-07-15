<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Estimate #{{ $estimate->estimate_number }}</title>
    <style>
        @page {
            size: 210mm 148.5mm; /* Half A4 size (210mm x 148.5mm) */
            margin: 0;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            padding: 10px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            box-sizing: border-box;
        }

        .estimate-container {
            width: 195mm;
            padding: 5mm;
            border: 1px solid #000;
            box-sizing: border-box;
        }

        .header {
            margin-bottom: 10px;
        }

        .table-layout {
            width: 100%;
            border-collapse: collapse;
        }

        .table-layout td {
            vertical-align: top;
            padding: 5px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        .table th, .table td {
            border: 1px solid #ddd;
            padding: 2px;
            text-align: left;
        }

        .table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .table tbody tr:hover {
            background-color: #e9e9e9;
        }

        .table tfoot td {
            font-size: 11px;
            font-weight: bold;
            text-align: right;
        }

        .section {
            line-height: 0.4;
            text-align: right;
        }

        .details {
            line-height: 0.5;
        }

        @media screen {
            .button-container {
                position: fixed;
                bottom: 20px;
                right: 20px;
                display: flex;
                gap: 10px;
                z-index: 1000;
            }

            .action-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 8px 16px;
                background-color: #4CAF50;
                color: white;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 14px;
                transition: background-color 0.3s;
            }

            .action-btn:hover {
                background-color: #45a049;
            }

            .close-btn {
                background-color: #f44336;
            }

            .close-btn:hover {
                background-color: #d32f2f;
            }

            .btn-icon {
                width: 18px;
                height: 18px;
                fill: currentColor;
                margin-right: 8px;
            }
        }

        @media print {
            .button-container {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="estimate-container">
        <div class="header">
            <table style="width: 100%; border: none; table-layout: fixed;">
                <tr>
                    <td style="width: 15%; text-align: left;">
                        @if($shopDetail && $shopDetail->logo_image)
                            <img src="{{ asset('storage/' . $shopDetail->logo_image) }}" alt="Logo" style="width: 70px; height: auto;">
                        @endif
                    </td>
                    <td style="width: 450%; text-align: center;">
                        <h1 style="margin: 0; font-size: 18px; text-transform: uppercase;">
                            {{ strtoupper($shopDetail->shop_name ?? 'SHOP NAME') }}
                        </h1>
                        <p style="margin: 2px 0; font-size: 10px;">
                            {{ $shopDetail->description ?? 'Shop Description' }}
                        </p>
                        <p style="margin: 2px 0; font-size: 10px;">
                            {{ $shopDetail->address ?? 'Shop Address' }}
                        </p>
                        <p style="margin: 2px 0; font-size: 10px;">
                            <strong>
                                | Hotline: {{ $shopDetail->hotline ?? 'Hotline' }} | 
                                Email : {{ $shopDetail->email ?? 'Email' }} |
                            </strong>
                        </p>
                    </td>
                </tr>
            </table>
        </div>

        <table class="table-layout">
            <tr>
                <td>
                    <div class="details">
                        <p><strong>Estimate To:</strong> {{ $estimate->customer_name }}</p>
                        <p><strong>Phone Number:</strong> {{ $estimate->customer_phone }}</p>
                    </div>
                </td>
                <td>
                    <div class="section">
                        <p><strong>Estimate No:</strong> {{ $estimate->estimate_number }}</p>
                        <p><strong>Issue Date:</strong> {{ \Carbon\Carbon::parse($estimate->issue_date)->format('Y-m-d') }}</p>
                        <p><strong>Expiry Date:</strong> {{ \Carbon\Carbon::parse($estimate->expiry_date)->format('Y-m-d') }}</p>
                        <p><strong>Sales Rep:</strong> {{ $estimate->sales_rep }}</p>
                    </div>
                </td>
            </tr>
        </table>

        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Description</th>
                    <th>Warranty</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $maxRows = 43;
                    $items = $estimate->items;
                @endphp
                @for($i = 0; $i < $maxRows; $i++)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $items[$i]->description ?? '' }}</td>
                        <td>{{ $items[$i]->warranty ?? '' }}</td>
                        <td>{{ $items[$i]->quantity ?? '' }}</td>
                        <td>{{ isset($items[$i]) ? number_format($items[$i]->unit_price, 2) : '' }}</td>
                        <td>{{ isset($items[$i]) ? number_format($items[$i]->amount, 2) : '' }}</td>
                    </tr>
                @endfor
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5">Total</td>
                    <td>{{ number_format($estimate->total_amount, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <div style="text-align: center; margin-top: 1px;">
            <p style="margin: 0; font-size: 9px;">Thank You For Choosing Us</p>
            <p style="margin: 0; font-size: 9px; text-align: left;">* {{ $shopDetail->condition_1 ?? '' }}</p>
            <p style="margin: 0; font-size: 9px; text-align: left;">* {{ $shopDetail->condition_2 ?? '' }}</p>
            <p style="margin: 0; font-size: 9px; text-align: left;">* {{ $shopDetail->condition_3 ?? '' }}</p>
        </div>

        <table style="width: 100%; margin-top: 10px; table-layout: fixed;">
            <tr>
                <td style="width: 50%; text-align: left;">
                    <p>__________________________</p>
                    <p style="margin-left: 50px; font-size: 7px;">Authorized By</p>
                </td>
                <td style="width: 50%; text-align: right;">
                    <p>__________________________</p>
                    <p style="margin-right: 50px; font-size: 7px;">Received By</p>
                </td>
            </tr>
        </table>

        <p style="text-align: center; margin-top: -20px;">Powered by CeylonGIT - 070 7645303</p>
    </div>

    <!-- Button Area -->
    <div class="button-container">
        <button class="action-btn print-btn" onclick="printReport()">
            <svg class="btn-icon" viewBox="0 0 24 24">
                <path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/>
            </svg>
            Print Report
        </button>
        <button class="action-btn close-btn" onclick="closeReport()">
            <svg class="btn-icon" viewBox="0 0 24 24">
                <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
            </svg>
            Close
        </button>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };

        function printReport() {
            window.print();
        }

        function closeReport() {
            window.location.href = "{{ route('user.estimates.index') }}";
        }
    </script>
</body>
</html>