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
            <h4>Shop Management</h4>
            <div>
                <button type="button" class="btn btn-primary" id="addShopBtn">
                    <i class="fas fa-plus"></i> Add New Shop
                </button>
            </div>
        </div>

        <div class="card-body">
            <!-- Search Form -->
            <form action="{{ route('user.shop.index') }}" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search by shop name, phone or items..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i> Search
                        </button>
                        @if(request('search'))
                            <a href="{{ route('user.shop.index') }}" class="btn btn-outline-danger">Clear</a>
                        @endif
                    </div>
                </div>
            </form>
                   
            <div class="table-responsive">
                <table class="table table-striped" id="shop-table">
                    <thead class="thead-dark">
                        <tr>
                            <th>Shop Name</th>
                            <th>Phone</th>
                            <th>Items Count</th>
                            <th>Last Added</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shops as $shop)
                        <tr>
                            <td>{{ $shop->shop_name }}</td>
                            <td>{{ $shop->phone_number }}</td>
                            <td>{{ $shop->items->count() }}</td>
                            <td>{{ $shop->items->max('date') ? \Carbon\Carbon::parse($shop->items->max('date'))->format('Y-m-d') : 'N/A' }}</td>
                            <td>
                                <a href="{{ route('user.shop.show', $shop->id) }}" class="btn btn-sm btn-info">
        <i class="fas fa-eye"></i>
    </a>
                                <button class="btn btn-sm btn-warning edit-shop" 
                                        data-id="{{ $shop->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger delete-shop" 
                                        data-id="{{ $shop->id }}"
                                        data-name="{{ $shop->shop_name }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No shops found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer text-right">
            <nav class="d-inline-block" aria-label="Pagination">
                {{ $shops->links('pagination::bootstrap-4') }}
            </nav>
        </div>
    </div>
</div>

<!-- Create Shop Modal -->
<div class="modal fade" id="createShopModal" tabindex="-1" role="dialog" aria-labelledby="createShopModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createShopModalLabel">Add New Shop</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="createShopForm" method="POST" action="{{ route('user.shop.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="create_shop_name">Shop Name *</label>
                                <input type="text" class="form-control" id="create_shop_name" name="shop_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="create_phone_number">Phone Number *</label>
                                <input type="text" class="form-control" id="create_phone_number" name="phone_number" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="items-container">
                        <h5>Shop Items</h5>
                        <div class="item-group mb-3 p-3 border rounded">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Item Name *</label>
                                        <input type="text" class="form-control" name="items[0][item_name]" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Price (Optional)</label>
                                        <input type="number" step="0.01" min="0" class="form-control" name="items[0][price]">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Serial Number (Optional)</label>
                                        <input type="text" class="form-control" name="items[0][serial_number]">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea class="form-control" name="items[0][description]" rows="2"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Warranty (Optional)</label>
                                        <input type="text" class="form-control" name="items[0][warranty]">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Date</label>
                                        <input type="date" class="form-control" name="items[0][date]" value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" class="btn btn-sm btn-success add-item-btn">
                        <i class="fas fa-plus"></i> Add Another Item
                    </button>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Shop</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Edit Shop Modal -->
<div class="modal fade" id="editShopModal" tabindex="-1" role="dialog" aria-labelledby="editShopModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editShopModalLabel">Edit Shop</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editShopForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_shop_name">Shop Name *</label>
                                <input type="text" class="form-control" id="edit_shop_name" name="shop_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_phone_number">Phone Number *</label>
                                <input type="text" class="form-control" id="edit_phone_number" name="phone_number" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="edit-items-container">
                        <h5>Shop Items</h5>
                        <!-- Items will be loaded dynamically via JavaScript -->
                    </div>
                    
                    <button type="button" class="btn btn-sm btn-success add-edit-item-btn">
                        <i class="fas fa-plus"></i> Add Another Item
                    </button>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Shop</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteShopModal" tabindex="-1" role="dialog" aria-labelledby="deleteShopModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteShopModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="delete_shop_name"></strong>? This action cannot be undone.</p>
                <p class="text-danger">This will permanently remove the shop and all its items.</p>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteShopForm" method="POST" style="display: inline;">
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
        let itemCount = 1;
        
        // Handle Add Shop button click
        $('#addShopBtn').on('click', function() {
            $('#createShopModal').modal('show');
        });
        
        // Add new item to create form
        $('.add-item-btn').on('click', function() {
            const newItem = `
                <div class="item-group mb-3 p-3 border rounded">
                    <button type="button" class="btn btn-sm btn-danger float-right remove-item-btn">
                        <i class="fas fa-times"></i>
                    </button>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Item Name *</label>
                                <input type="text" class="form-control" name="items[${itemCount}][item_name]" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Price (Optional)</label>
                                <input type="number" step="0.01" min="0" class="form-control" name="items[${itemCount}][price]">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Serial Number (Optional)</label>
                                <input type="text" class="form-control" name="items[${itemCount}][serial_number]">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" name="items[${itemCount}][description]" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Warranty (Optional)</label>
                                <input type="text" class="form-control" name="items[${itemCount}][warranty]">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date</label>
                                <input type="date" class="form-control" name="items[${itemCount}][date]" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                </div>
            `;
            $('.items-container').append(newItem);
            itemCount++;
        });
        
        // Remove item from create form
        $(document).on('click', '.remove-item-btn', function() {
            $(this).closest('.item-group').remove();
        });
        
        // Handle View button click
       
        
        // Handle Edit button click
        $(document).on('click', '.edit-shop', function() {
            var shopId = $(this).data('id');
            
            // Fetch shop data via AJAX
            $.get("{{ route('user.shop.edit', ':id') }}".replace(':id', shopId), function(data) {
                // Populate the edit form
                $('#edit_shop_name').val(data.shop_name);
                $('#edit_phone_number').val(data.phone_number);
                
                // Clear existing items
                $('.edit-items-container').find('.item-group').remove();
                
                // Populate items
                if(data.items.length > 0) {
                    $.each(data.items, function(index, item) {
                        const itemHtml = `
                            <div class="item-group mb-3 p-3 border rounded">
                                <button type="button" class="btn btn-sm btn-danger float-right remove-item-btn">
                                    <i class="fas fa-times"></i>
                                </button>
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>Item Name *</label>
                                            <input type="text" class="form-control" name="items[${index}][item_name]" value="${item.item_name}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Price (Optional)</label>
                                            <input type="number" step="0.01" min="0" class="form-control" name="items[${index}][price]" value="${item.price}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Serial Number (Optional)</label>
                                            <input type="text" class="form-control" name="items[${index}][serial_number]" value="${item.serial_number || ''}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea class="form-control" name="items[${index}][description]" rows="2">${item.description || ''}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Warranty (Optional)</label>
                                            <input type="text" class="form-control" name="items[${index}][warranty]" value="${item.warranty || ''}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Date</label>
                                            <input type="date" class="form-control" name="items[${index}][date]" value="${item.date ? item.date.split('T')[0] : '{{ date('Y-m-d') }}'}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        $('.edit-items-container').append(itemHtml);
                    });
                }
                
                // Set the form action URL
                var actionUrl = "{{ route('user.shop.update', ':id') }}";
                actionUrl = actionUrl.replace(':id', shopId);
                $('#editShopForm').attr('action', actionUrl);
                
                // Show the modal
                $('#editShopModal').modal('show');
            }).fail(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load shop data',
                    confirmButtonColor: '#0d6efd'
                });
            });
        });
        
        // Add new item to edit form
        $('.add-edit-item-btn').on('click', function() {
            const itemCount = $('.edit-items-container').find('.item-group').length;
            const newItem = `
                <div class="item-group mb-3 p-3 border rounded">
                    <button type="button" class="btn btn-sm btn-danger float-right remove-item-btn">
                        <i class="fas fa-times"></i>
                    </button>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Item Name *</label>
                                <input type="text" class="form-control" name="items[${itemCount}][item_name]" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Price *</label>
                                <input type="number" step="0.01" min="0" class="form-control" name="items[${itemCount}][price]" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Serial Number</label>
                                <input type="text" class="form-control" name="items[${itemCount}][serial_number]">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" name="items[${itemCount}][description]" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Warranty</label>
                                <input type="text" class="form-control" name="items[${itemCount}][warranty]">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date</label>
                                <input type="date" class="form-control" name="items[${itemCount}][date]" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                </div>
            `;
            $('.edit-items-container').append(newItem);
        });
        
        // Handle Delete button click
        $(document).on('click', '.delete-shop', function() {
            var shopId = $(this).data('id');
            var shopName = $(this).data('name');
            
            // Set the shop name in the confirmation message
            $('#delete_shop_name').text(shopName);
            
            // Set the form action URL with the correct shop ID
            var actionUrl = "{{ route('user.shop.destroy', ':id') }}";
            actionUrl = actionUrl.replace(':id', shopId);
            $('#deleteShopForm').attr('action', actionUrl);
            
            // Show the modal
            $('#deleteShopModal').modal('show');
        });
        
        // Clear modal forms when modals are hidden
        $('#createShopModal').on('hidden.bs.modal', function () {
            $('#createShopForm')[0].reset();
            $('.items-container').find('.item-group').not(':first').remove();
            itemCount = 1;
            $('#createShopForm button[type="submit"]').prop('disabled', false).html('Save Shop');
        });
        
        $('#editShopModal').on('hidden.bs.modal', function () {
            $('#editShopForm button[type="submit"]').prop('disabled', false).html('Update Shop');
        });
        
        $('#deleteShopModal').on('hidden.bs.modal', function () {
            $('#deleteShopForm button[type="submit"]').prop('disabled', false).html('Delete');
        });
        
        // Handle form submissions with loading states
        $('#createShopForm').on('submit', function() {
            $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
        });
        
        $('#editShopForm').on('submit', function() {
            $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');
        });
        
        $('#deleteShopForm').on('submit', function() {
            $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Deleting...');
        });
    });
</script>
@endpush
@endsection