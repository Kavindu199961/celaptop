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
                                <input type="date" class="form-control" id="issue_date" name="issue_date" required value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    <h5>Add Items</h5>
                    
                    <!-- Search Box for Items -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Search Items</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="global-item-search" placeholder="Type to search items...">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" id="clear-global-search">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="search-results-global mt-2" style="display: none;"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Selected Items List -->
                    <h5>Selected Items</h5>
                    <div id="selected-items-container">
                        <!-- Items will be added here dynamically -->
                        <div class="alert alert-info" id="no-items-message">
                            No items selected yet. Search and select items above.
                        </div>
                    </div>
                    
                    <!-- Invoice Total -->
                    <div class="row mt-4">
                        <div class="col-md-3 offset-md-9">
                            <div class="form-group">
                                <label class="font-weight-bold">Total Amount</label>
                                <input type="text" class="form-control font-weight-bold" id="total_amount" name="total_amount" readonly value="0.00">
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
    .selected-item-card {
    border-left: 4px solid #17a2b8;
    margin-bottom: 15px;
    transition: all 0.3s;
    padding: 10px;
}
.selected-item-card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}
.selected-item-header {
    background-color: #f8f9fa;
    padding: 10px 15px;
    border-bottom: 1px solid #eee;
}
.selected-item-body {
    padding: 15px;
}
.search-results-global {
    border: 1px solid #ddd;
    border-radius: 4px;
    max-height: 300px;
    overflow-y: auto;
}
.search-result-item {
    padding: 10px;
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
.item-name {
    font-weight: bold;
    color: #333;
}
.item-details {
    display: flex;
    justify-content: space-between;
    margin-top: 5px;
}
.item-price {
    color: #28a745;
    font-weight: bold;
}
.item-stock {
    color: #007bff;
    font-size: 0.85em;
}
.item-total {
    font-weight: bold;
    color: #28a745;
}
.form-group label {
    font-weight: 500;
}
.quantity-available {
    font-size: 0.8rem;
    color: #6c757d;
    display: block;
    margin-top: 5px;
}
.remove-item-btn {
    color: #dc3545;
    background: none;
    border: none;
    padding: 0;
}
.remove-item-btn:hover {
    color: #c82333;
}
.table {
    margin-bottom: 0;
}
.table thead th {
    background-color: #f8f9fa;
    font-weight: 600;
}
.selected-item-row td {
    vertical-align: middle;
}
.selected-item-row:hover {
    background-color: #f8f9fa;
}
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function () {
        let selectedItems = [];
        let searchTimeout;

        // Handle Add Invoice button click
        $('#addInvoiceWithStockBtn').on('click', function() {
            $('#createInvoiceWithStockModal').modal('show');
            $('#issue_date').val(new Date().toISOString().substr(0, 10));
            selectedItems = []; // Reset selected items
            updateSelectedItemsDisplay();
        });

        // Handle global item search
        $('#global-item-search').on('input', function() {
            const searchTerm = $(this).val().trim();
            const resultsContainer = $('.search-results-global');
            
            if (searchTimeout) {
                clearTimeout(searchTimeout);
            }
            
            if (searchTerm.length < 2) {
                resultsContainer.hide().empty();
                return;
            }
            
            resultsContainer.show().html('<div class="search-loading p-3"><i class="fas fa-spinner fa-spin"></i> Searching items...</div>');
            
            searchTimeout = setTimeout(function() {
                searchItems(searchTerm, resultsContainer);
            }, 300);
        });

        // Clear global search
        $('#clear-global-search').on('click', function() {
            $('#global-item-search').val('');
            $('.search-results-global').hide().empty();
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
                    resultsContainer.html('<div class="no-results p-3">Error searching items</div>');
                }
            });
        }

        // Display search results
        function displaySearchResults(items, resultsContainer) {
            if (!items || items.length === 0) {
                resultsContainer.html('<div class="no-results p-3">No items found</div>');
                return;
            }

            let html = '';
            items.forEach(function(item) {
                // Check if item is already selected
                const isSelected = selectedItems.some(selected => selected.id === item.id);
                
                if (!isSelected) {
                    const itemName = item.name || 'Unnamed Item';
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
                                <span class="item-price">LKR ${itemPrice.toFixed(2)}</span>
                                <span class="item-stock">${itemQuantity} available</span>
                            </div>
                        </div>
                    `;
                }
            });
            
            if (html === '') {
                html = '<div class="no-results p-3">All matching items are already added</div>';
            }
            
            resultsContainer.html(html);
        }

        // Handle item selection from global search
        $(document).on('click', '.search-result-item', function() {
            const item = $(this);
            const itemId = item.data('id');
            const itemName = item.data('name');
            const itemPrice = item.data('price');
            const itemQuantity = item.data('quantity');
            
            // Add to selected items array
            selectedItems.push({
                id: itemId,
                name: itemName,
                price: itemPrice,
                maxQuantity: itemQuantity,
                quantity: 1,
                warranty: ''
            });
            
            // Clear search and hide results
            $('#global-item-search').val('');
            $('.search-results-global').hide().empty();
            
            // Update display
            updateSelectedItemsDisplay();
        });

        // Update the selected items display
        function updateSelectedItemsDisplay() {
    const container = $('#selected-items-container');
    
    if (selectedItems.length === 0) {
        container.html('<div class="alert alert-info" id="no-items-message">No items selected yet. Search and select items above.</div>');
        $('#total_amount').val('0.00');
        return;
    }
    
    // Remove no items message if it exists
    $('#no-items-message').remove();
    
    let html = `
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Sale Price (LKR)</th>
                    <th>Quantity</th>
                    <th>Warranty</th>
                    <th>Total (LKR)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
    `;
    
    selectedItems.forEach((item, index) => {
        const itemTotal = (item.quantity * item.price).toFixed(2);
        
        html += `
            <tr class="selected-item-row" data-index="${index}">
                <td>${item.name}</td>
                <td>
                    <input type="number" class="form-control price-input" 
                           data-index="${index}" 
                           min="0" 
                           step="0.01" 
                           value="${item.price.toFixed(2)}" 
                           required>
                </td>
                <td>
                    <input type="number" class="form-control quantity-input" 
                           data-index="${index}" 
                           min="1" 
                           max="${item.maxQuantity}" 
                           value="${item.quantity}" 
                           required>
                    <small class="quantity-available">Available: ${item.maxQuantity}</small>
                </td>
                <td>
                    <input type="text" class="form-control warranty-input" 
                           data-index="${index}" 
                           value="${item.warranty}" 
                           placeholder="Enter warranty details">
                </td>
                <td class="item-total">${itemTotal}</td>

                <td>
                    <button type="button" class="btn btn-sm btn-danger remove-item-btn" data-index="${index}">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
                <input type="hidden" name="stock_id[]" value="${item.id}">
                <input type="hidden" name="warranty[]" class="warranty-hidden" value="${item.warranty}">
                <input type="hidden" name="qty[]" class="quantity-hidden" value="${item.quantity}">
                <input type="hidden" name="unit_price[]" class="price-hidden" value="${item.price}">
            </tr>
        `;
    });
    
    html += `
            </tbody>
        </table>
    `;
    
    container.html(html);
    calculateTotal();
}

        
        // Handle quantity, price, and warranty changes
        $(document).on('input', '.quantity-input, .price-input, .warranty-input', function () {
            const index = $(this).data('index');
            const value = $(this).val();

            if ($(this).hasClass('quantity-input')) {
                const maxQty = selectedItems[index].maxQuantity;
                let qty = parseInt(value) || 1;

                if (qty > maxQty) {
                    qty = maxQty;
                    $(this).val(maxQty);
                    Swal.fire({
                        icon: 'warning',
                        title: 'Quantity Exceeded',
                        text: `Maximum available quantity is ${maxQty}`,
                        confirmButtonColor: '#0d6efd'
                    });
                }

                selectedItems[index].quantity = qty;
                $(this).closest('tr').find('.quantity-hidden').val(qty);
            } 
            else if ($(this).hasClass('price-input')) {
                const price = parseFloat(value) || 0;
                selectedItems[index].price = price;
                $(this).closest('tr').find('.price-hidden').val(price);
            }
            else if ($(this).hasClass('warranty-input')) {
                selectedItems[index].warranty = value;
                $(this).closest('tr').find('.warranty-hidden').val(value);
            }

            // ✅ Update the total for the row
            const itemTotal = (selectedItems[index].quantity * selectedItems[index].price).toFixed(2);
            $(this).closest('tr').find('.item-total').text(itemTotal);

            calculateTotal();
        });


        // Handle item removal
        $(document).on('click', '.remove-item-btn', function() {
            const index = $(this).data('index');
            selectedItems.splice(index, 1);
            updateSelectedItemsDisplay();
        });

        // Calculate total amount
        function calculateTotal() {
            let total = 0;
            selectedItems.forEach(item => {
                total += item.quantity * item.price;
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
            selectedItems = [];
            updateSelectedItemsDisplay();
            $('#total_amount').val('0.00');
            $('#createInvoiceWithStockForm button[type="submit"]').prop('disabled', false).html('Create Invoice');
        });

        $('#deleteInvoiceModal').on('hidden.bs.modal', function () {
            $('#deleteInvoiceForm button[type="submit"]').prop('disabled', false).html('Delete');
        });

        // Handle form submission
        $('#createInvoiceWithStockForm').on('submit', function(e) {
            if (selectedItems.length === 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'No Items Selected',
                    text: 'Please select at least one item for the invoice',
                    confirmButtonColor: '#0d6efd'
                });
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