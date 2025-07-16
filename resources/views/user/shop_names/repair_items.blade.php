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
            <h4>Repair Items for {{ $shop->name }}</h4>
            <div>
                <a href="{{ route('user.shop_names.index') }}" class="btn btn-success">
                    <i class="fas fa-arrow-left"></i> Back to Shops
                </a>
                <button type="button" class="btn btn-primary" id="addRepairItemBtn">
                    <i class="fas fa-plus"></i> Add New Item
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="repair-items-table">
                    <thead class="thead-dark">
                        <tr>
                            <th>Item #</th>
                            <th>Name</th>
                            <th>Serial</th>
                            <th>Price (LKR)</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($repairItems as $item)
                        <tr>
                            <td>{{ $item->item_number }}</td>
                            <td>{{ $item->item_name }}</td>
                            <td>{{ $item->serial_number ?? '--' }}</td>
                            <td>{{ $item->price ? number_format($item->price, 2) : '--' }}</td>
                            <td>{{ $item->date->format('Y-m-d') }}</td>
                            <td>
                                <select class="form-control status-select" data-id="{{ $item->id }}">
                                    <option value="pending" {{ $item->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_progress" {{ $item->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ $item->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="canceled" {{ $item->status == 'canceled' ? 'selected' : '' }}>Canceled</option>
                                </select>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning edit-repair-item" 
                                        data-id="{{ $item->id }}"
                                        data-shop-id="{{ $shop->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger delete-repair-item" 
                                        data-id="{{ $item->id }}"
                                        data-number="{{ $item->item_number }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button class="btn btn-sm btn-info view-details" 
                                        data-item="{{ json_encode($item) }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No repair items found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($completedRepairs->count() > 0)
    <div class="card mt-4">
        <div class="card-header">
            <h4>Completed Repairs History</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Item #</th>
                            <th>Name</th>
                            <th>Completed Date</th>
                            <th>Final Price (LKR)</th>
                            <th>Notes</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($completedRepairs as $completed)
                        <tr>
                            <td>{{ $completed->repairItem->item_number }}</td>
                            <td>{{ $completed->repairItem->item_name }}</td>
                            <td>{{ $completed->created_at->format('Y-m-d H:i') }}</td>
                            <td>{{ number_format($completed->final_price, 2) }}</td>
                            <td>{{ Str::limit($completed->notes, 30) }}</td>
                            <td>
                                <button class="btn btn-sm btn-info view-completed-details" 
                                        data-completed="{{ json_encode($completed) }}"
                                        data-repair-item="{{ json_encode($completed->repairItem) }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Add Repair Items Modal -->
<div class="modal fade" id="addRepairItemsModal" tabindex="-1" role="dialog" aria-labelledby="addRepairItemsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRepairItemsModalLabel">Add Repair Items to {{ $shop->name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addRepairItemsForm" method="POST" action="{{ route('user.shop_names.repair_items.store', $shop->id) }}">
                @csrf
                <div class="modal-body">
                    <div id="repair-items-container">
                        <!-- Items will be added dynamically -->
                    </div>
                    <button type="button" id="add-another-item" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add Another Item
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Items</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Item Details Modal -->
<div class="modal fade" id="itemDetailsModal" tabindex="-1" role="dialog" aria-labelledby="itemDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="itemDetailsModalLabel">Repair Item Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="itemDetailsContent">
                <!-- Details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteRepairItemModal" tabindex="-1" role="dialog" aria-labelledby="deleteRepairItemModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteRepairItemModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete repair item <strong id="delete_item_number"></strong>? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteRepairItemForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Repair Item Modal -->
<div class="modal fade" id="editRepairItemModal" tabindex="-1" role="dialog" aria-labelledby="editRepairItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRepairItemModalLabel">Edit Repair Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editRepairItemForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_item_name">Item Name *</label>
                                <input type="text" class="form-control" id="edit_item_name" name="item_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_serial_number">Serial Number</label>
                                <input type="text" class="form-control" id="edit_serial_number" name="serial_number">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit_price">Price (LKR)</label>
                                <input type="number" step="0.01" class="form-control" id="edit_price" name="price">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit_date">Date *</label>
                                <input type="date" class="form-control" id="edit_date" name="date" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit_status">Status *</label>
                                <select class="form-control" id="edit_status" name="status" required>
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
                                    <label for="edit_ram">RAM</label>
                                    <select class="form-control" id="edit_ram" name="ram">
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
                                                <input type="checkbox" class="form-check-input" id="edit_hdd" name="hdd" value="1">
                                                <label class="form-check-label" for="edit_hdd">
                                                    <i class="fas fa-hdd"></i> HDD
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="checkbox-wrapper">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="edit_ssd" name="ssd" value="1">
                                                <label class="form-check-label" for="edit_ssd">
                                                    <i class="fas fa-save"></i> SSD
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="checkbox-wrapper">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="edit_nvme" name="nvme" value="1">
                                                <label class="form-check-label" for="edit_nvme">
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
                                        <input type="checkbox" class="form-check-input" id="edit_battery" name="battery" value="1">
                                        <label class="form-check-label" for="edit_battery">
                                            <i class="fas fa-battery-half"></i> Battery
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="checkbox-wrapper">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="edit_dvd_rom" name="dvd_rom" value="1">
                                        <label class="form-check-label" for="edit_dvd_rom">
                                            <i class="fas fa-compact-disc"></i> DVD ROM
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="checkbox-wrapper">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="edit_keyboard" name="keyboard" value="1">
                                        <label class="form-check-label" for="edit_keyboard">
                                            <i class="fas fa-keyboard"></i> Keyboard
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mt-3">
                        <label for="edit_description">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .badge {
        margin-right: 5px;
        margin-bottom: 5px;
    }
    .status-select {
        min-width: 150px;
        font-size: 12px;
        padding: 5px;
        border-radius: 0px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .status-select:focus {
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    .repair-item {
        background-color: #f8f9fa;
        position: relative;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    .remove-item {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 10;
    }
    .hardware-specs {
        background-color: #ffffff;
        border: 1px solid #e9ecef;
        border-radius: 0.375rem;
        padding: 1rem;
        margin-top: 1rem;
    }
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
</style>

@push('scripts')
<script>
    $(document).ready(function () {
        // Handle Add Repair Item button click
        $('#addRepairItemBtn').on('click', function() {
            $('#repair-items-container').html('');
            itemCounter = 0;
            addRepairItem(0);
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

        // Handle Delete Repair Item button click
        $(document).on('click', '.delete-repair-item', function() {
            var itemId = $(this).data('id');
            var itemNumber = $(this).data('number');
            
            $('#delete_item_number').text(itemNumber);
            
            var actionUrl = "{{ route('user.shop_names.repair_items.destroy', ['shop' => $shop->id, 'repairItem' => ':id']) }}";
            actionUrl = actionUrl.replace(':id', itemId);
            $('#deleteRepairItemForm').attr('action', actionUrl);
            
            $('#deleteRepairItemModal').modal('show');
        });

        // Handle View Details button click
        $(document).on('click', '.view-details', function() {
            var item = $(this).data('item');
            var formattedDate = new Date(item.date).toLocaleDateString();
            
            var html = `
                <div class="mb-3">
                    <h5>${item.item_name}</h5>
                    <p class="text-muted">Item #: ${item.item_number}</p>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Serial Number:</strong> ${item.serial_number || 'N/A'}</p>
                        <p><strong>Status:</strong> <span class="badge ${getStatusBadgeClass(item.status)}">${formatStatus(item.status)}</span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Date:</strong> ${formattedDate}</p>
                        <p><strong>Price:</strong> ${item.price ? 'LKR ' + parseFloat(item.price).toFixed(2) : 'N/A'}</p>
                    </div>
                </div>
                
                <div class="mb-3">
                    <h6>Hardware Specifications</h6>
                    <div>
                        ${item.ram ? '<span class="badge bg-info">RAM: ' + item.ram + '</span>' : ''}
                        ${item.hdd ? '<span class="badge bg-secondary">HDD</span>' : ''}
                        ${item.ssd ? '<span class="badge bg-primary">SSD</span>' : ''}
                        ${item.nvme ? '<span class="badge bg-success">NVMe</span>' : ''}
                        ${item.battery ? '<span class="badge bg-warning">Battery</span>' : ''}
                        ${item.dvd_rom ? '<span class="badge bg-dark text-white">DVD ROM</span>' : ''}
                        ${item.keyboard ? '<span class="badge bg-danger">Keyboard</span>' : ''}
                    </div>
                </div>
                
                <div class="mb-3">
                    <h6>Description</h6>
                    <p>${item.description || 'No description provided'}</p>
                </div>
            `;
            
            $('#itemDetailsContent').html(html);
            $('#itemDetailsModal').modal('show');
        });

        // Handle View Completed Details button click
        $(document).on('click', '.view-completed-details', function() {
            var completed = $(this).data('completed');
            var repairItem = $(this).data('repair-item');
            
            var html = `
                <div class="mb-3">
                    <h5>${repairItem.item_name}</h5>
                    <p class="text-muted">Item #: ${repairItem.item_number}</p>
                    <p class="text-success"><strong>Status: Completed</strong></p>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Completed Date:</strong> ${new Date(completed.created_at).toLocaleString()}</p>
                        <p><strong>Final Price:</strong> LKR ${parseFloat(completed.final_price).toFixed(2)}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Completed By:</strong> {{ Auth::user()->name }}</p>
                    </div>
                </div>
                
                <div class="mb-3">
                    <h6>Notes</h6>
                    <p>${completed.notes || 'No notes provided'}</p>
                </div>
                
                <div class="mb-3">
                    <h6>Original Details</h6>
                    <p><strong>Original Price:</strong> ${repairItem.price ? 'LKR ' + parseFloat(repairItem.price).toFixed(2) : 'N/A'}</p>
                    <p><strong>Description:</strong> ${repairItem.description || 'No description provided'}</p>
                </div>
            `;
            
            Swal.fire({
                title: 'Completed Repair Details',
                html: html,
                width: '800px',
                confirmButtonColor: '#0d6efd'
            });
        });

        // Handle Edit Repair Item button click
        $(document).on('click', '.edit-repair-item', function() {
            var shopId = $(this).data('shop-id');
            var repairItemId = $(this).data('id');
            
            var editUrl = "{{ route('user.shop_names.repair_items.edit', ['shop' => $shop->id, 'repairItem' => ':id']) }}"
                .replace(':id', repairItemId);
            
            var updateUrl = "{{ route('user.shop_names.repair_items.update', ['shop' => $shop->id, 'repairItem' => ':id']) }}"
                .replace(':id', repairItemId);
            
            $.get(editUrl, function(data) {
                $('#edit_item_name').val(data.item_name);
                $('#edit_serial_number').val(data.serial_number);
                $('#edit_price').val(data.price);
                $('#edit_date').val(new Date(data.date).toISOString().substr(0, 10));
                $('#edit_status').val(data.status);
                $('#edit_ram').val(data.ram);
                $('#edit_description').val(data.description);
                
                $('#edit_hdd').prop('checked', data.hdd == 1);
                $('#edit_ssd').prop('checked', data.ssd == 1);
                $('#edit_nvme').prop('checked', data.nvme == 1);
                $('#edit_battery').prop('checked', data.battery == 1);
                $('#edit_dvd_rom').prop('checked', data.dvd_rom == 1);
                $('#edit_keyboard').prop('checked', data.keyboard == 1);
                
                $('#editRepairItemForm').attr('action', updateUrl);
                $('#editRepairItemModal').modal('show');
            });
        });

        // Submit Edit Form
        $('#editRepairItemForm').on('submit', function(e) {
            e.preventDefault();
            
            var form = $(this);
            var url = form.attr('action');
            
            $.ajax({
                url: url,
                type: 'POST',
                data: form.serialize(),
                success: function(response) {
                    if(response.success) {
                        $('#editRepairItemModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            confirmButtonColor: '#0d6efd'
                        }).then(() => {
                            location.reload();
                        });
                    }
                },
                error: function(xhr) {
                    var errorMessage = xhr.responseJSON && xhr.responseJSON.message 
                        ? xhr.responseJSON.message 
                        : 'Failed to update repair item';
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage,
                        confirmButtonColor: '#0d6efd'
                    });
                }
            });
        });

        // Handle status change
        $(document).on('change', '.status-select', function() {
            var itemId = $(this).data('id');
            var newStatus = $(this).val();
            var previousValue = $(this).data('previous-value');
            var url = "{{ route('user.shop_names.repair_items.update_status', ['shop' => $shop->id, 'repairItem' => ':id']) }}";
            url = url.replace(':id', itemId);
            
            if (newStatus === 'completed') {
                Swal.fire({
                    title: 'Complete Repair',
                    html: `
                        <div class="form-group">
                            <label for="final_price">Final Price</label>
                            <input type="number" step="0.01" class="form-control" id="final_price" required>
                        </div>
                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea class="form-control" id="notes" rows="3"></textarea>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Complete Repair',
                    confirmButtonColor: '#28a745',
                    cancelButtonText: 'Cancel',
                    focusConfirm: false,
                    preConfirm: () => {
                        return {
                            final_price: document.getElementById('final_price').value,
                            notes: document.getElementById('notes').value
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (!result.value.final_price) {
                            Swal.showValidationMessage('Final price is required');
                            return false;
                        }
                        
                        var data = {
                            _token: "{{ csrf_token() }}",
                            status: newStatus,
                            final_price: result.value.final_price,
                            notes: result.value.notes
                        };
                        
                        submitStatusChange(url, data, $(this), previousValue);
                    } else {
                        $(this).val(previousValue);
                    }
                });
            } else {
                var data = {
                    _token: "{{ csrf_token() }}",
                    status: newStatus
                };
                
                submitStatusChange(url, data, $(this), previousValue);
            }
        });

        function submitStatusChange(url, data, selectElement, previousValue) {
            $.ajax({
                url: url,
                method: 'PUT',
                data: data,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Status Updated',
                        text: response.message,
                        confirmButtonColor: '#0d6efd',
                        timer: 1500
                    });
                    
                    if (data.status === 'completed') {
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Failed to update status',
                        confirmButtonColor: '#0d6efd'
                    });
                    selectElement.val(previousValue);
                }
            });
        }

        // Store the previous value when focus is gained
        $(document).on('focus', '.status-select', function() {
            $(this).data('previous-value', $(this).val());
        });

        // Helper function to format status
        function formatStatus(status) {
            return status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
        }

        // Helper function to get status badge class
        function getStatusBadgeClass(status) {
            switch(status) {
                case 'pending': return 'bg-secondary';
                case 'in_progress': return 'bg-primary';
                case 'completed': return 'bg-success';
                case 'canceled': return 'bg-danger';
                default: return 'bg-secondary';
            }
        }

        // Function to reindex all items after removal
        function reindexItems() {
            $('.repair-item').each(function(index) {
                $(this).attr('data-index', index);
                
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
    });
</script>
@endpush
@endsection