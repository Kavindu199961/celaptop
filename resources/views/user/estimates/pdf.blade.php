<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Estimate #{{ $estimate->estimate_number }}</title>
    <style>
        @page {
            size: 210mm 148.5mm; /* Half A4 size */
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

        .section {
            line-height: 0.4;
            text-align: right;
        }

        .details {
            line-height: 0.5;
        }

        .table tfoot td {
            font-size: 11px;
            font-weight: bold;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="estimate-container">
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
                    $maxRows = 10;
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
</body>
</html>