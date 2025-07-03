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
            <h4>Invoices With Stock</h4>
            <button type="button" class="btn btn-primary" id="addInvoiceWithStockBtn">
                <i class="fas fa-plus"></i> Create New Invoice
            </button>
        </div>

        <div class="card-body">
            <!-- Search Form -->
            <form action="{{ route('user.invoices_with_stock.index') }}" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search by invoice number, customer name or phone..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i> Search
                        </button>
                        @if(request('search'))
                            <a href="{{ route('user.invoices_with_stock.index') }}" class="btn btn-outline-danger">Clear</a>
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
                                <a href="{{ route('user.invoices_with_stock.show', $invoice->id)}}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('user.invoices_with_stock.print', $invoice->id) }}" 
                                class="btn btn-sm btn-success" 
                                target="_blank">
                                    <i class="fas fa-print"></i>
                                </a>
                                <a href="{{ route('user.invoices_with_stock.download', $invoice->id) }}" 
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

<!-- Create Invoice With Stock Modal -->
<div class="modal fade" id="createInvoiceWithStockModal" tabindex="-1" role="dialog" aria-labelledby="createInvoiceWithStockModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createInvoiceWithStockModalLabel">Create New Invoice With Stock</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="createInvoiceWithStockForm" method="POST" action="{{ route('user.invoices_with_stock.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Customer Information</h5>
                            <div class="form-group">
                                <label for="customer_name">Customer Name</label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                            </div>
                            <div class="form-group">
                                <label for="customer_phone">Phone Number</label>
                                <input type="text" class="form-control" id="customer_phone" name="customer_phone" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5>Invoice Details</h5>
                            <div class="form-group">
                                <label for="sales_rep">Sales Representative</label>
                                <select class="form-control" id="sales_rep" name="sales_rep" required>
                                    <option value="Chammika">Chammika</option>
                                    <option value="Vidwashan">Vidwashan</option>
                                    <option value="Gihan">Gihan</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="issue_date">Invoice Date</label>
                                <input type="date" class="form-control" id="issue_date" name="issue_date" required value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    <h5>Invoice Items</h5>
                    
                    <div id="invoiceItems">
                        <div class="row item-row">
    <div class="col-md-6">
        <div class="form-group">
            <div class="input-group">
                <input type="text" class="form-control item-search" placeholder="Search for items..." autocomplete="off">
                <div class="input-group-append">
                    <button type="button" class="btn btn-outline-secondary clear-search">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="search-results" style="display: none;"></div>
            <input type="hidden" name="stock_id[]" class="selected-stock-id" required>
            <div class="selected-item-display" style="display: none;">
                <div class="alert alert-info">
                    <strong>Selected:</strong> <span class="selected-item-name"></span>
                    <button type="button" class="btn btn-sm btn-outline-danger float-right remove-selection">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
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
                                    <label>Qty</label>
                                    <input type="number" class="form-control qty" name="qty[]" min="1" value="1" required>
                                    <small class="available text-muted">Available: 0</small>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Price</label>
                                    <input type="number" class="form-control unit-price" name="unit_price[]" min="0" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-success btn-sm add-item">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-3 offset-md-9">
                            <div class="form-group">
                                <label>Total Amount</label>
                                <input type="text" class="form-control" id="total_amount" name="total_amount" readonly value="0.00">
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

@push('styles')
<style>
    .item-row {
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
        margin-bottom: 10px;
    }
    .item-row:last-child {
        border-bottom: none;
    }
    .search-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 1000;
        background: white;
        border: 1px solid #ddd;
        border-top: none;
        max-height: 300px;
        overflow-y: auto;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .search-result-item {
        padding: 12px;
        cursor: pointer;
        border-bottom: 1px solid #eee;
        transition: background-color 0.2s;
    }
    .search-result-item:hover {
        background-color: #f8f9fa;
    }
    .search-result-item:last-child {
        border-bottom: none;
    }
    .search-result-item .item-name {
        font-weight: bold;
        color: #333;
    }
    .search-result-item .item-description {
        color: #666;
        font-size: 0.9em;
        margin-top: 2px;
    }
    .search-result-item .item-details {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 5px;
    }
    .search-result-item .item-price {
        color: #28a745;
        font-weight: bold;
    }
    .search-result-item .item-stock {
        color: #007bff;
        font-size: 0.85em;
    }
    .input-group {
        position: relative;
    }
    .no-results {
        padding: 12px;
        text-align: center;
        color: #666;
        font-style: italic;
    }
    .search-loading {
        padding: 12px;
        text-align: center;
        color: #666;
    }
    .selected-item-display {
        margin-top: 10px;
    }
.selected-item-display {
    margin-top: 10px;
    display: block !important; /* Force display */
}

.selected-item-display .alert {
    padding: 8px 12px;
    margin-bottom: 0;
}

.item-search {
    display: block !important; /* Ensure search input is visible when needed */
}

.search-results {
    z-index: 1000; /* Ensure results appear above other elements */
}

</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function () {
    let itemRowCounter = 0;
    let searchTimeout;

    // Handle Add Invoice button click
    $('#addInvoiceWithStockBtn').on('click', function() {
        $('#createInvoiceWithStockModal').modal('show');
        $('#issue_date').val(new Date().toISOString().substr(0, 10));
    });

    // Handle item search
    $(document).on('input', '.item-search', function() {
        const searchInput = $(this);
        const searchTerm = searchInput.val().trim();
        const resultsContainer = searchInput.closest('.form-group').find('.search-results');
        
        if (searchTimeout) {
            clearTimeout(searchTimeout);
        }
        
        if (searchTerm.length < 2) {
            resultsContainer.hide().empty();
            return;
        }
        
        resultsContainer.show().html('<div class="search-loading"><i class="fas fa-spinner fa-spin"></i> Searching...</div>');
        
        searchTimeout = setTimeout(function() {
            searchItems(searchTerm, resultsContainer);
        }, 300);
    });

    // Search items function
    function searchItems(term, resultsContainer) {
        $.ajax({
            url: "{{ route('user.invoices_with_stock.products.search') }}",
            method: 'GET',
            data: { term: term },
            dataType: 'json',
            success: function(response) {
                displaySearchResults(response, resultsContainer);
            },
            error: function() {
                resultsContainer.html('<div class="no-results">Error searching items</div>');
            }
        });
    }

    // Display search results
    function displaySearchResults(items, resultsContainer) {
        if (!items || items.length === 0) {
            resultsContainer.html('<div class="no-results">No items found</div>');
            return;
        }

        let html = '';
        items.forEach(function(item) {
            const itemName = item.name || item.text || 'Unnamed Item';
            const itemPrice = parseFloat(item.retail_price || 0);
            const itemQuantity = parseInt(item.quantity || 0);
            
            html += `
                <div class="search-result-item" 
                     data-id="${item.id}" 
                     data-name="${itemName}" 
                     data-price="${itemPrice}" 
                     data-quantity="${itemQuantity}">
                    <div class="item-name">${itemName}</div>
                    <div class="item-details">
                        <span class="item-price">$${itemPrice.toFixed(2)}</span>
                        <span class="item-stock">${itemQuantity} available</span>
                    </div>
                </div>
            `;
        });
        
        resultsContainer.html(html);
    }

    // Handle item selection
    $(document).on('click', '.search-result-item', function() {
        const item = $(this);
        const row = item.closest('.item-row');
        const itemId = item.data('id');
        const itemName = item.data('name');
        const itemPrice = item.data('price');
        const itemQuantity = item.data('quantity');
        
        // Set selected item
        row.find('.selected-stock-id').val(itemId);
        row.find('.unit-price').val(itemPrice);
        row.find('.available').text('Available: ' + itemQuantity);
        row.find('.qty').attr('max', itemQuantity);
        row.find('.selected-item-name').text(itemName);
        
        // Validate current quantity
        const currentQty = parseInt(row.find('.qty').val());
        if (currentQty > itemQuantity) {
            row.find('.qty').val(itemQuantity);
        }
        
        // Hide search results and show selected item
        row.find('.search-results').hide();
        row.find('.item-search').hide();
        row.find('.selected-item-display').show();
        
        calculateTotal();
    });

    // Handle clear search
    $(document).on('click', '.clear-search', function() {
        const row = $(this).closest('.item-row');
        clearItemSelection(row);
    });

    // Handle remove selection
    $(document).on('click', '.remove-selection', function() {
        const row = $(this).closest('.item-row');
        clearItemSelection(row);
    });

    // Clear item selection function
    function clearItemSelection(row) {
        row.find('.item-search').val('').show();
        row.find('.selected-stock-id').val('');
        row.find('.unit-price').val('');
        row.find('.available').text('Available: 0');
        row.find('.qty').removeAttr('max').val('1');
        row.find('.search-results').hide().empty();
        row.find('.selected-item-display').hide();
        row.find('.selected-item-name').text('');
        calculateTotal();
    }

    // Hide search results when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.form-group').length) {
            $('.search-results').hide();
        }
    });

    // Handle Add Item button click
    $(document).on('click', '.add-item', function() {
        const itemCount = $('.item-row').length;
        if(itemCount >= 43) {
            Swal.fire({
                icon: 'warning',
                title: 'Maximum Items Reached',
                text: 'Maximum 43 items allowed per invoice',
                confirmButtonColor: '#0d6efd'
            });
            return;
        }
        
        itemRowCounter++;
        const newItem = `
            <div class="row item-row mt-2" data-row="${itemRowCounter}">
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control item-search" placeholder="Search for items..." autocomplete="off">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary clear-search">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="search-results" style="display: none;"></div>
                        <input type="hidden" name="stock_id[]" class="selected-stock-id" required>
                        <div class="selected-item-display" style="display: none;">
                            <div class="alert alert-info">
                                <strong>Selected:</strong> <span class="selected-item-name"></span>
                                <button type="button" class="btn btn-sm btn-outline-danger float-right remove-selection">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <input type="text" class="form-control" name="warranty[]" placeholder="Warranty">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <input type="number" class="form-control qty" name="qty[]" min="1" value="1" required>
                        <small class="available text-muted">Available: 0</small>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <input type="number" class="form-control unit-price" name="unit_price[]" min="0" step="0.01" required>
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm remove-item">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        
        $('#invoiceItems').append(newItem);
    });

    // Handle Remove Item button click
    $(document).on('click', '.remove-item', function() {
        $(this).closest('.item-row').remove();
        calculateTotal();
    });

    // Calculate total amount when quantity or price changes
    $(document).on('input', '.qty, .unit-price', function() {
        const row = $(this).closest('.item-row');
        
        if ($(this).hasClass('qty')) {
            const maxQty = parseInt($(this).attr('max'));
            const currentQty = parseInt($(this).val());
            
            if (maxQty && currentQty > maxQty) {
                $(this).val(maxQty);
                Swal.fire({
                    icon: 'warning',
                    title: 'Quantity Exceeded',
                    text: `Maximum available quantity is ${maxQty}`,
                    confirmButtonColor: '#0d6efd'
                });
            }
        }
        
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
        
        const actionUrl = "{{ route('user.invoices_with_stock.destroy', ':id') }}".replace(':id', invoiceId);
        $('#deleteInvoiceForm').attr('action', actionUrl);
        
        $('#deleteInvoiceModal').modal('show');
    });

    // Clear modal forms when modals are hidden
    $('#createInvoiceWithStockModal').on('hidden.bs.modal', function () {
        $('#createInvoiceWithStockForm')[0].reset();
        $('#invoiceItems .item-row:not(:first)').remove();
        const firstRow = $('#invoiceItems .item-row:first');
        clearItemSelection(firstRow);
        $('#total_amount').val('0.00');
        $('#createInvoiceWithStockForm button[type="submit"]').prop('disabled', false).html('Create Invoice');
    });

    $('#deleteInvoiceModal').on('hidden.bs.modal', function () {
        $('#deleteInvoiceForm button[type="submit"]').prop('disabled', false).html('Delete');
    });

    // Handle form submissions with loading states
    $('#createInvoiceWithStockForm').on('submit', function(e) {
        let hasValidItems = false;
        let valid = true;
        
        $('.item-row').each(function() {
            const stockId = $(this).find('.selected-stock-id').val();
            const qty = parseInt($(this).find('.qty').val());
            const maxQty = parseInt($(this).find('.qty').attr('max')) || 0;
            
            if (stockId) {
                hasValidItems = true;
                
                if (qty > maxQty && maxQty > 0) {
                    valid = false;
                    const itemName = $(this).find('.selected-item-name').text() || 'selected item';
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Quantity',
                        text: `Quantity for ${itemName} exceeds available stock (Max: ${maxQty})`,
                        confirmButtonColor: '#0d6efd'
                    });
                    return false;
                }
            }
        });
        
        if (!hasValidItems) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'No Items Selected',
                text: 'Please select at least one item for the invoice',
                confirmButtonColor: '#0d6efd'
            });
            return false;
        }
        
        if (!valid) {
            e.preventDefault();
            return false;
        }
        
        $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Creating...');
    });

    $('#deleteInvoiceForm').on('submit', function() {
        $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Deleting...');
    });
});
</script>
@endpush
@endsection