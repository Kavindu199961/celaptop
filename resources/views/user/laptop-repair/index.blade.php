@extends('layouts.app')

@section('content')
<div class="col-12 col-md-12 col-lg-12">
    <!-- Success Message -->
   @if(session('success'))
    <script>
        // Show SweetAlert
        Swal.fire({
            icon: 'success',
            title: '',
            text: @json(session('success')),
            showConfirmButton: true,
            confirmButtonColor: '#0d6efd',
            confirmButtonText: 'OK',
            background: '#f8f9fa',
            iconColor: '#28a745'
        });

        // Clear history state to prevent message on back
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
@endif

    <!-- Error Message -->
    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
            showConfirmButton: true,
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'OK',
            background: '#f8f9fa',
            iconColor: '#dc3545'
        });
    </script>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Laptop Repairs</h4>
            <button type="button" class="btn btn-primary" id="addRepairBtn">
                <i class="fas fa-plus"></i> Add New Repair
            </button>
        </div>

        <div class="card-body">
            <!-- Search Form -->
            <form action="{{ route('user.laptop-repair.index') }}" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search by customer number, name or Note number..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i> Search
                        </button>
                        @if(request('search'))
                            <a href="{{ route('user.laptop-repair.index') }}" class="btn btn-outline-danger">Clear</a>
                        @endif
                    </div>
                </div>
            </form>
                   
            <div class="table-responsive">
                <table class="table table-striped" id="repairs-table">
                    <thead class="thead-dark">
                        <tr>
                            <th>Customer #</th>
                            <th>Customer Name</th>
                            <th>Contact</th>
                            <th>Device</th>
                            <th>Serial Number</th>
                            <th>Note Number</th>
                            <th>Fault</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($repairs as $repair)
                        <tr>
                            <td>{{ $repair->customer_number }}</td>
                            <td>{{ $repair->customer_name }}</td>
                            <td>{{ $repair->contact }}</td>
                            <td>{{ $repair->device }}</td>
                            <td>{{ $repair->serial_number }}</td>
                            <td>{{ $repair->note_number }}</td>
                            <td>{{ Str::limit($repair->fault, 30) }}</td>
                            <td>
                                <div class="editable-price" data-id="{{ $repair->id }}">
                                    <span class="price-display">{{ number_format($repair->repair_price, 2) }}</span>
                                    <input type="number" step="0.01" class="form-control price-input d-none" value="{{ $repair->repair_price }}">
                                    <!-- <button class="btn btn-sm btn-link p-0 ml-1 edit-price-btn" title="Edit Price">
                                        <i class="fas fa-edit text-primary"></i>
                                    </button> -->
                                </div>
                            </td>
                            <td>
                                <select class="form-control status-select 
                                    @if($repair->status == 'pending') status-pending
                                    @elseif($repair->status == 'in_progress') status-progress
                                    @elseif($repair->status == 'completed') status-complete
                                    @elseif($repair->status == 'cancelled') status-cancelled
                                    @endif" 
                                    data-id="{{ $repair->id }}"
                                    data-current-status="{{ $repair->status }}">
                                    <option value="pending" {{ $repair->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_progress" {{ $repair->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ $repair->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $repair->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </td>
                            <td>{{ $repair->date->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('user.laptop-repair.show', $repair->id) }}" class="btn btn-sm btn-info" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button class="btn btn-sm btn-danger delete-repair" 
                                        data-id="{{ $repair->id }}"
                                        data-name="{{ $repair->customer_name }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center">No repairs found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer text-right">
            <nav class="d-inline-block" aria-label="Pagination">
                {{ $repairs->links('pagination::bootstrap-4') }}
            </nav>
        </div>
    </div>
</div>

<!-- Create Repair Modal -->
<div class="modal fade" id="createRepairModal" tabindex="-1" role="dialog" aria-labelledby="createRepairModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createRepairModalLabel">Add New Laptop Repair</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="createRepairForm" enctype="multipart/form-data" method="POST" action="{{ route('user.laptop-repair.store') }}">
                @csrf
                <div class="modal-body">
                    <!-- Customer Information -->
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Customer Information</h5>
                            <div class="form-group">
                                <label for="create_customer_name">Name</label>
                                <input type="text" class="form-control" id="create_customer_name" name="customer_name" required>
                            </div>
                            <div class="form-group">
                                <label for="create_contact">Contact</label>
                                <input type="text" class="form-control" id="create_contact" name="contact" required>
                            </div>
                            <div class="form-group">
                                <label for="create_email">Email (Optional)</label>
                                <input type="email" class="form-control" id="create_email" name="email" placeholder="Enter customer email for notifications">
                            </div>
                        </div>

                        <!-- Device Information -->
                        <div class="col-md-6">
                            <h5>Device Information</h5>
                            <div class="form-group">
                                <label for="create_device">Device</label>
                                <input type="text" class="form-control" id="create_device" name="device" required>
                            </div>
                            <div class="form-group">
                                <label for="create_serial_number">Serial Number</label>
                                <input type="text" class="form-control" id="create_serial_number" name="serial_number" required>
                            </div>
                        </div>
                    </div>

                    <!-- Specifications and Components -->
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Device Specifications</h5>
                            <div class="form-group">
                                <label for="create_ram">RAM</label>
                                <select class="form-control" id="create_ram" name="ram">
                                    <option value="">Select RAM</option>
                                    <option value="4GB">4GB</option>
                                    <option value="8GB">8GB</option>
                                    <option value="12GB">12GB</option>
                                    <option value="16GB">16GB</option>
                                    <option value="32GB">32GB</option>
                                    <option value="64GB">64GB</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Storage Options</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="create_hdd" name="hdd" value="1">
                                    <label class="custom-control-label" for="create_hdd">HDD</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="create_ssd" name="ssd" value="1">
                                    <label class="custom-control-label" for="create_ssd">SSD</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="create_nvme" name="nvme" value="1">
                                    <label class="custom-control-label" for="create_nvme">NVMe</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h5>Components</h5>
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="create_battery" name="battery" value="1">
                                    <label class="custom-control-label" for="create_battery">Battery</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="create_dvd_rom" name="dvd_rom" value="1">
                                    <label class="custom-control-label" for="create_dvd_rom">DVD ROM</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="create_keyboard" name="keyboard" value="1">
                                    <label class="custom-control-label" for="create_keyboard">Keyboard</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fault, Images, Price -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="create_fault">Fault Description</label>
                                <textarea class="form-control" id="create_fault" name="fault" rows="3" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="create_repair_price">Repair Price (Optional)</label>
                                <input type="number" step="0.01" class="form-control" id="create_repair_price" name="repair_price">
                            </div>
                            <div class="form-group">
                                <label for="create_note_number">Note Number</label>
                                <input type="text" class="form-control" id="create_note_number" name="note_number" placeholder="Enter Note Number" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="create_images">Upload Images (Optional)</label>
                                <input type="file" class="form-control" name="images[]" id="create_images" multiple accept="image/*">
                                <div id="imagePreviewContainer" class="mt-2 d-flex flex-wrap"></div>
                            </div>
                            <div class="form-group">
                                <label for="create_date">Date</label>
                                <input type="date" class="form-control" id="create_date" name="date" required value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Repair</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteRepairModal" tabindex="-1" role="dialog" aria-labelledby="deleteRepairModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteRepairModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this repair for <strong id="delete_repair_name"></strong>? This action cannot be undone.</p>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteRepairForm" method="POST" style="display: inline;">
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
    /* Status Colors */
    .status-pending {
        background-color: #ffc107 !important;
        color: #212529 !important;
        border-color: #ffc107 !important;
        font-weight: 600;
    }
    
    .status-progress {
        background-color: #17a2b8 !important;
        color: #ffffff !important;
        border-color: #17a2b8 !important;
        font-weight: 600;
    }
    
    .status-complete {
        background-color: #28a745 !important;
        color: #ffffff !important;
        border-color: #28a745 !important;
        font-weight: 600;
    }
    
    .status-cancelled {
        background-color: #dc3545 !important;
        color: #ffffff !important;
        border-color: #dc3545 !important;
        font-weight: 600;
    }
    
    /* Price editing styles */
    .editable-price {
        display: flex;
        align-items: center;
        min-width: 120px;
    }
    
    .price-display {
        font-weight: 600;
        color: #28a745;
    }
    
    .price-input {
        width: 80px !important;
        height: 30px !important;
        font-size: 12px !important;
        padding: 2px 5px !important;
    }
    
    .edit-price-btn {
        opacity: 0.7;
        transition: opacity 0.2s;
    }
    
    .edit-price-btn:hover {
        opacity: 1;
    }
    
    /* Table improvements */
    .table th {
        font-weight: 600;
        font-size: 14px;
    }
    
    .table td {
        vertical-align: middle;
        font-size: 13px;
    }
    
    .status-select {
        font-size: 12px;
        padding: 5px 10px;
        border-radius: 20px;
        border: 2px solid;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .status-select:focus {
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    
    /* Action buttons */
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        line-height: 1.5;
    }

    /* Loading state */
    .swal2-loading .swal2-title {
        margin-bottom: 1em;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        // Initialize the modal
        $('#createRepairModal').modal({
            show: false
        });

        // Handle Add Repair button click
       $('#addRepairBtn').on('click', function () {
    // Set today's date
    $('#create_date').val(new Date().toISOString().split('T')[0]);


    // Always show modal immediately to avoid "not opening" issues
    $('#createRepairModal').modal('show');

});

        // Handle Delete button click
        $(document).on('click', '.delete-repair', function() {
            var repairId = $(this).data('id');
            var repairName = $(this).data('name');
            
            $('#delete_repair_name').text(repairName);
            
            var actionUrl = "{{ route('user.laptop-repair.destroy', ':id') }}";
            actionUrl = actionUrl.replace(':id', repairId);
            $('#deleteRepairForm').attr('action', actionUrl);
            
            $('#deleteRepairModal').modal('show');
        });

        // Handle status change
        $(document).on('change', '.status-select', function() {
            var repairId = $(this).data('id');
            var newStatus = $(this).val();
            var currentStatus = $(this).data('current-status');
            var selectElement = $(this);
            var row = selectElement.closest('tr');
            
            // Remove existing status classes
            selectElement.removeClass('status-pending status-progress status-complete status-cancelled');
            
            // Add new status class based on actual status
            if (newStatus === 'pending') {
                selectElement.addClass('status-pending');
            } else if (newStatus === 'in_progress') {
                selectElement.addClass('status-progress');
            } else if (newStatus === 'completed') {
                selectElement.addClass('status-complete');
            } else if (newStatus === 'cancelled') {
                selectElement.addClass('status-cancelled');
            }
            
            $.ajax({
                url: "{{ route('user.laptop-repair.update-status', ':id') }}".replace(':id', repairId),
                method: 'PATCH',
                data: {
                    status: newStatus,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        if (newStatus === 'completed' && response.show_price_modal) {
                            // Show price update modal for completed repairs
                            Swal.fire({
                                title: 'Repair Completed',
                                text: 'Please confirm the final repair price',
                                input: 'number',
                                inputValue: row.find('.price-display').text().replace(/[^0-9.-]+/g,""),
                                inputAttributes: {
                                    step: '0.01',
                                    min: '0'
                                },
                                showCancelButton: true,
                                confirmButtonText: 'Confirm & Complete',
                                cancelButtonText: 'Cancel',
                                showLoaderOnConfirm: true,
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                preConfirm: (price) => {
                                    if (!price || price <= 0) {
                                        Swal.showValidationMessage('Please enter a valid price');
                                        return false;
                                    }
                                    
                                    return $.ajax({
                                        url: "{{ route('user.laptop-repair.complete', ':id') }}".replace(':id', repairId),
                                        method: 'PATCH',
                                        data: {
                                            repair_price: price,
                                            _token: '{{ csrf_token() }}'
                                        }
                                    }).then(response => {
                                        if (!response.success) {
                                            throw new Error(response.message || 'Failed to complete repair');
                                        }
                                        return response;
                                    }).catch(error => {
                                        console.error('Ajax error:', error);
                                        if (error.responseJSON && error.responseJSON.message) {
                                            Swal.showValidationMessage('Error: ' + error.responseJSON.message);
                                        } else {
                                            Swal.showValidationMessage('Error: ' + (error.message || 'An unexpected error occurred'));
                                        }
                                        return false;
                                    });
                                }
                            }).then((result) => {
                                if (result.isConfirmed && result.value) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Completed!',
                                        text: 'Repair has been completed and moved to completed repairs',
                                        showConfirmButton: false,
                                        timer: 2000
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                } else if (result.isDismissed) {
                                    // Revert to previous status
                                    Swal.fire({
                                        title: 'Reverting Status',
                                        text: 'Reverting repair status...',
                                        allowOutsideClick: false,
                                        showConfirmButton: false,
                                        willOpen: () => {
                                            Swal.showLoading();
                                        }
                                    });
                                    
                                    $.ajax({
                                        url: "{{ route('user.laptop-repair.update-status', ':id') }}".replace(':id', repairId),
                                        method: 'PATCH',
                                        data: {
                                            status: currentStatus,
                                            _token: '{{ csrf_token() }}'
                                        },
                                        success: function() {
                                            Swal.close();
                                            window.location.reload();
                                        },
                                        error: function() {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Error',
                                                text: 'Failed to revert status. Please refresh the page.',
                                                showConfirmButton: true
                                            }).then(() => {
                                                window.location.reload();
                                            });
                                        }
                                    });
                                }
                            });
                        } else {
                            // For other status changes
                            selectElement.data('current-status', newStatus);
                            Swal.fire({
                                icon: 'success',
                                title: 'Status Updated',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    }
                },
                error: function(xhr) {
                    // Revert the select to previous state on error
                    selectElement.val(currentStatus);
                    
                    // Re-add the original status class
                    selectElement.removeClass('status-pending status-progress status-complete status-cancelled');
                    if (currentStatus === 'pending') {
                        selectElement.addClass('status-pending');
                    } else if (currentStatus === 'in_progress') {
                        selectElement.addClass('status-progress');
                    } else if (currentStatus === 'completed') {
                        selectElement.addClass('status-complete');
                    } else if (currentStatus === 'cancelled') {
                        selectElement.addClass('status-cancelled');
                    }

                    // Show error message
                    let errorMessage = xhr.responseJSON?.message || 'Failed to update status';
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage,
                        showConfirmButton: true
                    });
                }
            });
        });

        // Handle price editing
        $(document).on('click', '.edit-price-btn', function() {
            var priceContainer = $(this).closest('.editable-price');
            var priceDisplay = priceContainer.find('.price-display');
            var priceInput = priceContainer.find('.price-input');
            var editBtn = $(this);
            
            // Show input, hide display
            priceDisplay.addClass('d-none');
            priceInput.removeClass('d-none').focus();
            editBtn.html('<i class="fas fa-check text-success"></i>');
            editBtn.removeClass('edit-price-btn').addClass('save-price-btn');
        });

        // Handle price save
        $(document).on('click', '.save-price-btn', function() {
            var priceContainer = $(this).closest('.editable-price');
            var priceDisplay = priceContainer.find('.price-display');
            var priceInput = priceContainer.find('.price-input');
            var editBtn = $(this);
            var repairId = priceContainer.data('id');
            var newPrice = priceInput.val();
            
            if (!newPrice || newPrice < 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Price',
                    text: 'Please enter a valid price',
                    showConfirmButton: true
                });
                return;
            }

            $.ajax({
                url: "{{ route('user.laptop-repair.update-price', ':id') }}".replace(':id', repairId),
                method: 'PATCH',
                data: {
                    repair_price: newPrice,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        // Update display
                        priceDisplay.text(response.new_price);
                        priceDisplay.removeClass('d-none');
                        priceInput.addClass('d-none');
                        editBtn.html('<i class="fas fa-edit text-primary"></i>');
                        editBtn.removeClass('save-price-btn').addClass('edit-price-btn');
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Price Updated',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Failed to update price',
                        showConfirmButton: true
                    });
                }
            });
        });

        // Handle Enter key for price input
        $(document).on('keypress', '.price-input', function(e) {
            if (e.which === 13) {
                $(this).siblings('.save-price-btn').click();
            }
        });

        // Handle Escape key to cancel price editing
        $(document).on('keyup', '.price-input', function(e) {
            if (e.which === 27) {
                var priceContainer = $(this).closest('.editable-price');
                var priceDisplay = priceContainer.find('.price-display');
                var editBtn = priceContainer.find('.save-price-btn');
                
                priceDisplay.removeClass('d-none');
                $(this).addClass('d-none');
                editBtn.html('<i class="fas fa-edit text-primary"></i>');
                editBtn.removeClass('save-price-btn').addClass('edit-price-btn');
            }
        });

        // Clear modal forms when modals are hidden
        $('#createRepairModal').on('hidden.bs.modal', function () {
            $('#createRepairForm')[0].reset();
            $('#createRepairForm button[type="submit"]').prop('disabled', false).html('Save Repair');
        });

        $('#deleteRepairModal').on('hidden.bs.modal', function () {
            $('#deleteRepairForm button[type="submit"]').prop('disabled', false).html('Delete');
        });

        // Handle form submissions with loading states
        $('#createRepairForm').on('submit', function() {
            $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
        });

        $('#deleteRepairForm').on('submit', function() {
            $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Deleting...');
        });
    });

    let selectedFiles = [];

const input = document.getElementById('create_images');
const container = document.getElementById('imagePreviewContainer');

input.addEventListener('change', function (event) {
    const newFiles = Array.from(event.target.files);

    // Append new files (prevent duplicates by checking name + size)
    newFiles.forEach(file => {
        const isDuplicate = selectedFiles.some(existing =>
            existing.name === file.name && existing.size === file.size
        );
        if (!isDuplicate) {
            selectedFiles.push(file);
        }
    });

    // Clear file input so selecting the same file again still triggers change
    input.value = '';

    // Refresh preview
    showPreviews();
});

function showPreviews() {
    container.innerHTML = '';

    selectedFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function (e) {
            const previewBox = document.createElement('div');
            previewBox.className = 'position-relative mr-2 mb-2';

            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.width = '120px';
            img.style.height = '120px';
            img.style.objectFit = 'cover';
            img.className = 'img-thumbnail';

            const removeBtn = document.createElement('span');
            removeBtn.innerHTML = '&times;';
            removeBtn.className = 'position-absolute text-white bg-danger rounded-circle text-center';
            removeBtn.style.cssText = `
                width: 18px;
                height: 18px;
                top: -6px;
                right: -6px;
                font-size: 14px;
                cursor: pointer;
                line-height: 18px;
            `;
            removeBtn.title = 'Remove';

            removeBtn.addEventListener('click', function () {
                selectedFiles.splice(index, 1);
                showPreviews();
            });

            previewBox.appendChild(img);
            previewBox.appendChild(removeBtn);
            container.appendChild(previewBox);
        };
        reader.readAsDataURL(file);
    });
}

// Intercept form submit to update the input field manually
document.getElementById('createRepairForm').addEventListener('submit', function (e) {
    const dataTransfer = new DataTransfer();
    selectedFiles.forEach(file => dataTransfer.items.add(file));
    input.files = dataTransfer.files;
});
</script>
@endpush
@endsection