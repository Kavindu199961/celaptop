<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
       @page {
            size: A4;
            margin: 0;
        }


        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            margin: 0;
            padding: 10px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            box-sizing: border-box;
        }

        .invoice-container {
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
    <div class="invoice-container">
        <div class="header">
            <table style="width: 100%; border: none; table-layout: fixed;">
                <tr>
                   <td style="width: 15%; text-align: left;">
                        @if($logoPath)
                            <img src="{{ $logoPath }}" alt="Logo" style="width: 70px; height: auto;">
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
                        <p><strong>Invoice To:</strong> {{ $invoice->customer_name }}</p>
                        <p><strong>Phone Number:</strong> {{ $invoice->customer_phone }}</p>
                    </div>
                </td>
                <td>
                    <div class="section">
                        <p><strong>Invoice No:</strong> {{ $invoice->invoice_number }}</p>
                        <p><strong>Issue Date:</strong> {{ \Carbon\Carbon::parse($invoice->issue_date)->format('Y-m-d') }}</p>
                        <p><strong>Sales Rep:</strong> {{ $invoice->sales_rep }}</p>
                        <p><strong>Payment Method:</strong> Cash</p>
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
                    $items = $invoice->items;
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
                    <td>{{ number_format($invoice->total_amount, 2) }}</td>
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

    
</body>
</html>
