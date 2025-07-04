@extends('layouts.app')

@section('content')
<div class="col-12 col-md-12 col-lg-12">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Shop Details: {{ $shop->shop_name }}</h4>
            <div>
                <!-- <a href="{{ route('user.shop.edit', $shop->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a> -->
                <a href="{{ route('user.shop.index') }}" class="btn btn-info">
                    <i class="fas fa-arrow-left"></i> Back to Shops
                </a>

                 <button class="btn btn-sm btn-warning edit-shop" 
                                        data-id="{{ $shop->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Shop Information</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Shop Name</th>
                            <td>{{ $shop->shop_name }}</td>
                        </tr>
                        <tr>
                            <th>Phone Number</th>
                            <td>{{ $shop->phone_number }}</td>
                        </tr>
                        <tr>
                            <th>Total Items</th>
                            <td>{{ $shop->items->count() }}</td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $shop->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Last Updated</th>
                            <td>{{ $shop->updated_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <h5 class="mb-3">Shop Items</h5>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>Item Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Serial Number</th>
                            <th>Warranty</th>
                            <th>Date Added</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shop->items as $item)
                        <tr>
                            <td>{{ $item->item_name }}</td>
                            <td>{{ $item->description ?? '--' }}</td>
                            <td>{{ number_format($item->price, 2) }}</td>
                            <td>{{ $item->serial_number ?? '--' }}</td>
                            <td>{{ $item->warranty ?? '--' }}</td>
                            <td>{{ $item->date ? \Carbon\Carbon::parse($item->date)->format('Y-m-d') : 'N/A' }}</td>
                            
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No items found for this shop</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

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

<!-- View Item Modal -->
<div class="modal fade" id="viewItemModal" tabindex="-1" role="dialog" aria-labelledby="viewItemModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewItemModalLabel">Item Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="itemDetailsContent">
                <!-- Content will be loaded via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function () {
        // Handle View Item button click
        $(document).on('click', '.view-item', function() {
            var itemId = $(this).data('id');
            
            $.get("{{ url('shop/item') }}/" + itemId, function(data) {
                var html = `
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Item Name:</strong> ${data.item_name}</p>
                            <p><strong>Price:</strong> ${parseFloat(data.price).toFixed(2)}</p>
                            <p><strong>Date Added:</strong> ${data.date ? data.date.split('T')[0] : 'N/A'}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Serial Number:</strong> ${data.serial_number || 'N/A'}</p>
                            <p><strong>Warranty:</strong> ${data.warranty || 'N/A'}</p>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <p><strong>Description:</strong></p>
                            <p>${data.description || 'No description available'}</p>
                        </div>
                    </div>
                `;
                
                $('#itemDetailsContent').html(html);
                $('#viewItemModal').modal('show');
            }).fail(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load item details',
                    confirmButtonColor: '#0d6efd'
                });
            });
        });
    });

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

        $('#editShopForm').on('submit', function() {
            $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');
        });
        
        $('#deleteShopModal').on('hidden.bs.modal', function () {
            $('#deleteShopForm button[type="submit"]').prop('disabled', false).html('Delete');
        });
</script>
@endpush
@endsection