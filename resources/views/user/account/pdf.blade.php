<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Account Statement</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f8f9fa; font-weight: bold; }
        .text-right { text-align: right; }
        .text-success { color: #28a745; }
        .text-danger { color: #dc3545; }
        .summary { margin-bottom: 20px; padding: 10px; background-color: #f8f9fa; border-radius: 5px; }
        .filter-info { margin-bottom: 15px; padding: 10px; background-color: #e9ecef; border-radius: 5px; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #6c757d; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Account Statement</h2>
        <p>Generated on: {{ date('Y-m-d H:i:s') }}</p>
    </div>

    <!-- Filter Information -->
    @if($filterInfo['search'] || $filterInfo['date'] || $filterInfo['start_date'] || $filterInfo['month'] || $filterInfo['year'])
    <div class="filter-info">
        <strong>Filters Applied:</strong>
        @if($filterInfo['search']) Search: "{{ $filterInfo['search'] }}" | @endif
        @if($filterInfo['date']) Date: {{ $filterInfo['date'] }} | @endif
        @if($filterInfo['start_date']) Period: {{ $filterInfo['start_date'] }} to {{ $filterInfo['end_date'] }} | @endif
        @if($filterInfo['month']) Month: {{ $filterInfo['month'] }} | @endif
        @if($filterInfo['year']) Year: {{ $filterInfo['year'] }} @endif
    </div>
    @endif

    <!-- Summary -->
    <div class="summary">
        <strong>Summary:</strong>
        Total Income: Rs {{ number_format($totalIncome, 2) }} | 
        Total Expense: Rs {{ number_format(abs($totalExpense), 2) }} | 
        Net Balance: Rs {{ number_format($netBalance, 2) }}
    </div>

    <!-- Account Table -->
    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th class="text-right">Amount (Rs)</th>
                <th>Type</th>
            </tr>
        </thead>
        <tbody>
            @foreach($accounts as $account)
            <tr>
                <td>{{ $account->date->format('Y-m-d') }}</td>
                <td>{{ $account->description }}</td>
                <td class="text-right {{ $account->amount >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ number_format($account->amount, 2) }}
                </td>
                <td>
                    @if($account->amount >= 0)
                        Income
                    @else
                        Expense
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" style="text-align: right; font-weight: bold;">Net Amount:</td>
                <td colspan="2" class="text-right {{ $netBalance >= 0 ? 'text-success' : 'text-danger' }}" style="font-weight: bold;">
                    Rs {{ number_format($netBalance, 2) }}
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        This report was generated automatically from the Account Management System.
    </div>
</body>
</html>