// resources/views/admin/invoice/show.blade.php
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h1 class="card-title">Invoice #{{ $invoice->invoice_number }}</h1>
            <p class="card-subtitle">Issued on {{ \Carbon\Carbon::parse($invoice->issue_date)->format('Y-m-d') }}</p>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Customer Information</h5>
                    <p><strong>Name:</strong> {{ $invoice->customer_name }}</p>
                    <p><strong>Phone:</strong> {{ $invoice->customer_phone }}</p>
                </div>
                <div class="col-md-6 text-end">
                    <h5>Sales Representative</h5>
                    <p><strong>Name:</strong> {{ $invoice->sales_rep }}</p>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Warranty</th>
                            <th>Qty</th>
                            <th>Unit Price</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->items as $item)
                        <tr>
                            <td>{{ $item->description }}</td>
                            <td>{{ $item->warranty }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->unit_price, 2) }}</td>
                            <td>{{ number_format($item->amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end"><strong>Total Amount:</strong></td>
                            <td><strong>{{ number_format($invoice->total_amount, 2) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                <a href="{{ route('admin.invoices.print', $invoice->id) }}" class="btn btn-success me-md-2">
                    <i class="fas fa-print me-1"></i> Print Invoice
                </a>
                <a href="{{ route('admin.invoices.download', $invoice->id) }}" class="btn btn-primary">
                    <i class="fas fa-download me-1"></i> Download PDF
                </a>
            </div>
        </div>
    </div>
</div>
@endsection