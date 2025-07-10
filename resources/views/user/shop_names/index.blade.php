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
            <form action="{{ route('user.shop_names.index') }}" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search by shop name..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i> Search
                        </button>
                        @if(request('search'))
                            <a href="{{ route('user.shop_names.index') }}" class="btn btn-outline-danger">Clear</a>
                        @endif
                    </div>
                </div>
            </form>
                   
            <div class="table-responsive">
                <table class="table table-striped" id="shop-table">
                    <thead class="thead-dark">
                        <tr>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Address</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shops as $shop)
                        <tr>
                            <td>{{ $shop->name }}</td>
                            <td>{{ $shop->contact ?? 'N/A' }}</td>
                            <td>{{ $shop->address ? Str::limit($shop->address, 30) : 'N/A' }}</td>
                            <td>{{ $shop->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <!-- Edit Button -->
                                <button class="btn btn-sm btn-warning edit-shop" 
                                        data-id="{{ $shop->id }}"
                                        data-bs-toggle="tooltip" title="Edit Shop">
                                    <i class="fas fa-edit"></i>
                                </button>
                                
                                <!-- Delete Button -->
                                <button class="btn btn-sm btn-danger delete-shop" 
                                        data-id="{{ $shop->id }}"
                                        data-name="{{ $shop->name }}"
                                        data-bs-toggle="tooltip" title="Delete Shop">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                
                                <!-- Add Repair Button -->
                                <button class="btn btn-sm btn-success add-repair-item" 
                                        data-id="{{ $shop->id }}"
                                        data-name="{{ $shop->name }}"
                                        data-bs-toggle="tooltip" title="Add New Repair">
                                    <i class="fas fa-plus-circle"></i> Add
                                </button>
                                
                                <!-- View Repairs Button -->
                                <a href="{{ route('user.shop_names.repair_items.index', $shop->id) }}" 
                                class="btn btn-sm btn-info"
                                data-bs-toggle="tooltip" title="View All Repairs">
                                    <i class="fas fa-list-ul"></i> View
                                </a>
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
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createShopModalLabel">Add New Shop</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="createShopForm" method="POST" action="{{ route('user.shop_names.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="create_name">Name *</label>
                        <input type="text" class="form-control" id="create_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="create_contact">Contact</label>
                        <input type="text" class="form-control" id="create_contact" name="contact">
                    </div>
                    <div class="form-group">
                        <label for="create_address">Address</label>
                        <textarea class="form-control" id="create_address" name="address" rows="3"></textarea>
                    </div>
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
    <div class="modal-dialog" role="document">
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
                    <div class="form-group">
                        <label for="edit_name">Name *</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_contact">Contact</label>
                        <input type="text" class="form-control" id="edit_contact" name="contact">
                    </div>
                    <div class="form-group">
                        <label for="edit_address">Address</label>
                        <textarea class="form-control" id="edit_address" name="address" rows="3"></textarea>
                    </div>
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

<!-- Add Repair Items Modal -->
<div class="modal fade" id="addRepairItemsModal" tabindex="-1" role="dialog" aria-labelledby="addRepairItemsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRepairItemsModalLabel">Add Repair Items to <span id="shop-name-title"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addRepairItemsForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div id="repair-items-container">
                        <!-- Items will be added dynamically -->
                    </div>
                    <button type="button" id="add-another-item" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add Another Item
                    </button>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Items</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .repair-item {
        background-color: #f8f9fa;
        position: relative;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    .repair-item:not(:last-child) {
        margin-bottom: 1rem;
    }
    .remove-item {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 10;
    }
    
    /* Enhanced checkbox styling */
    .checkbox-wrapper {
        display: flex;
        align-items: center;
        min-height: 38px;
    }
    
    .checkbox-wrapper .form-check {
        margin-bottom: 0;
    }
    
    .checkbox-wrapper .form-check-input {
        width: 18px;
        height: 18px;
        margin-top: 0;
        margin-right: 8px;
        cursor: pointer;
    }
    
    .checkbox-wrapper .form-check-label {
        cursor: pointer;
        font-weight: 500;
        margin-bottom: 0;
    }
    
    /* Custom checkbox styling for better visibility */
    .form-check-input[type="checkbox"] {
        border-radius: 3px;
        border: 2px solid #ced4da;
        transition: all 0.2s ease-in-out;
    }
    
    .form-check-input[type="checkbox"]:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    .form-check-input[type="checkbox"]:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    
    /* Hardware specifications section styling */
    .hardware-specs {
        background-color: #ffffff;
        border: 1px solid #e9ecef;
        border-radius: 0.375rem;
        padding: 1rem;
        margin-top: 1rem;
    }
    
    .hardware-specs h6 {
        margin-bottom: 1rem;
        color: #495057;
        font-weight: 600;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function () {
        // Handle Add Shop button click
        $('#addShopBtn').on('click', function() {
            $('#createShopModal').modal('show');
        });

        // Handle Edit button click
        $(document).on('click', '.edit-shop', function() {
            var shopId = $(this).data('id');
            
            // Fetch shop data via AJAX
            $.get("{{ route('user.shop_names.edit', ':id') }}".replace(':id', shopId), function(data) {
                // Populate the edit form
                $('#edit_name').val(data.name);
                $('#edit_contact').val(data.contact);
                $('#edit_address').val(data.address);
                
                // Set the form action URL
                var actionUrl = "{{ route('user.shop_names.update', ':id') }}";
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

        // Handle Delete button click
        $(document).on('click', '.delete-shop', function() {
            var shopId = $(this).data('id');
            var shopName = $(this).data('name');
            
            // Set the shop name in the confirmation message
            $('#delete_shop_name').text(shopName);
            
            // Set the form action URL with the correct shop ID
            var actionUrl = "{{ route('user.shop_names.destroy', ':id') }}";
            actionUrl = actionUrl.replace(':id', shopId);
            $('#deleteShopForm').attr('action', actionUrl);
            
            // Show the modal
            $('#deleteShopModal').modal('show');
        });

        // Handle Add Repair Item button click
        $(document).on('click', '.add-repair-item', function() {
            var shopId = $(this).data('id');
            var shopName = $(this).data('name');
            
            // Set the shop name in the modal title
            $('#shop-name-title').text(shopName);
            
            // Set the form action URL
            var actionUrl = "{{ route('user.shop_names.repair_items.store', ':id') }}";
            actionUrl = actionUrl.replace(':id', shopId);
            $('#addRepairItemsForm').attr('action', actionUrl);
            
            // Reset the form and add one empty item
            $('#repair-items-container').html('');
            itemCounter = 0;
            addRepairItem(0);
            
            // Show the modal
            $('#addRepairItemsModal').modal('show');
        });

        // Function to add a new repair item
        var itemCounter = 0;
        function addRepairItem(index) {
            var html = `
            <div class="repair-item" data-index="${index}">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="item_name_${index}">Item Name *</label>
                            <input type="text" class="form-control" id="item_name_${index}" name="repair_items[${index}][item_name]" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="serial_number_${index}">Serial Number (Optional)</label>
                            <input type="text" class="form-control" id="serial_number_${index}" name="repair_items[${index}][serial_number]">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="price_${index}">Price (LKR) (Optional)</label>
                            <input type="number" step="0.01" class="form-control" id="price_${index}" name="repair_items[${index}][price]">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="date_${index}">Date *</label>
                            <input type="date" class="form-control" id="date_${index}" name="repair_items[${index}][date]" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="status_${index}">Status *</label>
                            <select class="form-control" id="status_${index}" name="repair_items[${index}][status]" required>
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                                <option value="canceled">Canceled</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Hardware Specifications Section -->
                <div class="hardware-specs">
                    <h6><i class="fas fa-microchip"></i> Hardware Specifications</h6>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ram_${index}">RAM</label>
                                <select class="form-control" id="ram_${index}" name="repair_items[${index}][ram]">
                                    <option value="">Select RAM</option>
                                    <option value="4GB">4GB</option>
                                    <option value="8GB">8GB</option>
                                    <option value="12GB">12GB</option>
                                    <option value="16GB">16GB</option>
                                    <option value="32GB">32GB</option>
                                    <option value="64GB">64GB</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="checkbox-wrapper">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="hdd_${index}" name="repair_items[${index}][hdd]" value="1">
                                            <label class="form-check-label" for="hdd_${index}">
                                                <i class="fas fa-hdd"></i> HDD
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="checkbox-wrapper">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="ssd_${index}" name="repair_items[${index}][ssd]" value="1">
                                            <label class="form-check-label" for="ssd_${index}">
                                                <i class="fas fa-save"></i> SSD
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="checkbox-wrapper">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="nvme_${index}" name="repair_items[${index}][nvme]" value="1">
                                            <label class="form-check-label" for="nvme_${index}">
                                                <i class="fas fa-bolt"></i> NVMe
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <div class="checkbox-wrapper">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="battery_${index}" name="repair_items[${index}][battery]" value="1">
                                    <label class="form-check-label" for="battery_${index}">
                                        <i class="fas fa-battery-half"></i> Battery
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="checkbox-wrapper">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="dvd_rom_${index}" name="repair_items[${index}][dvd_rom]" value="1">
                                    <label class="form-check-label" for="dvd_rom_${index}">
                                        <i class="fas fa-compact-disc"></i> DVD ROM
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="checkbox-wrapper">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="keyboard_${index}" name="repair_items[${index}][keyboard]" value="1">
                                    <label class="form-check-label" for="keyboard_${index}">
                                        <i class="fas fa-keyboard"></i> Keyboard
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-group mt-3">
                    <label for="description_${index}">Description</label>
                    <textarea class="form-control" id="description_${index}" name="repair_items[${index}][description]" rows="2"></textarea>
                </div>
                
                <button type="button" class="btn btn-sm btn-danger remove-item mb-3">
                    <i class="fas fa-trash"></i> Remove Item
                </button>
            </div>
            `;
            $('#repair-items-container').append(html);
        }

        // Handle Add Another Item button click
        $(document).on('click', '#add-another-item', function() {
            itemCounter++;
            addRepairItem(itemCounter);
        });

        // Handle Remove Item button click
        $(document).on('click', '.remove-item', function() {
            if($('.repair-item').length > 1) {
                $(this).closest('.repair-item').remove();
                // Reindex all items
                reindexItems();
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Cannot remove',
                    text: 'You must have at least one repair item',
                    confirmButtonColor: '#0d6efd'
                });
            }
        });

        // Function to reindex all items after removal
        function reindexItems() {
            $('.repair-item').each(function(index) {
                $(this).attr('data-index', index);
                
                // Update all form elements
                $(this).find('input, select, textarea').each(function() {
                    var element = $(this);
                    var name = element.attr('name');
                    var id = element.attr('id');
                    
                    if (name) {
                        name = name.replace(/\[\d+\]/, '[' + index + ']');
                        element.attr('name', name);
                    }
                    
                    if (id) {
                        id = id.replace(/_\d+$/, '_' + index);
                        element.attr('id', id);
                    }
                });
                
                // Update labels
                $(this).find('label').each(function() {
                    var label = $(this);
                    var forAttr = label.attr('for');
                    if (forAttr) {
                        forAttr = forAttr.replace(/_\d+$/, '_' + index);
                        label.attr('for', forAttr);
                    }
                });
            });
            
            itemCounter = $('.repair-item').length - 1;
        }

        // Clear modal forms when modals are hidden
        $('#createShopModal').on('hidden.bs.modal', function () {
            $('#createShopForm')[0].reset();
            $('#createShopForm button[type="submit"]').prop('disabled', false).html('Save Shop');
        });

        $('#editShopModal').on('hidden.bs.modal', function () {
            $('#editShopForm button[type="submit"]').prop('disabled', false).html('Update Shop');
        });

        $('#deleteShopModal').on('hidden.bs.modal', function () {
            $('#deleteShopForm button[type="submit"]').prop('disabled', false).html('Delete');
        });

        $('#addRepairItemsModal').on('hidden.bs.modal', function () {
            $('#addRepairItemsForm button[type="submit"]').prop('disabled', false).html('Save Items');
            $('#repair-items-container').html('');
            itemCounter = 0;
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

        $('#addRepairItemsForm').on('submit', function() {
            $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
        });
    });
</script>
@endpush
@endsection