@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Completed Repairs</h4>
    </div>
    <div class="card-body">
        <!-- Search Form -->
        <form action="{{ route('user.complete-repair.index') }}" method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search by customer number, name, serial number or device..." value="{{ request('search') }}">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i> Search
                    </button>
                    @if(request('search'))
                        <a href="{{ route('user.complete-repair.index') }}" class="btn btn-outline-danger">Clear</a>
                    @endif
                </div>
            </div>
        </form>
               
        <div class="table-responsive">
            <table class="table table-striped" id="repairs-table">
                <thead class="thead-dark">
                    <tr>
                        <th>Customer Number</th>
                        <th>Customer Name</th>
                        <th>Contact</th>
                        <th>Device</th>
                        <th>Serial Number</th>
                        <th>Note Number</th>
                        <th>Fault</th>
                        <th>Repair Price</th>
                        <th>Date</th>
                        <th>Completed At</th>
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
                        <td>{{ number_format($repair->repair_price, 2) }}</td>
                        <td>{{ $repair->date->format('Y-m-d') }}</td>
                        <td>{{ $repair->completed_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <button class="btn btn-sm btn-danger delete-repair-btn" 
                                    data-id="{{ $repair->id }}"
                                    data-name="{{ $repair->customer_name }}"
                                    data-toggle="modal" 
                                    data-target="#deleteRepairModal">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center">No completed repairs found</td>
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

<!-- Delete Repair Modal -->
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
                <p>Are you sure you want to delete this repair record for <strong id="delete_repair_name"></strong>? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteRepairForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" id="deleteRepairButton">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        <span class="button-text">Delete</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Handle delete button click
    $(document).on('click', '.delete-repair-btn', function() {
        var repairId = $(this).data('id');
        var repairName = $(this).data('name');
        
        $('#delete_repair_name').text(repairName);
        
        var actionUrl = "{{ route('user.complete-repair.destroy', ':id') }}";
        actionUrl = actionUrl.replace(':id', repairId);
        $('#deleteRepairForm').attr('action', actionUrl);
    });

    // Handle form submission
    $('#deleteRepairForm').on('submit', function(e) {
        e.preventDefault();
        
        var form = $(this);
        var button = $('#deleteRepairButton');
        var spinner = button.find('.spinner-border');
        var buttonText = button.find('.button-text');
        
        // Show spinner and disable button
        spinner.removeClass('d-none');
        buttonText.text('Deleting...');
        button.prop('disabled', true);
        
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                $('#deleteRepairModal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message || 'Repair deleted successfully',
                    showConfirmButton: true,
                    confirmButtonColor: '#0d6efd',
                    confirmButtonText: 'OK',
                    background: '#f8f9fa',
                    iconColor: '#28a745'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON.message || 'Failed to delete repair',
                    showConfirmButton: true,
                    confirmButtonColor: '#0d6efd',
                    background: '#f8f9fa',
                    iconColor: '#dc3545'
                });
            },
            complete: function() {
                // Hide spinner and reset button
                spinner.addClass('d-none');
                buttonText.text('Delete');
                button.prop('disabled', false);
            }
        });
    });

    // Reset form when modal is closed
    $('#deleteRepairModal').on('hidden.bs.modal', function () {
        $('#deleteRepairButton').prop('disabled', false).find('.button-text').text('Delete');
        $('#deleteRepairButton').find('.spinner-border').addClass('d-none');
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
    });
});

@if(session('success'))
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
@endif
</script>
@endpush