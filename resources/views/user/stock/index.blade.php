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
            <h4>Stock Management</h4>
            <div>
                <button type="button" class="btn btn-primary" id="addStockBtn">
                    <i class="fas fa-plus"></i> Add New Item
                </button>
            </div>
        </div>

        <div class="card-body">
            <!-- Search Form -->
            <form action="{{ route('user.stock.index') }}" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search by item name or vendor..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i> Search
                        </button>
                        @if(request('search'))
                            <a href="{{ route('user.stock.index') }}" class="btn btn-outline-danger">Clear</a>
                        @endif
                    </div>
                </div>
            </form>
                   
            <div class="table-responsive">
                <table class="table table-striped" id="stock-table">
                    <thead class="thead-dark">
                        <tr>
                            <th>Item Name</th>
                            <th>Description</th>
                            <th>Cost</th>
                            <th>Wholesale</th>
                            <th>Retail</th>
                            <th>Vendor</th>
                            <th>Qty</th>
                            <th>Stock Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stocks as $stock)
                        <tr class="{{ $stock->quantity <= 2 ? 'table-danger' : '' }}">
                            <td>{{ $stock->item_name }}</td>
                            <td>{{ Str::limit($stock->description, 20) }}</td>
                            <td>{{ number_format($stock->cost, 2) }}</td>
                            <td>{{ number_format($stock->whole_sale_price, 2) }}</td>
                            <td>{{ number_format($stock->retail_price, 2) }}</td>
                            <td>
                                @if($stock->vender)
                                <a href="{{ route('user.stock.vendor.show', $stock->vender) }}" 
                                   class="btn btn-sm btn-outline-primary vendor-link"
                                   data-vendor="{{ $stock->vender }}">
                                    {{ $stock->vender }}
                                </a>
                                @else
                                <span class="text-muted">No vendor</span>
                                @endif
                            </td>
                            <td>{{ $stock->quantity }}</td>
                            <td>{{ \Carbon\Carbon::parse($stock->stock_date)->format('Y-m-d') }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning edit-stock" 
                                        data-id="{{ $stock->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger delete-stock" 
                                        data-id="{{ $stock->id }}"
                                        data-name="{{ $stock->item_name }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">No stock items found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer text-right">
            <nav class="d-inline-block" aria-label="Pagination">
                {{ $stocks->links('pagination::bootstrap-4') }}
            </nav>
        </div>
    </div>
</div>

<!-- Create Stock Modal -->
<div class="modal fade" id="createStockModal" tabindex="-1" role="dialog" aria-labelledby="createStockModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createStockModalLabel">Add New Stock Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="createStockForm" method="POST" action="{{ route('user.stock.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="create_item_name">Item Name *</label>
                                <input type="text" class="form-control" id="create_item_name" name="item_name" required>
                            </div>
                            <div class="form-group">
                                <label for="create_description">Description</label>
                                <textarea class="form-control" id="create_description" name="description" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="create_vender">Vendor (optional)</label>
                                <input type="text" class="form-control" id="create_vender" name="vender">
                                <small class="text-muted">Leave blank if no vendor</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="create_cost">Cost Price *</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="create_cost" name="cost" required>
                            </div>
                            <div class="form-group">
                                <label for="create_whole_sale_price">Wholesale Price *</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="create_whole_sale_price" name="whole_sale_price" required>
                            </div>
                            <div class="form-group">
                                <label for="create_retail_price">Retail Price *</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="create_retail_price" name="retail_price" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="create_quantity">Quantity *</label>
                                <input type="number" min="0" class="form-control" id="create_quantity" name="quantity" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="create_stock_date">Stock Date *</label>
                                <input type="date" class="form-control" id="create_stock_date" name="stock_date" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Stock Modal -->
<div class="modal fade" id="editStockModal" tabindex="-1" role="dialog" aria-labelledby="editStockModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editStockModalLabel">Edit Stock Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editStockForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_item_name">Item Name *</label>
                                <input type="text" class="form-control" id="edit_item_name" name="item_name" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_description">Description</label>
                                <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="edit_vender">Vendor (optional)</label>
                                <input type="text" class="form-control" id="edit_vender" name="vender">
                                <small class="text-muted">Leave blank to remove vendor</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_cost">Cost Price *</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="edit_cost" name="cost" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_whole_sale_price">Wholesale Price *</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="edit_whole_sale_price" name="whole_sale_price" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_retail_price">Retail Price *</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="edit_retail_price" name="retail_price" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_quantity">Quantity *</label>
                                <input type="number" min="0" class="form-control" id="edit_quantity" name="quantity" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_stock_date">Stock Date *</label>
                                <input type="date" class="form-control" id="edit_stock_date" name="stock_date" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Vendor Items Modal -->
<div class="modal fade" id="vendorItemsModal" tabindex="-1" role="dialog" aria-labelledby="vendorItemsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="vendorItemsModalLabel">Items from <span id="vendorNameTitle"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="vendor-items-table">
                        <thead>
                            <tr>
                                <th>Item Name</th>
                                <th>Cost</th>
                                <th>Wholesale</th>
                                <th>Retail</th>
                                <th>Qty</th>
                                <th>Stock Date</th>
                            </tr>
                        </thead>
                        <tbody id="vendorItemsBody">
                            <!-- Items will be loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteStockModal" tabindex="-1" role="dialog" aria-labelledby="deleteStockModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteStockModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="delete_stock_name"></strong>? This action cannot be undone.</p>
                <p class="text-danger">This will permanently remove the item from your inventory.</p>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteStockForm" method="POST" style="display: inline;">
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
        // Set today's date as default for create form
        $('#create_stock_date').val(new Date().toISOString().split('T')[0]);

        // Handle Add Stock button click
        $('#addStockBtn').on('click', function() {
            $('#createStockModal').modal('show');
        });

        // Handle Edit button click
        $(document).on('click', '.edit-stock', function() {
            var stockId = $(this).data('id');
            
            // Fetch stock data via AJAX
            $.get("{{ route('user.stock.edit', ':id') }}".replace(':id', stockId), function(data) {
                // Populate the edit form
                $('#edit_item_name').val(data.item_name);
                $('#edit_description').val(data.description);
                $('#edit_cost').val(data.cost);
                $('#edit_whole_sale_price').val(data.whole_sale_price);
                $('#edit_retail_price').val(data.retail_price);
                $('#edit_vender').val(data.vender || '');
                $('#edit_quantity').val(data.quantity);
                $('#edit_stock_date').val(data.stock_date.split('T')[0]);
                
                // Set the form action URL
                var actionUrl = "{{ route('user.stock.update', ':id') }}";
                actionUrl = actionUrl.replace(':id', stockId);
                $('#editStockForm').attr('action', actionUrl);
                
                // Show the modal
                $('#editStockModal').modal('show');
            }).fail(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load stock item data',
                    confirmButtonColor: '#0d6efd'
                });
            });
        });

        // Handle Vendor link click
        $(document).on('click', '.vendor-link', function(e) {
            e.preventDefault();
            var vendor = $(this).data('vendor');
            
            if (!vendor) {
                Swal.fire({
                    icon: 'info',
                    title: 'No Vendor',
                    text: 'This item has no vendor associated',
                    confirmButtonColor: '#0d6efd'
                });
                return;
            }
            
            // Set vendor name in modal title
            $('#vendorNameTitle').text(vendor);
            
            // Load items via AJAX
            $.get("{{ route('user.stock.vendor.show', ':vendor') }}".replace(':vendor', vendor), function(data) {
                var itemsHtml = '';
                
                if(data.length > 0) {
                    $.each(data, function(index, item) {
                        itemsHtml += `
                            <tr>
                                <td>${item.item_name}</td>
                                <td>${item.cost.toFixed(2)}</td>
                                <td>${item.whole_sale_price.toFixed(2)}</td>
                                <td>${item.retail_price.toFixed(2)}</td>
                                <td>${item.quantity}</td>
                                <td>${item.stock_date}</td>
                            </tr>
                        `;
                    });
                } else {
                    itemsHtml = '<tr><td colspan="6" class="text-center">No items found for this vendor</td></tr>';
                }
                
                $('#vendorItemsBody').html(itemsHtml);
                $('#vendorItemsModal').modal('show');
            }).fail(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load vendor items',
                    confirmButtonColor: '#0d6efd'
                });
            });
        });

        // Handle Delete button click
        $(document).on('click', '.delete-stock', function() {
            var stockId = $(this).data('id');
            var stockName = $(this).data('name');
            
            // Set the stock name in the confirmation message
            $('#delete_stock_name').text(stockName);
            
            // Set the form action URL with the correct stock ID
            var actionUrl = "{{ route('user.stock.destroy', ':id') }}";
            actionUrl = actionUrl.replace(':id', stockId);
            $('#deleteStockForm').attr('action', actionUrl);
            
            // Show the modal
            $('#deleteStockModal').modal('show');
        });

        // Clear modal forms when modals are hidden
        $('#createStockModal').on('hidden.bs.modal', function () {
            $('#createStockForm')[0].reset();
            $('#createStockForm button[type="submit"]').prop('disabled', false).html('Save Item');
        });

        $('#editStockModal').on('hidden.bs.modal', function () {
            $('#editStockForm button[type="submit"]').prop('disabled', false).html('Update Item');
        });

        $('#deleteStockModal').on('hidden.bs.modal', function () {
            $('#deleteStockForm button[type="submit"]').prop('disabled', false).html('Delete');
        });

        // Handle form submissions with loading states
        $('#createStockForm').on('submit', function() {
            $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
        });

        $('#editStockForm').on('submit', function() {
            $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');
        });

        $('#deleteStockForm').on('submit', function() {
            $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Deleting...');
        });

        // Auto-calculate prices if needed
        $('#create_cost, #edit_cost').on('change', function() {
            var cost = parseFloat($(this).val());
            if (!isNaN(cost)) {
                var retailPrice = cost * 1.3; // 30% markup
                var wholeSalePrice = cost * 1.15; // 15% markup
                
                // Get the current form context
                var formContext = $(this).closest('form');
                
                // Only auto-fill if the fields are empty
                if (formContext.find('#create_retail_price').length && formContext.find('#create_retail_price').val() === '') {
                    formContext.find('#create_retail_price').val(retailPrice.toFixed(2));
                    formContext.find('#create_whole_sale_price').val(wholeSalePrice.toFixed(2));
                }
                
                if (formContext.find('#edit_retail_price').length && formContext.find('#edit_retail_price').val() === '') {
                    formContext.find('#edit_retail_price').val(retailPrice.toFixed(2));
                    formContext.find('#edit_whole_sale_price').val(wholeSalePrice.toFixed(2));
                }
            }
        });
    });
</script>
@endpush
@endsection