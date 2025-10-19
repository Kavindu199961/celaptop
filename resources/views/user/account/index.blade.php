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
            <h4>Account Management</h4>
            <div>
                <button type="button" class="btn btn-success mr-2" id="exportPdfBtn">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </button>
                <button type="button" class="btn btn-primary" id="addAccountBtn">
                    <i class="fas fa-plus"></i> Add New Record
                </button>
            </div>
        </div>

        <div class="card-body">
            <!-- Search and Filter Form -->
            <form action="{{ route('user.account.index') }}" method="GET" class="mb-4" id="filterForm">
                <div class="row">
                    <!-- Search Input -->
                    <div class="col-md-4 mb-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search by description..." value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-outline-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Date Filter -->
                    <div class="col-md-2 mb-3">
                        <input type="date" name="date" class="form-control" value="{{ request('date') }}" placeholder="Specific Date">
                    </div>

                    <!-- Date Range -->
                    <div class="col-md-3 mb-3">
                        <div class="input-group">
                            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}" placeholder="Start Date">
                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}" placeholder="End Date">
                        </div>
                    </div>

                    <!-- Month Filter -->
                    <div class="col-md-2 mb-3">
                        <select name="month" class="form-control">
                            <option value="">Select Month</option>
                            @foreach(range(1, 12) as $month)
                                <option value="{{ $month }}" {{ request('month') == $month ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $month)->format('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Year Filter -->
                    <div class="col-md-1 mb-3">
                        <select name="year" class="form-control">
                            <option value="">Year</option>
                            @foreach(range(date('Y'), date('Y') - 10) as $year)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Apply Filters
                        </button>
                        @if(request()->hasAny(['search', 'date', 'start_date', 'end_date', 'month', 'year']))
                            <a href="{{ route('user.account.index') }}" class="btn btn-outline-danger">
                                <i class="fas fa-times"></i> Clear Filters
                            </a>
                        @endif
                    </div>
                </div>
            </form>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h6>Total Income</h6>
                            <h4>Rs {{ number_format($totalIncome, 2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <h6>Total Expense</h6>
                            <h4>Rs {{ number_format(abs($totalExpense), 2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h6>Net Balance</h6>
                            <h4>Rs {{ number_format($netBalance, 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
                   
            <div class="table-responsive">
                <table class="table table-striped" id="account-table">
                    <thead class="thead-dark">
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($accounts as $account)
                        <tr>
                            <td>{{ $account->date->format('Y-m-d') }}</td>
                            <td>{{ $account->description }}</td>
                            <td>
                                <span class="{{ $account->amount >= 0 ? 'text-success' : 'text-danger' }}">
                                    Rs {{ number_format($account->amount, 2) }}
                                </span>
                            </td>
                            <td>
                                @if($account->amount >= 0)
                                    <span class="badge badge-success">Income</span>
                                @else
                                    <span class="badge badge-danger">Expense</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning edit-account" 
                                        data-id="{{ $account->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger delete-account" 
                                        data-id="{{ $account->id }}"
                                        data-description="{{ $account->description }}"
                                        data-amount="{{ $account->amount }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No account records found</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <!-- Net Amount Row -->
                    @if($accounts->count() > 0)
                    <tfoot class="bg-light">
                        <tr>
                            <td colspan="2" class="text-right"><strong>Net Amount:</strong></td>
                            <td colspan="3">
                                <strong class="{{ $netBalance >= 0 ? 'text-success' : 'text-danger' }}">
                                    Rs {{ number_format($netBalance, 2) }}
                                </strong>
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
        
        <div class="card-footer text-right">
            <nav class="d-inline-block" aria-label="Pagination">
                {{ $accounts->appends(request()->query())->links('pagination::bootstrap-4') }}
            </nav>
        </div>
    </div>
</div>

<!-- Create Account Modal -->
<div class="modal fade" id="createAccountModal" tabindex="-1" role="dialog" aria-labelledby="createAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createAccountModalLabel">Add New Account Record</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="createAccountForm" method="POST" action="{{ route('user.account.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="create_description">Description *</label>
                        <input type="text" class="form-control" id="create_description" name="description" required>
                    </div>
                    <div class="form-group">
                        <label for="create_amount">Amount *</label>
                        <input type="number" step="0.01" class="form-control" id="create_amount" name="amount" required>
                        <small class="form-text text-muted">
                            Use positive values for income, negative values for expense
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="create_date">Date *</label>
                        <input type="date" class="form-control" id="create_date" name="date" required value="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Record</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Account Modal -->
<div class="modal fade" id="editAccountModal" tabindex="-1" role="dialog" aria-labelledby="editAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAccountModalLabel">Edit Account Record</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editAccountForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_description">Description *</label>
                        <input type="text" class="form-control" id="edit_description" name="description" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_amount">Amount *</label>
                        <input type="number" step="0.01" class="form-control" id="edit_amount" name="amount" required>
                        <small class="form-text text-muted">
                            Use positive values for income, negative values for expense
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="edit_date">Date *</label>
                        <input type="date" class="form-control" id="edit_date" name="date" required>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Record</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" role="dialog" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAccountModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this account record?</p>
                <p><strong>Description:</strong> <span id="delete_account_description"></span></p>
                <p><strong>Amount:</strong> Rs <span id="delete_account_amount"></span></p>
                <p class="text-danger">This action cannot be undone.</p>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteAccountForm" method="POST" style="display: inline;">
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
        // Handle Add Account button click
        $('#addAccountBtn').on('click', function() {
            $('#createAccountModal').modal('show');
        });

        // Handle Export PDF button click
        $('#exportPdfBtn').on('click', function() {
            exportToPdf();
        });

        // Handle Edit button click
        $(document).on('click', '.edit-account', function() {
            var accountId = $(this).data('id');
            
            // Fetch account data via AJAX
            $.get("{{ route('user.account.edit', ':id') }}".replace(':id', accountId), function(data) {
                // Populate the edit form
                $('#edit_description').val(data.description);
                $('#edit_amount').val(data.amount);
                $('#edit_date').val(data.date);
                
                // Set the form action URL
                var actionUrl = "{{ route('user.account.update', ':id') }}";
                actionUrl = actionUrl.replace(':id', accountId);
                $('#editAccountForm').attr('action', actionUrl);
                
                // Show the modal
                $('#editAccountModal').modal('show');
            }).fail(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load account data',
                    confirmButtonColor: '#0d6efd'
                });
            });
        });

        // Handle Delete button click
        $(document).on('click', '.delete-account', function() {
            var accountId = $(this).data('id');
            var description = $(this).data('description');
            var amount = $(this).data('amount');
            
            // Set the account details in the confirmation message
            $('#delete_account_description').text(description);
            $('#delete_account_amount').text(Math.abs(amount).toFixed(2));
            
            // Set the form action URL with the correct account ID
            var actionUrl = "{{ route('user.account.destroy', ':id') }}";
            actionUrl = actionUrl.replace(':id', accountId);
            $('#deleteAccountForm').attr('action', actionUrl);
            
            // Show the modal
            $('#deleteAccountModal').modal('show');
        });

        // Export to PDF function
        function exportToPdf() {
            // Get current filter parameters
            const search = "{{ request('search') }}";
            const date = "{{ request('date') }}";
            const startDate = "{{ request('start_date') }}";
            const endDate = "{{ request('end_date') }}";
            const month = "{{ request('month') }}";
            const year = "{{ request('year') }}";

            // Show loading
            $('#exportPdfBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Generating PDF...');

            // Create form for PDF export
            const form = document.createElement('form');
            form.method = 'GET';
            form.action = "{{ route('user.account.export.pdf') }}";

            // Add current filter parameters
            if (search) addHiddenInput(form, 'search', search);
            if (date) addHiddenInput(form, 'date', date);
            if (startDate) addHiddenInput(form, 'start_date', startDate);
            if (endDate) addHiddenInput(form, 'end_date', endDate);
            if (month) addHiddenInput(form, 'month', month);
            if (year) addHiddenInput(form, 'year', year);

            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);

            // Re-enable button after a delay
            setTimeout(() => {
                $('#exportPdfBtn').prop('disabled', false).html('<i class="fas fa-file-pdf"></i> Export PDF');
            }, 3000);
        }

        function addHiddenInput(form, name, value) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = name;
            input.value = value;
            form.appendChild(input);
        }

        // Clear modal forms when modals are hidden
        $('#createAccountModal').on('hidden.bs.modal', function () {
            $('#createAccountForm')[0].reset();
            $('#createAccountForm button[type="submit"]').prop('disabled', false).html('Save Record');
        });

        $('#editAccountModal').on('hidden.bs.modal', function () {
            $('#editAccountForm button[type="submit"]').prop('disabled', false).html('Update Record');
        });

        $('#deleteAccountModal').on('hidden.bs.modal', function () {
            $('#deleteAccountForm button[type="submit"]').prop('disabled', false).html('Delete');
        });

        // Handle form submissions with loading states
        $('#createAccountForm').on('submit', function() {
            $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
        });

        $('#editAccountForm').on('submit', function() {
            $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');
        });

        $('#deleteAccountForm').on('submit', function() {
            $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Deleting...');
        });
    });
</script>
@endpush
@endsection