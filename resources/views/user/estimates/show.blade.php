@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-center">
            <h2 class="card-title">Estimate #<strong>{{ $estimate->estimate_number }}</strong></h2>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Customer Information</h5>
                    <p><strong>Name:</strong> {{ $estimate->customer_name }}</p>
                    <p><strong>Phone:</strong> {{ $estimate->customer_phone }}</p>
                    <p><strong>Issued on:</strong> {{ \Carbon\Carbon::parse($estimate->issue_date)->format('Y-m-d') }}</p>
                    <p><strong>Valid until:</strong> {{ \Carbon\Carbon::parse($estimate->expiry_date)->format('Y-m-d') }}</p>
                </div>
                <div class="col-md-6 text-end">
                    <h5>Sales Representative</h5>
                    <p><strong>Name:</strong> {{ $estimate->sales_rep }}</p>
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
                        @foreach($estimate->items as $item)
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
                            <td><strong>{{ number_format($estimate->total_amount, 2) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                <a href="{{ route('user.estimates.print', $estimate->id) }}" class="btn btn-success me-md-2">
                    <i class="fas fa-print me-1"></i> Print Estimate
                </a>
                <a href="{{ route('user.estimates.download', $estimate->id) }}" class="btn btn-primary ml-1">
                    <i class="fas fa-download me-1"></i> Download PDF
                </a>
            </div>
        </div>
    </div>
</div>
@endsection