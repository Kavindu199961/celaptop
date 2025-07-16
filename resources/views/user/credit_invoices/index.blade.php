@extends('layouts.app')

@section('content')
<div class="col-12 col-md-12 col-lg-12">
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: '',
            text: '{{ session('success') }}',
            showConfirmButton: true,
            confirmButtonColor: '#0d6efd',
            confirmButtonText: 'OK',
            background: '#f8f9fa',
            iconColor: '#28a745'
        });
    </script>
    @endif 

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Credit Invoices</h4>
            <button type="button" class="btn btn-primary" id="addCreditInvoiceBtn">
                <i class="fas fa-plus"></i> Create New Credit Invoice
            </button>
        </div>

        <div class="card-body">
            <!-- Monthly Credit Summary Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Monthly Credit Summary</h5>
                </div>
                <div class="card-body">
                    <!-- Monthly Summary Search Form -->
                    <form action="{{ route('user.credit_invoices.index') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Month</label>
                                    <select class="form-control" name="month">
                                        <option value="">All Months</option>
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                                                {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Year</label>
                                    <select class="form-control" name="year">
                                        <option value="">All Years</option>
                                        @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                                            <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Shop</label>
                                    <select class="form-control" name="shop">
                                        <option value="">All Shops</option>
                                        @foreach($creditShops as $shop)
                                            <option value="{{ $shop->id }}" {{ request('shop') == $shop->id ? 'selected' : '' }}>
                                                {{ $shop->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                                <a href="{{ route('user.credit_invoices.index') }}" class="btn btn-outline-danger">
                                    <i class="fas fa-times"></i> Clear
                                </a>
                            </div>
                        </div>
                    </form>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Shop Name</th>
                                    <th>Month</th>
                                    <th>Total Credit</th>
                                    <th>Total Paid</th>
                                    <th>Outstanding</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($monthlyTotals as $total)
                                <tr>
                                    <td>{{ $total->creditShop->name }}</td>
                                    <td>{{ date('F Y', mktime(0, 0, 0, $total->month, 1, $total->year)) }}</td>
                                    <td>{{ number_format($total->total, 2) }}</td>
                                    <td>{{ number_format($total->paid, 2) }}</td>
                                    <td>{{ number_format($total->total - $total->paid, 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No credit data available</td>
                                </tr>
                                @endforelse
                            </tbody>
                            @if($monthlyTotals->isNotEmpty())
                            <tfoot>
                                <tr>
                                    <th colspan="2">Total</th>
                                    <th>{{ number_format($monthlyTotals->sum('total'), 2) }}</th>
                                    <th>{{ number_format($monthlyTotals->sum('paid'), 2) }}</th>
                                    <th>{{ number_format($monthlyTotals->sum('total') - $monthlyTotals->sum('paid'), 2) }}</th>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            <!-- Invoices Section -->
            <div class="card">
                <div class="card-header">
                    <h5>Credit Invoices</h5>
                </div>
                <div class="card-body">
                    <!-- Invoice Search Form -->
                    <form action="{{ route('user.credit_invoices.index') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Search</label>
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="Invoice #, customer, phone..." 
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <!-- <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control" name="status">
                                        <option value="">All Statuses</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Partial</option>
                                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                    </select>
                                </div> -->
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Shop</label>
                                    <select class="form-control" name="invoice_shop">
                                        <option value="">All Shops</option>
                                        @foreach($creditShops as $shop)
                                            <option value="{{ $shop->id }}" {{ request('invoice_shop') == $shop->id ? 'selected' : '' }}>
                                                {{ $shop->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary mr-2">
                                    <i class="fas fa-search"></i> Search
                                </button>
                                <a href="{{ route('user.credit_invoices.index') }}" class="btn btn-outline-danger">
                                    <i class="fas fa-times"></i>Clear
                                </a>
                            </div>
                        </div>
                    </form>
                    
                    <div class="table-responsive">
                        <table class="table table-striped" id="credit-invoices-table">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Shop Name</th>
                                    <!-- <th>Customer Name</th> -->
                                    <th>Phone</th>
                                    <th>Total Amount</th>
                                    <th>Remaining</th>
                                    <!-- <th>Status</th> -->
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($invoices as $invoice)
                                <tr>
                                    <td>{{ $invoice->invoice_number }}</td>
                                    <td>{{ $invoice->creditShop->name }}</td>
                                    <!-- <td>{{ $invoice->customer_name }}</td> -->
                                    <td>{{ $invoice->customer_phone }}</td>
                                    <td>{{ number_format($invoice->total_amount, 2) }}</td>
                                    <td>{{ number_format($invoice->remaining_amount, 2) }}</td>
                                    <!-- <td>
                                        <span class="badge 
                                            @if($invoice->status == 'paid') badge-success
                                            @elseif($invoice->status == 'partial') badge-warning
                                            @else badge-danger @endif">
                                            {{ ucfirst($invoice->status) }}
                                        </span>
                                    </td> -->
                                    <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <a href="{{ route('user.credit_invoices.show', $invoice->id)}}" 
                                           class="btn btn-sm btn-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('user.credit_invoices.print', $invoice->id) }}" 
                                           class="btn btn-sm btn-success" title="Print" target="_blank">
                                            <i class="fas fa-print"></i>
                                        </a>
                                        <a href="{{ route('user.credit_invoices.download', $invoice->id) }}" 
                                           class="btn btn-sm btn-primary" title="Download" target="_blank">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <button class="btn btn-sm btn-danger delete-credit-invoice" 
                                                data-id="{{ $invoice->id }}"
                                                data-name="{{ $invoice->customer_name }}"
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">No credit invoices found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        {{ $invoices->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Credit Invoice Modal -->
<div class="modal fade" id="createCreditInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="createCreditInvoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createCreditInvoiceModalLabel">Create New Credit Invoice</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="createCreditInvoiceForm" method="POST" action="{{ route('user.credit_invoices.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Shop & Customer Information</h5>
                            <div class="form-group">
                                <label for="credit_shop_id">Credit Shop *</label>
                                <select class="form-control" id="credit_shop_id" name="credit_shop_id" required>
                                    <option value="">Select Credit Shop</option>
                                    @foreach($creditShops as $shop)
                                        <option value="{{ $shop->id }}" 
                                            data-contact="{{ $shop->contact }}"
                                            data-address="{{ $shop->address }}">
                                            {{ $shop->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- <div class="form-group">
                                <label for="customer_name">Customer Name *</label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name">
                            </div> -->
                            <div class="form-group">
                                <label for="customer_phone">Phone Number</label>
                                <input type="text" class="form-control" id="customer_phone" name="customer_phone">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5>Invoice Details</h5>
                            <div class="form-group">
                                <label for="sales_rep">Sales Representative *</label>
                                <select class="form-control" id="sales_rep" name="sales_rep" required>
                                    @foreach($cashiers as $cashier)
                                        <option value="{{ $cashier->name }}">{{ $cashier->name }}</option>
                                    @endforeach
                                    <option value="{{ $shopName }}">{{ $shopName }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="issue_date">Invoice Date *</label>
                                <input type="date" class="form-control" id="issue_date" name="issue_date" required>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    <h5>Invoice Items</h5>
                    
                    <div id="creditInvoiceItems">
                        <div class="row item-row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Description *</label>
                                    <input type="text" class="form-control" name="description[]" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Warranty</label>
                                    <input type="text" class="form-control" name="warranty[]">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Quantity *</label>
                                    <input type="number" class="form-control qty" name="qty[]" min="1" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Unit Price *</label>
                                    <input type="number" class="form-control unit-price" name="unit_price[]" min="0" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="button" class="btn btn-success btn-sm add-item">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-4 offset-md-8">
                            <div class="form-group">
                                <label>Total Amount</label>
                                <input type="text" class="form-control" id="total_amount" name="total_amount" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create Credit Invoice</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteCreditInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="deleteCreditInvoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCreditInvoiceModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete credit invoice for <strong id="delete_credit_invoice_name"></strong>? This action cannot be undone.</p>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteCreditInvoiceForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function () {
        // Handle Add Invoice button click
        $('#addCreditInvoiceBtn').on('click', function() {
            $('#createCreditInvoiceModal').modal('show');
            $('#issue_date').val(new Date().toISOString().substr(0, 10));
        });

        // Auto-fill contact when credit shop is selected
        $('#credit_shop_id').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const contact = selectedOption.data('contact');
            const address = selectedOption.data('address');
            
            if(contact) {
                $('#customer_phone').val(contact);
            }
        });

        // Handle Add Item button click
        $(document).on('click', '.add-item', function() {
            const itemCount = $('.item-row').length;
            if(itemCount >= 43) {
                alert('Maximum 43 items allowed per invoice');
                return;
            }
            
            const newItem = `
                <div class="row item-row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <input type="text" class="form-control" name="description[]" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <input type="text" class="form-control" name="warranty[]">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <input type="number" class="form-control qty" name="qty[]" min="1" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <input type="number" class="form-control unit-price" name="unit_price[]" min="0" step="0.01" required>
                        </div>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-item">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            
            $('#creditInvoiceItems').append(newItem);
            calculateTotal();
        });

        // Handle Remove Item button click
        $(document).on('click', '.remove-item', function() {
            $(this).closest('.item-row').remove();
            calculateTotal();
        });

        // Calculate total amount when quantity or price changes
        $(document).on('input', '.qty, .unit-price', function() {
            calculateTotal();
        });

        // Calculate total amount function
        function calculateTotal() {
            let total = 0;
            $('.item-row').each(function() {
                const qty = parseFloat($(this).find('.qty').val()) || 0;
                const unitPrice = parseFloat($(this).find('.unit-price').val()) || 0;
                total += qty * unitPrice;
            });
            $('#total_amount').val(total.toFixed(2));
        }

        // Handle Delete button click
        $(document).on('click', '.delete-credit-invoice', function() {
            const invoiceId = $(this).data('id');
            const invoiceName = $(this).data('name');
            
            $('#delete_credit_invoice_name').text(invoiceName);
            
            const actionUrl = "{{ route('user.credit_invoices.destroy', ':id') }}".replace(':id', invoiceId);
            $('#deleteCreditInvoiceForm').attr('action', actionUrl);
            
            $('#deleteCreditInvoiceModal').modal('show');
        });

        // Clear modal forms when modals are hidden
        $('#createCreditInvoiceModal').on('hidden.bs.modal', function () {
            $('#createCreditInvoiceForm')[0].reset();
            $('#creditInvoiceItems .item-row:not(:first)').remove();
            $('#total_amount').val('0.00');
            $('#createCreditInvoiceForm button[type="submit"]').prop('disabled', false).html('Create Credit Invoice');
        });

        $('#deleteCreditInvoiceModal').on('hidden.bs.modal', function () {
            $('#deleteCreditInvoiceForm button[type="submit"]').prop('disabled', false).html('Delete');
        });

        // Handle form submissions with loading states
        $('#createCreditInvoiceForm').on('submit', function() {
            $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Creating...');
        });

        $('#deleteCreditInvoiceForm').on('submit', function() {
            $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Deleting...');
        });
    });
</script>

@endpush
@endsection