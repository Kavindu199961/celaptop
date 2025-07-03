@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-center">
            <h2 class="card-title">Invoice With Stock #<strong>{{ $invoiceWithStock->invoice_number }}</strong></h2>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Customer Information</h5>
                    <p><strong>Name:</strong> {{ $invoiceWithStock->customer_name }}</p>
                    <p><strong>Phone:</strong> {{ $invoiceWithStock->customer_phone }}</p>
                    <p><strong>Issued on:</strong> {{ \Carbon\Carbon::parse($invoiceWithStock->issue_date)->format('Y-m-d') }}</p>
                </div>
                <div class="col-md-6 text-end">
                    <h5>Sales Representative</h5>
                    <p><strong>Name:</strong> {{ $invoiceWithStock->sales_rep }}</p>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Item Name</th>
                            <th>Warranty</th>
                            <th>Qty</th>
                            <th>Unit Price</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoiceWithStock->items as $item)
                            <tr>
                                <td>{{ $item->stock->item_name ?? 'N/A' }}</td> 
                                <td>{{ $item->warranty ?? 'N/A' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->unit_price, 2) }}</td>
                                <td>{{ number_format($item->amount, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end"><strong>Total Amount:</strong></td>
                            <td><strong>{{ number_format($invoiceWithStock->total_amount, 2) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

        <div class="d-flex justify-content-end mt-4">
            <a href="{{ route('user.invoices_with_stock.print', $invoiceWithStock->id) }}" class="btn btn-success me-2">
                <i class="fas fa-print me-1"></i> Print Invoice
            </a>
            <a href="{{ route('user.invoices_with_stock.download', $invoiceWithStock->id) }}" class="btn btn-primary ml-2">
                <i class="fas fa-download me-1"></i> Download PDF
            </a>
        </div>

        </div>
    </div>
</div>
@endsection