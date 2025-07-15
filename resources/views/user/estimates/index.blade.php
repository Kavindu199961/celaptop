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
            <h4>Estimates</h4>
            <button type="button" class="btn btn-primary" id="addEstimateBtn">
                <i class="fas fa-plus"></i> Create New Estimate
            </button>
        </div>

        <div class="card-body">
            <!-- Search Form -->
            <form action="{{ route('user.estimates.index') }}" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search by estimate number, customer name or phone..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i> Search
                        </button>
                        @if(request('search'))
                            <a href="{{ route('user.estimates.index') }}" class="btn btn-outline-danger">Clear</a>
                        @endif
                    </div>
                </div>
            </form>
                   
            <div class="table-responsive">
                <table class="table table-striped" id="estimates-table">
                    <thead class="thead-dark">
                        <tr>
                            <th>Estimate #</th>
                            <th>Customer Name</th>
                            <th>Phone</th>
                            <th>Sales Rep</th>
                            <th>Total Amount</th>
                            <th>Date</th>
                            <th>Expiry</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($estimates as $estimate)
                        <tr>
                            <td>{{ $estimate->estimate_number }}</td>
                            <td>{{ $estimate->customer_name }}</td>
                            <td>{{ $estimate->customer_phone }}</td>
                            <td>{{ $estimate->sales_rep }}</td>
                            <td>{{ number_format($estimate->total_amount, 2) }}</td>
                            <td>{{ \Carbon\Carbon::parse($estimate->issue_date)->format('Y-m-d') }}</td>
                            <td>{{ \Carbon\Carbon::parse($estimate->expiry_date)->format('Y-m-d') }}</td>

                            <td>
                                <a href="{{ route('user.estimates.show', $estimate->id)}}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('user.estimates.print', $estimate->id) }}" 
                                class="btn btn-sm btn-success" 
                                target="_blank">
                                    <i class="fas fa-print"></i>
                                </a>
                                <a href="{{ route('user.estimates.download', $estimate->id) }}" 
                                class="btn btn-sm btn-primary" 
                                target="_blank">
                                    <i class="fas fa-download"></i>
                                </a>
                                <button class="btn btn-sm btn-danger delete-estimate" 
                                        data-id="{{ $estimate->id }}"
                                        data-name="{{ $estimate->customer_name }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No estimates found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer text-right">
            <nav class="d-inline-block" aria-label="Pagination">
                {{ $estimates->links('pagination::bootstrap-4') }}
            </nav>
        </div>
    </div>
</div>

<!-- Create Estimate Modal -->
<div class="modal fade" id="createEstimateModal" tabindex="-1" role="dialog" aria-labelledby="createEstimateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createEstimateModalLabel">Create New Estimate</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="createEstimateForm" method="POST" action="{{ route('user.estimates.store') }}">
                @csrf
                <div class="modal-body">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Customer Information</h5>
                            <div class="form-group">
                                <label for="customer_name">Customer Name</label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                            </div>
                            <div class="form-group">
                                <label for="customer_phone">Phone Number (Optional)</label>
                                <input type="text" class="form-control" id="customer_phone" name="customer_phone">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5>Estimate Details</h5>
                          
                            <div class="form-group">
                                <label for="sales_rep">Sales Representative</label>
                                <select class="form-control" id="sales_rep" name="sales_rep" required>
                                    @foreach($cashiers as $cashier)
                                        <option value="{{ $cashier->name }}">{{ $cashier->name }}</option>
                                    @endforeach
                                    <option value="{{ $shopName }}">{{ $shopName }}</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="issue_date">Estimate Date</label>
                                <input type="date" class="form-control" id="issue_date" name="issue_date" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="expiry_date">Expiry Date</label>
                                <input type="date" class="form-control" id="expiry_date" name="expiry_date" required>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    <h5>Estimate Items</h5>
                    
                    <div id="estimateItems">
                        <div class="row item-row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Description</label>
                                    <input type="text" class="form-control" name="description[]" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Warranty (Optional)</label>
                                    <input type="text" class="form-control" name="warranty[]">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Quantity</label>
                                    <input type="number" class="form-control qty" name="qty[]" min="1" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Unit Price</label>
                                    <input type="number" class="form-control unit-price" name="unit_price[]" min="0" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="button" class="btn btn-success btn-sm add-item">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-4 offset-md-8">
                            <div class="form-group">
                                <label>Total Amount</label>
                                <input type="text" class="form-control" id="total_amount" name="total_amount" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create Estimate</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteEstimateModal" tabindex="-1" role="dialog" aria-labelledby="deleteEstimateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteEstimateModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete estimate for <strong id="delete_estimate_name"></strong>? This action cannot be undone.</p>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteEstimateForm" method="POST" style="display: inline;">
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
        // Handle Add Estimate button click
        $('#addEstimateBtn').on('click', function() {
            $('#createEstimateModal').modal('show');
            // Set current date as default for issue date
            $('#issue_date').val(new Date().toISOString().substr(0, 10));
            // Set default expiry date (30 days from now)
            const expiryDate = new Date();
            expiryDate.setDate(expiryDate.getDate() + 30);
            $('#expiry_date').val(expiryDate.toISOString().substr(0, 10));
        });

        // Handle Add Item button click
        $(document).on('click', '.add-item', function() {
            const itemCount = $('.item-row').length;
            if(itemCount >= 43) {
                alert('Maximum 43 items allowed per estimate');
                return;
            }
            
            const newItem = `
                <div class="row item-row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <input type="text" class="form-control" name="description[]" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <input type="text" class="form-control" name="warranty[]">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <input type="number" class="form-control qty" name="qty[]" min="1" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <input type="number" class="form-control unit-price" name="unit_price[]" min="0" step="0.01" required>
                        </div>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-item">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            
            $('#estimateItems').append(newItem);
            calculateTotal();
        });

        // Handle Remove Item button click
        $(document).on('click', '.remove-item', function() {
            $(this).closest('.item-row').remove();
            calculateTotal();
        });

        // Calculate total amount when quantity or price changes
        $(document).on('input', '.qty, .unit-price', function() {
            calculateTotal();
        });

        // Calculate total amount function
        function calculateTotal() {
            let total = 0;
            $('.item-row').each(function() {
                const qty = parseFloat($(this).find('.qty').val()) || 0;
                const unitPrice = parseFloat($(this).find('.unit-price').val()) || 0;
                total += qty * unitPrice;
            });
            $('#total_amount').val(total.toFixed(2));
        }

        // Handle Delete button click
        $(document).on('click', '.delete-estimate', function() {
            const estimateId = $(this).data('id');
            const estimateName = $(this).data('name');
            
            $('#delete_estimate_name').text(estimateName);
            
            const actionUrl = "{{ route('user.estimates.destroy', ':id') }}".replace(':id', estimateId);
            $('#deleteEstimateForm').attr('action', actionUrl);
            
            $('#deleteEstimateModal').modal('show');
        });

        // Clear modal forms when modals are hidden
        $('#createEstimateModal').on('hidden.bs.modal', function () {
            $('#createEstimateForm')[0].reset();
            $('#estimateItems .item-row:not(:first)').remove();
            $('#total_amount').val('0.00');
            $('#createEstimateForm button[type="submit"]').prop('disabled', false).html('Create Estimate');
        });

        $('#deleteEstimateModal').on('hidden.bs.modal', function () {
            $('#deleteEstimateForm button[type="submit"]').prop('disabled', false).html('Delete');
        });

        // Handle form submissions with loading states
        $('#createEstimateForm').on('submit', function() {
            $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Creating...');
        });

        $('#deleteEstimateForm').on('submit', function() {
            $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Deleting...');
        });
    });
</script>
@endpush
@endsection