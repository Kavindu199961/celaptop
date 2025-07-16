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
            <h4>Credit Shop Management</h4>
            <div>
                <button type="button" class="btn btn-primary" id="addCreditShopBtn">
                    <i class="fas fa-plus"></i> Add New Credit Shop
                </button>
            </div>
        </div>

        <div class="card-body">
            <!-- Search Form -->
            <form action="{{ route('user.credit_shop.index') }}" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search by credit shop name..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i> Search
                        </button>
                        @if(request('search'))
                            <a href="{{ route('user.credit_shop.index') }}" class="btn btn-outline-danger">Clear</a>
                        @endif
                    </div>
                </div>
            </form>
                   
            <div class="table-responsive">
                <table class="table table-striped" id="credit-shop-table">
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
                            <td>{{ $shop->contact ?? '--' }}</td>
                            <td>{{ $shop->address ? Str::limit($shop->address, 30) : '--' }}</td>
                            <td>{{ $shop->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <!-- Edit Button -->
                                <button class="btn btn-sm btn-warning edit-credit-shop" 
                                        data-id="{{ $shop->id }}"
                                        data-name="{{ $shop->name }}"
                                        data-contact="{{ $shop->contact }}"
                                        data-address="{{ $shop->address }}"
                                        data-bs-toggle="tooltip" title="Edit Credit Shop">
                                    <i class="fas fa-edit"></i>
                                </button>
                                
                                <!-- Delete Button -->
                                <button class="btn btn-sm btn-danger delete-credit-shop" 
                                        data-id="{{ $shop->id }}"
                                        data-name="{{ $shop->name }}"
                                        data-bs-toggle="tooltip" title="Delete Credit Shop">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No credit shops found</td>
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

<!-- Create Credit Shop Modal -->
<div class="modal fade" id="createCreditShopModal" tabindex="-1" role="dialog" aria-labelledby="createCreditShopModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createCreditShopModalLabel">Add New Credit Shop</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="createCreditShopForm" method="POST" action="{{ route('user.credit_shop.store') }}">
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
                    <button type="submit" class="btn btn-primary" id="createSubmitBtn">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        Save Credit Shop
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Credit Shop Modal -->
<div class="modal fade" id="editCreditShopModal" tabindex="-1" role="dialog" aria-labelledby="editCreditShopModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCreditShopModalLabel">Edit Credit Shop</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editCreditShopForm" method="POST">
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
                    <button type="submit" class="btn btn-primary" id="editSubmitBtn">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        Update Credit Shop
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteCreditShopModal" tabindex="-1" role="dialog" aria-labelledby="deleteCreditShopModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCreditShopModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="delete_credit_shop_name"></strong>? This action cannot be undone.</p>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteCreditShopForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" id="deleteSubmitBtn">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();

        // Show create modal
        $('#addCreditShopBtn').click(function() {
            $('#createCreditShopModal').modal('show');
        });

        // Show edit modal with data
        $('.edit-credit-shop').click(function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const contact = $(this).data('contact');
            const address = $(this).data('address');
            
            $('#edit_name').val(name);
            $('#edit_contact').val(contact);
            $('#edit_address').val(address);
            
            $('#editCreditShopForm').attr('action', '/user/credit_shop/' + id);
            $('#editCreditShopModal').modal('show');
        });

        // Show delete confirmation modal
        $('.delete-credit-shop').click(function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            
            $('#delete_credit_shop_name').text(name);
            $('#deleteCreditShopForm').attr('action', '/user/credit_shop/' + id);
            $('#deleteCreditShopModal').modal('show');
        });

        // Form submission with spinners
        $('#createCreditShopForm').on('submit', function() {
            const btn = $('#createSubmitBtn');
            btn.prop('disabled', true);
            btn.find('.spinner-border').removeClass('d-none');
            btn.find('span:not(.spinner-border)').addClass('d-none');
        });

        $('#editCreditShopForm').on('submit', function() {
            const btn = $('#editSubmitBtn');
            btn.prop('disabled', true);
            btn.find('.spinner-border').removeClass('d-none');
            btn.find('span:not(.spinner-border)').addClass('d-none');
        });

        $('#deleteCreditShopForm').on('submit', function() {
            const btn = $('#deleteSubmitBtn');
            btn.prop('disabled', true);
            btn.find('.spinner-border').removeClass('d-none');
            btn.find('span:not(.spinner-border)').addClass('d-none');
        });

        // Clear modal forms when modals are hidden
        $('#createCreditShopModal').on('hidden.bs.modal', function () {
            $('#createCreditShopForm')[0].reset();
            const btn = $('#createSubmitBtn');
            btn.prop('disabled', false);
            btn.find('.spinner-border').addClass('d-none');
            btn.find('span:not(.spinner-border)').removeClass('d-none');
        });

        $('#editCreditShopModal').on('hidden.bs.modal', function () {
            const btn = $('#editSubmitBtn');
            btn.prop('disabled', false);
            btn.find('.spinner-border').addClass('d-none');
            btn.find('span:not(.spinner-border)').removeClass('d-none');
        });

        $('#deleteCreditShopModal').on('hidden.bs.modal', function () {
            const btn = $('#deleteSubmitBtn');
            btn.prop('disabled', false);
            btn.find('.spinner-border').addClass('d-none');
            btn.find('span:not(.spinner-border)').removeClass('d-none');
        });
    });
</script>
@endpush