@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Invoice Summary</h3>
                </div>
                <div class="card-body">
                    <!-- Search and Filter Form -->
                    <form action="{{ route('user.total_amount.index') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Search by invoice number..." 
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="start_date" class="form-control" 
                                       value="{{ request('start_date') }}" placeholder="Start Date">
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="end_date" class="form-control" 
                                       value="{{ request('end_date') }}" placeholder="End Date">
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search"></i> Search
                                    </button>
                                    @if(request('search') || request('start_date') || request('end_date'))
                                        <a href="{{ route('user.total_amount.index') }}" class="btn btn-outline-danger ml-2">Clear</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Standard Invoices Table -->
                    <div class="mb-5">
                        <h4 class="mb-3">Standard Invoices</h4>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Date</th>
                                        <th>Invoice Count</th>
                                        <th>Invoice Numbers</th>
                                        <th>Total Amount</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $standardGrouped = $standardInvoices->groupBy('date');
                                        $standardTotalAmount = 0;
                                    @endphp
                                    @forelse($standardGrouped as $date => $invoices)
                                        @php
                                            $dayTotal = $invoices->sum('amount');
                                            $standardTotalAmount += $dayTotal;
                                        @endphp
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($date)->format('Y-m-d') }}</td>
                                            <td>
                                                <span class="badge badge-primary">{{ $invoices->count() }}</span>
                                            </td>
                                            <td>
                                                <div class="invoice-numbers">
                                                    @foreach($invoices as $invoice)
                                                        <span class="badge badge-secondary mr-1">{{ $invoice['invoice_number'] }}</span>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td>
                                                <strong>{{ number_format($dayTotal, 2) }}</strong>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-info view-details" 
                                                        data-date="{{ $date }}" 
                                                        data-type="standard">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No standard invoices found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot class="thead-dark">
                                    <tr>
                                        <th colspan="3">Total Standard Invoices</th>
                                        <th>{{ number_format($standardTotalAmount, 2) }}</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Stock Invoices Table -->
                    <div class="mb-4">
                        <h4 class="mb-3">Stock Invoices</h4>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Date</th>
                                        <th>Invoice Count</th>
                                        <th>Invoice Numbers</th>
                                        <th>Total Amount</th>
                                        <th>Total Cost</th>
                                        <th>Profit</th>
                                        <th>Margin %</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $stockGrouped = $stockInvoices->groupBy('date');
                                        $stockTotalAmount = 0;
                                        $stockTotalCost = 0;
                                        $stockTotalProfit = 0;
                                    @endphp
                                    @forelse($stockGrouped as $date => $invoices)
                                        @php
                                            $dayTotal = $invoices->sum('amount');
                                            $dayCost = $invoices->sum('cost');
                                            $dayProfit = $invoices->sum('profit');
                                            $dayMargin = $dayTotal > 0 ? ($dayProfit / $dayTotal) * 100 : 0;
                                            
                                            $stockTotalAmount += $dayTotal;
                                            $stockTotalCost += $dayCost;
                                            $stockTotalProfit += $dayProfit;
                                        @endphp
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($date)->format('Y-m-d') }}</td>
                                            <td>
                                                <span class="badge badge-success">{{ $invoices->count() }}</span>
                                            </td>
                                            <td>
                                                <div class="invoice-numbers">
                                                    @foreach($invoices as $invoice)
                                                        <span class="badge badge-secondary mr-1">{{ $invoice['invoice_number'] }}</span>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td>
                                                <strong>{{ number_format($dayTotal, 2) }}</strong>
                                            </td>
                                            <td>
                                                <span class="text-danger">{{ number_format($dayCost, 2) }}</span>
                                            </td>
                                            <td>
                                                <span class="text-success">{{ number_format($dayProfit, 2) }}</span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $dayMargin >= 0 ? 'badge-success' : 'badge-danger' }}">
                                                    {{ number_format($dayMargin, 1) }}%
                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-info view-details" 
                                                        data-date="{{ $date }}" 
                                                        data-type="stock">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">No stock invoices found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot class="thead-dark">
                                    <tr>
                                        <th colspan="3">Total Stock Invoices</th>
                                        <th>{{ number_format($stockTotalAmount, 2) }}</th>
                                        <th>{{ number_format($stockTotalCost, 2) }}</th>
                                        <th>{{ number_format($stockTotalProfit, 2) }}</th>
                                        <th>
                                            <span class="badge {{ $stockTotalAmount > 0 ? (($stockTotalProfit / $stockTotalAmount) * 100 >= 0 ? 'badge-success' : 'badge-danger') : 'badge-secondary' }}">
                                                {{ $stockTotalAmount > 0 ? number_format(($stockTotalProfit / $stockTotalAmount) * 100, 1) : 0 }}%
                                            </span>
                                        </th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Summary Cards -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5>Standard Total</h5>
                                    <h3>{{ number_format($standardTotalAmount, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5>Stock Total</h5>
                                    <h3>{{ number_format($stockTotalAmount, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5>Total Profit</h5>
                                    <h3>{{ number_format($stockTotalProfit, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h5>Overall Total</h5>
                                    <h3>{{ number_format($standardTotalAmount + $stockTotalAmount, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Invoice Details Modal -->
<div class="modal fade" id="invoiceDetailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Invoice Details</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="invoiceDetailsBody">
                <!-- Details will be loaded here -->
            </div>
        </div>
    </div>
</div>

<style>
.invoice-numbers {
    max-width: 250px;
    max-height: 60px;
    overflow-y: auto;
}

.invoice-numbers .badge {
    margin-bottom: 2px;
}

.table th, .table td {
    vertical-align: middle;
}

.card {
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

.summary-cards .card {
    transition: transform 0.2s;
}

.summary-cards .card:hover {
    transform: translateY(-5px);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // View details functionality
    document.querySelectorAll('.view-details').forEach(function(button) {
        button.addEventListener('click', function() {
            const date = this.getAttribute('data-date');
            const type = this.getAttribute('data-type');
            
            // You can implement AJAX call here to fetch detailed invoice data
            // For now, showing a simple modal
            const modal = document.getElementById('invoiceDetailsModal');
            const modalBody = document.getElementById('invoiceDetailsBody');
            
            modalBody.innerHTML = `
                <h6>Invoices for ${date} (${type.charAt(0).toUpperCase() + type.slice(1)})</h6>
                <p>Loading detailed information...</p>
            `;
            
            $(modal).modal('show');
        });
    });
});
</script>
@endsection