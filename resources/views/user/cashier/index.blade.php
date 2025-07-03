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
            <h4>Cashier Management</h4>
            <div>
                <button type="button" class="btn btn-primary" id="addCashierBtn">
                    <i class="fas fa-plus"></i> Add New Cashier
                </button>
            </div>
        </div>

        <div class="card-body">
            <!-- Search Form -->
            <form action="{{ route('user.cashier.index') }}" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search by cashier name..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i> Search
                        </button>
                        @if(request('search'))
                            <a href="{{ route('user.cashier.index') }}" class="btn btn-outline-danger">Clear</a>
                        @endif
                    </div>
                </div>
            </form>
                   
            <div class="table-responsive">
                <table class="table table-striped" id="cashier-table">
                    <thead class="thead-dark">
                        <tr>
                            <th>Name</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cashiers as $cashier)
                        <tr>
                            <td>{{ $cashier->name }}</td>
                            <td>{{ $cashier->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning edit-cashier" 
                                        data-id="{{ $cashier->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger delete-cashier" 
                                        data-id="{{ $cashier->id }}"
                                        data-name="{{ $cashier->name }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">No cashiers found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer text-right">
            <nav class="d-inline-block" aria-label="Pagination">
                {{ $cashiers->links('pagination::bootstrap-4') }}
            </nav>
        </div>
    </div>
</div>

<!-- Create Cashier Modal -->
<div class="modal fade" id="createCashierModal" tabindex="-1" role="dialog" aria-labelledby="createCashierModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createCashierModalLabel">Add New Cashier</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="createCashierForm" method="POST" action="{{ route('user.cashier.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="create_name">Name *</label>
                        <input type="text" class="form-control" id="create_name" name="name" required>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Cashier</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Cashier Modal -->
<div class="modal fade" id="editCashierModal" tabindex="-1" role="dialog" aria-labelledby="editCashierModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCashierModalLabel">Edit Cashier</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editCashierForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_name">Name *</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Cashier</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteCashierModal" tabindex="-1" role="dialog" aria-labelledby="deleteCashierModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCashierModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="delete_cashier_name"></strong>? This action cannot be undone.</p>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteCashierForm" method="POST" style="display: inline;">
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
        // Handle Add Cashier button click
        $('#addCashierBtn').on('click', function() {
            $('#createCashierModal').modal('show');
        });

        // Handle Edit button click
        $(document).on('click', '.edit-cashier', function() {
            var cashierId = $(this).data('id');
            
            // Fetch cashier data via AJAX
            $.get("{{ route('user.cashier.edit', ':id') }}".replace(':id', cashierId), function(data) {
                // Populate the edit form
                $('#edit_name').val(data.name);
                
                // Set the form action URL
                var actionUrl = "{{ route('user.cashier.update', ':id') }}";
                actionUrl = actionUrl.replace(':id', cashierId);
                $('#editCashierForm').attr('action', actionUrl);
                
                // Show the modal
                $('#editCashierModal').modal('show');
            }).fail(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load cashier data',
                    confirmButtonColor: '#0d6efd'
                });
            });
        });

        // Handle Delete button click
        $(document).on('click', '.delete-cashier', function() {
            var cashierId = $(this).data('id');
            var cashierName = $(this).data('name');
            
            // Set the cashier name in the confirmation message
            $('#delete_cashier_name').text(cashierName);
            
            // Set the form action URL with the correct cashier ID
            var actionUrl = "{{ route('user.cashier.destroy', ':id') }}";
            actionUrl = actionUrl.replace(':id', cashierId);
            $('#deleteCashierForm').attr('action', actionUrl);
            
            // Show the modal
            $('#deleteCashierModal').modal('show');
        });

        // Clear modal forms when modals are hidden
        $('#createCashierModal').on('hidden.bs.modal', function () {
            $('#createCashierForm')[0].reset();
            $('#createCashierForm button[type="submit"]').prop('disabled', false).html('Save Cashier');
        });

        $('#editCashierModal').on('hidden.bs.modal', function () {
            $('#editCashierForm button[type="submit"]').prop('disabled', false).html('Update Cashier');
        });

        $('#deleteCashierModal').on('hidden.bs.modal', function () {
            $('#deleteCashierForm button[type="submit"]').prop('disabled', false).html('Delete');
        });

        // Handle form submissions with loading states
        $('#createCashierForm').on('submit', function() {
            $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
        });

        $('#editCashierForm').on('submit', function() {
            $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');
        });

        $('#deleteCashierForm').on('submit', function() {
            $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Deleting...');
        });
    });
</script>
@endpush
@endsection