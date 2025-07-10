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

    <!-- Error Message -->
    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: '',
            text: '{{ session('error') }}',
            showConfirmButton: true,
            confirmButtonColor: '#0d6efd',
            confirmButtonText: 'OK',
            background: '#f8f9fa',
            iconColor: '#dc3545'
        });
    </script>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Completed Shop Repairs</h4>
        </div>

        <div class="card-body">
            <!-- Search Form -->
            <form action="{{ route('user.shop_completed_repair.index') }}" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search by item number or shop name..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i> Search
                        </button>
                        @if(request('search'))
                            <a href="{{ route('user.shop_completed_repair.index') }}" class="btn btn-outline-danger">Clear</a>
                        @endif
                    </div>
                </div>
            </form>
                   
            <div class="table-responsive">
                <table class="table table-striped" id="repair-table">
                    <thead class="thead-dark">
                        <tr>
                            <th>Shop Name</th>
                            <th>Item Number</th>
                            <th>Final Price (LKR)</th>
                            <th>Status</th>
                            <th>Completed Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($repairs as $repair)
                        <tr>
                            <td>{{ $repair->shop->name ?? 'N/A' }}</td>
                            <td>{{ $repair->repairItem->item_number ?? 'N/A' }}</td>
                            <td>{{ number_format($repair->final_price, 2) }}</td>
                            <td>
                                <span class="badge badge-{{ $repair->status === 'completed' ? 'success' : ($repair->status === 'cancelled' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($repair->status) }}
                                </span>
                            </td>
                            <td>{{ $repair->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <!-- <button class="btn btn-sm btn-warning edit-repair" 
                                        data-id="{{ $repair->id }}">
                                    <i class="fas fa-edit"></i>
                                </button> -->
                                <button class="btn btn-sm btn-danger delete-repair" 
                                        data-id="{{ $repair->id }}"
                                        data-name="{{ $repair->repairItem->item_number ?? 'Item' }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No repair records found</td>
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

<!-- Edit Repair Modal -->
<div class="modal fade" id="editRepairModal" tabindex="-1" role="dialog" aria-labelledby="editRepairModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRepairModalLabel">Edit Repair Record</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editRepairForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label>Shop Name</label>
                        <input type="text" class="form-control" id="edit_shop_name" readonly>
                    </div>
                    <div class="form-group">
                        <label>Item Number</label>
                        <input type="text" class="form-control" id="edit_item_number" readonly>
                    </div>
                    <div class="form-group">
                        <label for="edit_final_price">Final Price *</label>
                        <input type="number" class="form-control" id="edit_final_price" name="final_price" step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_status">Status *</label>
                        <select class="form-control" id="edit_status" name="status" required>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Repair</button>
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
                <p>Are you sure you want to delete repair record for <strong id="delete_repair_name"></strong>? This action cannot be undone.</p>
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

@push('scripts')
<script>
    $(document).ready(function () {
        // Handle Edit button click
        $(document).on('click', '.edit-repair', function() {
            var repairId = $(this).data('id');
            
            // Fetch repair data via AJAX
            $.get("{{ route('user.shop_completed_repair.edit', ':id') }}".replace(':id', repairId), function(data) {
                // Populate the edit form
                $('#edit_shop_name').val(data.shop_name);
                $('#edit_item_number').val(data.item_number);
                $('#edit_final_price').val(data.final_price);
                $('#edit_status').val(data.status);
                
                // Set the form action URL
                var actionUrl = "{{ route('user.shop_completed_repair.update', ':id') }}";
                actionUrl = actionUrl.replace(':id', repairId);
                $('#editRepairForm').attr('action', actionUrl);
                
                // Show the modal
                $('#editRepairModal').modal('show');
            }).fail(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load repair data',
                    confirmButtonColor: '#0d6efd'
                });
            });
        });

        // Handle Delete button click
        $(document).on('click', '.delete-repair', function() {
            var repairId = $(this).data('id');
            var repairName = $(this).data('name');
            
            // Set the repair name in the confirmation message
            $('#delete_repair_name').text(repairName);
            
            // Set the form action URL with the correct repair ID
            var actionUrl = "{{ route('user.shop_completed_repair.destroy', ':id') }}";
            actionUrl = actionUrl.replace(':id', repairId);
            $('#deleteRepairForm').attr('action', actionUrl);
            
            // Show the modal
            $('#deleteRepairModal').modal('show');
        });

        // Clear modal forms when modals are hidden
        $('#editRepairModal').on('hidden.bs.modal', function () {
            $('#editRepairForm button[type="submit"]').prop('disabled', false).html('Update Repair');
        });

        $('#deleteRepairModal').on('hidden.bs.modal', function () {
            $('#deleteRepairForm button[type="submit"]').prop('disabled', false).html('Delete');
        });

        // Handle form submissions with loading states
        $('#editRepairForm').on('submit', function() {
            $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');
        });

        $('#deleteRepairForm').on('submit', function() {
            $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Deleting...');
        });
    });
</script>
@endpush
@endsection