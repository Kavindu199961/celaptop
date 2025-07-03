@extends('layouts.super-admin')

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
            <h4>User Management</h4>
            <div>
                <button type="button" class="btn btn-primary" id="addUserBtn">
                    <i class="fas fa-plus"></i> Add New User
                </button>
            </div>
        </div>

        <div class="card-body">
            <!-- Search Form -->
            <form action="{{ route('super-admin.users.index') }}" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search by name or email..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i> Search
                        </button>
                        @if(request('search'))
                            <a href="{{ route('super-admin.users.index') }}" class="btn btn-outline-danger">Clear</a>
                        @endif
                    </div>
                </div>
            </form>
                   
            <div class="table-responsive">
                <table class="table table-striped" id="users-table">
                    <thead class="thead-dark">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge badge-{{ $user->role === 'admin' ? 'primary' : 'secondary' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input toggle-status" 
                                           id="toggle-{{ $user->id }}" 
                                           data-id="{{ $user->id }}"
                                           {{ $user->is_active ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="toggle-{{ $user->id }}"></label>
                                </div>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning edit-user" 
                                        data-id="{{ $user->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger delete-user" 
                                        data-id="{{ $user->id }}"
                                        data-name="{{ $user->name }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No users found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer text-right">
            <nav class="d-inline-block" aria-label="Pagination">
                {{ $users->links('pagination::bootstrap-4') }}
            </nav>
        </div>
    </div>
</div>

<!-- Create User Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createUserModalLabel">Add New User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="createUserForm" method="POST" action="{{ route('super-admin.users.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="create_name">Name *</label>
                        <input type="text" class="form-control" id="create_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="create_email">Email *</label>
                        <input type="email" class="form-control" id="create_email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="create_password">Password *</label>
                        <input type="password" class="form-control" id="create_password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="create_password_confirmation">Confirm Password *</label>
                        <input type="password" class="form-control" id="create_password_confirmation" name="password_confirmation" required>
                    </div>
                    <div class="form-group">
                        <label for="create_role">Role *</label>
                        <select class="form-control" id="create_role" name="role" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                            
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="create_is_active" name="is_active" checked>
                            <label class="custom-control-label" for="create_is_active">Active</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_name">Name *</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_email">Email *</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_password">Password (Leave blank to keep current)</label>
                        <input type="password" class="form-control" id="edit_password" name="password">
                    </div>
                    <div class="form-group">
                        <label for="edit_password_confirmation">Confirm Password</label>
                        <input type="password" class="form-control" id="edit_password_confirmation" name="password_confirmation">
                    </div>
                    <div class="form-group">
                        <label for="edit_role">Role *</label>
                        <select class="form-control" id="edit_role" name="role" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="edit_is_active" name="is_active">
                            <label class="custom-control-label" for="edit_is_active">Active</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUserModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="delete_user_name"></strong>? This action cannot be undone.</p>
                <p class="text-danger">This will permanently remove the user and all their data.</p>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteUserForm" method="POST" style="display: inline;">
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
   
        // Handle Add User button click
        $(document).ready(function () {
        // Handle Add User button click - FIXED
        $('#addUserBtn').on('click', function() {
            $('#createUserModal').modal('show');
        });

        // Handle Edit button click - FIXED
        $(document).on('click', '.edit-user', function() {
            var userId = $(this).data('id');
            
            // Fetch user data via AJAX - FIXED route
            $.get("/super-admin/users/" + userId + "/edit", function(data) {
                // Populate the edit form
                $('#edit_name').val(data.name);
                $('#edit_email').val(data.email);
                $('#edit_role').val(data.role);
                $('#edit_is_active').prop('checked', data.is_active);
                
                // Set the form action URL
                var actionUrl = "/super-admin/users/" + userId;
                $('#editUserForm').attr('action', actionUrl);
                
                // Show the modal
                $('#editUserModal').modal('show');
            }).fail(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load user data',
                    confirmButtonColor: '#0d6efd'
                });
            });
        });

        // Handle status toggle
        $(document).on('change', '.toggle-status', function() {
            var userId = $(this).data('id');
            var isActive = $(this).is(':checked');
            
            $.ajax({
                url: "/super-admin/users/" + userId + "/toggle-status",
                type: 'PATCH',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (!response.success) {
                        // Revert the toggle if the request failed
                        $('#toggle-' + userId).prop('checked', !isActive);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to update user status',
                            confirmButtonColor: '#0d6efd'
                        });
                    }
                },
                error: function() {
                    // Revert the toggle if the request failed
                    $('#toggle-' + userId).prop('checked', !isActive);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to update user status',
                        confirmButtonColor: '#0d6efd'
                    });
                }
            });
        });

        // Handle Delete button click
        $(document).on('click', '.delete-user', function() {
            var userId = $(this).data('id');
            var userName = $(this).data('name');
            
            // Set the user name in the confirmation message
            $('#delete_user_name').text(userName);
            
            // Set the form action URL with the correct user ID
            var actionUrl = "/super-admin/users/" + userId + "/delete";
            $('#deleteUserForm').attr('action', actionUrl);
            
            // Show the modal
            $('#deleteUserModal').modal('show');
        });

        // Clear modal forms when modals are hidden
        $('#editUserModal').on('hidden.bs.modal', function () {
            $('#editUserForm button[type="submit"]').prop('disabled', false).html('Update User');
        });

        $('#deleteUserModal').on('hidden.bs.modal', function () {
            $('#deleteUserForm button[type="submit"]').prop('disabled', false).html('Delete');
        });

        // Handle form submissions with loading states
        $('#editUserForm').on('submit', function() {
            $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');
        });

        $('#deleteUserForm').on('submit', function() {
            $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Deleting...');
        });
    });
</script>
@endpush
@endsection