@extends('layouts.app')

@section('content')
<div class="col-12 col-md-12 col-lg-12">
    <div class="card">
        <div class="card-header">
            <h4>Credit Invoice Details</h4>
            <div class="card-header-action">
                <a href="{{ route('user.credit_invoices.print', $creditInvoice->id) }}" 
                   class="btn btn-success" target="_blank">
                    <i class="fas fa-print"></i> Print
                </a>
                <a href="{{ route('user.credit_invoices.download', $creditInvoice->id) }}" 
                   class="btn btn-primary" target="_blank">
                    <i class="fas fa-download"></i> Download PDF
                </a>
                <a href="{{ route('user.credit_invoices.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Shop Information</h5>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th width="120">Shop Name</th>
                            <td>{{ $creditInvoice->creditShop->name }}</td>
                        </tr>
                        <tr>
                            <th>Contact</th>
                            <td>{{ $creditInvoice->creditShop->contact ?? '--' }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{ $creditInvoice->creditShop->address ?? '--' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5>Invoice Information</h5>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th width="120">Invoice #</th>
                            <td>{{ \Carbon\Carbon::parse($creditInvoice->issue_date)->format('Y-m-d') }}</td>

                        </tr>
                        <tr>
                            <th>Date</th>
                            <td>{{ \Carbon\Carbon::parse($creditInvoice->issue_date)->format('Y-m-d') }}</td>

                        </tr>
                        <tr>
                            <th>Sales Rep</th>
                            <td>{{ $creditInvoice->sales_rep }}</td>
                        </tr>
                        <!-- <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge 
                                    @if($creditInvoice->status == 'paid') badge-success
                                    @elseif($creditInvoice->status == 'partial') badge-warning
                                    @else badge-danger @endif">
                                    {{ ucfirst($creditInvoice->status) }}
                                </span>
                            </td>
                        </tr> -->
                    </table>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Customer Information</h5>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th width="120"></th>
                            <td>{{ $creditInvoice->customer_name }}</td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>{{ $creditInvoice->customer_phone ?? '--' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5>Payment Summary</h5>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th width="120">Total Credit Amount</th>
                            <td>{{ number_format($creditInvoice->total_amount, 2) }}</td>
                        </tr>
                        <!-- <tr>
                            <th>Paid Amount</th>
                            <td>{{ number_format($creditInvoice->paid_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Remaining</th>
                            <td>{{ number_format($creditInvoice->remaining_amount, 2) }}</td>
                        </tr> -->
                    </table>
                </div>
            </div>

            <h5>Invoice Items</h5>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Description</th>
                            <th>Warranty</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($creditInvoice->items as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->description }}</td>
                            <td>{{ $item->warranty ?? 'N/A' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->unit_price, 2) }}</td>
                            <td>{{ number_format($item->amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5" class="text-right">Total Credit Amount:</th>
                            <th>{{ number_format($creditInvoice->total_amount, 2) }}</th>
                        </tr>
                        <!-- <tr>
                            <th colspan="5" class="text-right">Paid Amount:</th>
                            <th>{{ number_format($creditInvoice->paid_amount, 2) }}</th>
                        </tr>
                        <tr>
                            <th colspan="5" class="text-right">Remaining Balance:</th>
                            <th>{{ number_format($creditInvoice->remaining_amount, 2) }}</th>
                        </tr> -->
                    </tfoot>
                </table>
            </div>

            <!-- Payment History Section -->
            @if($creditInvoice->payments && $creditInvoice->payments->count() > 0)
            <h5 class="mt-4">Payment History</h5>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Notes</th>
                            <th>Recorded At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($creditInvoice->payments as $payment)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                            <td>{{ number_format($payment->amount, 2) }}</td>
                            <td>{{ ucfirst($payment->payment_method) }}</td>
                            <td>{{ $payment->notes ?? 'N/A' }}</td>
                            <td>{{ $payment->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            <!-- Add Payment Form (if invoice not fully paid)
            @if($creditInvoice->remaining_amount > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Record Payment</h5>
                </div>
                <div class="card-body">
                    <form  method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Payment Date</label>
                                    <input type="date" class="form-control" name="payment_date" 
                                           value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Amount</label>
                                    <input type="number" class="form-control" name="amount" 
                                           min="0.01" max="{{ $creditInvoice->remaining_amount }}" 
                                           step="0.01" value="{{ $creditInvoice->remaining_amount }}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Payment Method</label>
                                    <select class="form-control" name="payment_method" required>
                                        <option value="cash">Cash</option>
                                        <option value="bank">Bank Transfer</option>
                                        <option value="cheque">Cheque</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Notes</label>
                                    <input type="text" class="form-control" name="notes">
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Record Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        </div> -->

        <div class="card-footer text-right">
            <small class="text-muted">
                Created: {{ $creditInvoice->created_at->format('Y-m-d H:i') }} | 
                Last Updated: {{ $creditInvoice->updated_at->format('Y-m-d H:i') }}
            </small>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize any scripts needed for this page
    });
</script>
@endpush