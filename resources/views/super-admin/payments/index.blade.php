@extends('layouts.super-admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">
                        <i class="bi bi-credit-card"></i> Payment Approvals
                    </h3>
                </div>
                
                <div class="card-body">
                    <!-- Search Form -->
                    <form action="{{ route('super-admin.payments.index') }}" method="GET" class="mb-4">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search by user name, email, or ID..." value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i> Search
                                </button>
                                @if(request('search'))
                                    <a href="{{ route('super-admin.payments.index') }}" class="btn btn-outline-danger">Clear</a>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Filter Options -->
                        <div class="row mt-2">
                            <div class="col-md-3">
                                <select name="status" class="form-control form-control-sm">
                                    <option value="">All Statuses</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="method" class="form-control form-control-sm">
                                    <option value="">All Methods</option>
                                    <option value="bank_transfer" {{ request('method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="cash_deposit" {{ request('method') == 'cash_deposit' ? 'selected' : '' }}>Cash Deposit</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="from_date" class="form-control form-control-sm" value="{{ request('from_date') }}" placeholder="From Date">
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="to_date" class="form-control form-control-sm" value="{{ request('to_date') }}" placeholder="To Date">
                            </div>
                        </div>
                    </form>
                    
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="payments-table">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Bank Details</th>
                                    <th>Slip</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $payment)
                                <tr>
                                    <td>{{ $payment->id }}</td>
                                    <td>
                                        <strong>{{ $payment->user->name }}</strong><br>
                                        <small class="text-muted">{{ $payment->user->email }}</small>
                                    </td>
                                    <td class="font-weight-bold">LKR {{ number_format($payment->amount, 2) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $payment->payment_method === 'bank_transfer' ? 'info' : 'warning' }}">
                                            {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($payment->payment_method === 'bank_transfer')
                                            <small class="text-muted">
                                                <strong>Bank:</strong> {{ $payment->bank_name }}<br>
                                                <strong>Account:</strong> {{ $payment->account_number }}
                                            </small>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($payment->slip_path)
                                            <a href="{{ Storage::url($payment->slip_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $payment->status === 'approved' ? 'success' : ($payment->status === 'rejected' ? 'danger' : 'secondary') }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                        @if($payment->status === 'rejected' && $payment->reject_reason)
                                            <br><small class="text-muted" title="{{ $payment->reject_reason }}">
                                                <i class="fas fa-info-circle"></i> Reason provided
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $payment->created_at->format('M d, Y h:i A') }}</small>
                                    </td>
                                    <td>
                                        @if($payment->status === 'pending')
                                            <div class="btn-group btn-group-sm">
                                                <form action="{{ route('super-admin.payments.approve', $payment->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success" title="Approve">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <button class="btn btn-danger" data-toggle="modal" data-target="#rejectModal{{ $payment->id }}" title="Reject">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                <!-- <button class="btn btn-info" data-toggle="modal" data-target="#remarksModal{{ $payment->id }}" title="Add Remarks">
                                                    <i class="fas fa-comment"></i>
                                                </button> -->
                                            </div>

                                            <!-- Reject Modal -->
                                            <div class="modal fade" id="rejectModal{{ $payment->id }}" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-danger text-white">
                                                            <h5 class="modal-title" id="rejectModalLabel">Reject Payment #{{ $payment->id }}</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{ route('super-admin.payments.reject', $payment->id) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <p>You are about to reject this payment of <strong>LKR {{ number_format($payment->amount, 2) }}</strong> from <strong>{{ $payment->user->name }}</strong>.</p>
                                                                <div class="form-group">
                                                                    <label for="reject_reason">Reason (Optional but recommended)</label>
                                                                    <textarea name="reject_reason" id="reject_reason" class="form-control" rows="3" placeholder="Please specify the reason for rejection..."></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-danger">Confirm Rejection</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">Action taken</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">No payment records found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="card-footer text-right">
                        <nav class="d-inline-block" aria-label="Pagination">
                            {{ $payments->links('pagination::bootstrap-4') }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection