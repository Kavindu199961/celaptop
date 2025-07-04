@extends('layouts.app')

@section('content')
<div class="col-12 col-md-12 col-lg-12">
    <!-- Success Message -->
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
            <h4>Invoices</h4>
            <button type="button" class="btn btn-primary" id="addInvoiceBtn">
                <i class="fas fa-plus"></i> Create New Invoice
            </button>
        </div>

        <div class="card-body">
            <!-- Search Form -->
            <form action="{{ route('user.invoices.index') }}" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search by invoice number, customer name or phone..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i> Search
                        </button>
                        @if(request('search'))
                            <a href="{{ route('user.invoices.index') }}" class="btn btn-outline-danger">Clear</a>
                        @endif
                    </div>
                </div>
            </form>
                   
            <div class="table-responsive">
                <table class="table table-striped" id="invoices-table">
                    <thead class="thead-dark">
                        <tr>
                            <th>Invoice #</th>
                            <th>Customer Name</th>
                            <th>Phone</th>
                            <th>Sales Rep</th>
                            <th>Total Amount</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->invoice_number }}</td>
                            <td>{{ $invoice->customer_name }}</td>
                            <td>{{ $invoice->customer_phone }}</td>
                            <td>{{ $invoice->sales_rep }}</td>
                            <td>{{ number_format($invoice->total_amount, 2) }}</td>
                            <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('user.invoices.show', $invoice->id)}}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('user.invoices.print', $invoice->id) }}" 
                                class="btn btn-sm btn-success" 
                                target="_blank">
                                    <i class="fas fa-print"></i>
                                </a>
                                <a href="{{ route('user.invoices.download', $invoice->id) }}" 
                                class="btn btn-sm btn-primary" 
                                target="_blank">
                                    <i class="fas fa-download"></i>
                                </a>
                                <button class="btn btn-sm btn-danger delete-invoice" 
                                        data-id="{{ $invoice->id }}"
                                        data-name="{{ $invoice->customer_name }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No invoices found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer text-right">
            <nav class="d-inline-block" aria-label="Pagination">
                {{ $invoices->links('pagination::bootstrap-4') }}
            </nav>
        </div>
    </div>
</div>

<!-- Create Invoice Modal -->
<div class="modal fade" id="createInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="createInvoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createInvoiceModalLabel">Create New Invoice</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="createInvoiceForm" method="POST" action="{{ route('user.invoices.store') }}">
                @csrf
                <div class="modal-body">
                    <!-- <div class="alert alert-warning">
                        <strong>Note:</strong> Maximum 10 items allowed in this invoice.
                    </div> -->
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Customer Information</h5>
                            <div class="form-group">
                                <label for="customer_name">Customer Name</label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                            </div>
                            <div class="form-group">
                                <label for="customer_phone">Phone Number (Optional)</label>
                                <input type="text" class="form-control" id="customer_phone" name="customer_phone">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5>Invoice Details</h5>
                          
                            <div class="form-group">
                                <label for="sales_rep">Sales Representative</label>
                                <select class="form-control" id="sales_rep" name="sales_rep" required>
                                    @foreach($cashiers as $cashier)
                                        <option value="{{ $cashier->name }}">{{ $cashier->name }}</option>
                                    @endforeach
                                    <option value="{{ $shopName }}">{{ $shopName }}</option>
                                </select>
                            </div>


                            <div class="form-group">
                                <label for="issue_date">Invoice Date</label>
                                <input type="date" class="form-control" id="issue_date" name="issue_date" required>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    <h5>Invoice Items</h5>
                    
                    <div id="invoiceItems">
                        <div class="row item-row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Description</label>
                                    <input type="text" class="form-control" name="description[]" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Warranty (Optional)</label>
                                    <input type="text" class="form-control" name="warranty[]">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Quantity</label>
                                    <input type="number" class="form-control qty" name="qty[]" min="1" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Unit Price</label>
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
                    <button type="submit" class="btn btn-primary">Create Invoice</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="deleteInvoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteInvoiceModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete invoice for <strong id="delete_invoice_name"></strong>? This action cannot be undone.</p>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteInvoiceForm" method="POST" style="display: inline;">
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
        $('#addInvoiceBtn').on('click', function() {
            $('#createInvoiceModal').modal('show');
            // Set current date as default
            $('#issue_date').val(new Date().toISOString().substr(0, 10));
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
            
            $('#invoiceItems').append(newItem);
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
        $(document).on('click', '.delete-invoice', function() {
            const invoiceId = $(this).data('id');
            const invoiceName = $(this).data('name');
            
            $('#delete_invoice_name').text(invoiceName);
            
            const actionUrl = "{{ route('user.invoices.destroy', ':id') }}".replace(':id', invoiceId);
            $('#deleteInvoiceForm').attr('action', actionUrl);
            
            $('#deleteInvoiceModal').modal('show');
        });

        // Clear modal forms when modals are hidden
        $('#createInvoiceModal').on('hidden.bs.modal', function () {
            $('#createInvoiceForm')[0].reset();
            $('#invoiceItems .item-row:not(:first)').remove();
            $('#total_amount').val('0.00');
            $('#createInvoiceForm button[type="submit"]').prop('disabled', false).html('Create Invoice');
        });

        $('#deleteInvoiceModal').on('hidden.bs.modal', function () {
            $('#deleteInvoiceForm button[type="submit"]').prop('disabled', false).html('Delete');
        });

        // Handle form submissions with loading states
        $('#createInvoiceForm').on('submit', function() {
            $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Creating...');
        });

        $('#deleteInvoiceForm').on('submit', function() {
            $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Deleting...');
        });
    });
</script>
@endpush
@endsection