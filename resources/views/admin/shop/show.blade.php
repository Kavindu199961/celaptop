@extends('layouts.app')

@section('content')
<div class="col-12 col-md-12 col-lg-12">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Shop Details: {{ $shop->shop_name }}</h4>
            <div>
                <!-- <a href="{{ route('admin.shop.edit', $shop->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a> -->
                <a href="{{ route('admin.shop.index') }}" class="btn btn-info">
                    <i class="fas fa-arrow-left"></i> Back to Shops
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Shop Information</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Shop Name</th>
                            <td>{{ $shop->shop_name }}</td>
                        </tr>
                        <tr>
                            <th>Phone Number</th>
                            <td>{{ $shop->phone_number }}</td>
                        </tr>
                        <tr>
                            <th>Total Items</th>
                            <td>{{ $shop->items->count() }}</td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $shop->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Last Updated</th>
                            <td>{{ $shop->updated_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <h5 class="mb-3">Shop Items</h5>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>Item Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Serial Number</th>
                            <th>Warranty</th>
                            <th>Date Added</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shop->items as $item)
                        <tr>
                            <td>{{ $item->item_name }}</td>
                            <td>{{ $item->description ?? 'N/A' }}</td>
                            <td>{{ number_format($item->price, 2) }}</td>
                            <td>{{ $item->serial_number ?? 'N/A' }}</td>
                            <td>{{ $item->warranty ?? 'N/A' }}</td>
                            <td>{{ $item->date ? \Carbon\Carbon::parse($item->date)->format('Y-m-d') : 'N/A' }}</td>
                            
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No items found for this shop</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- View Item Modal -->
<div class="modal fade" id="viewItemModal" tabindex="-1" role="dialog" aria-labelledby="viewItemModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewItemModalLabel">Item Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="itemDetailsContent">
                <!-- Content will be loaded via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function () {
        // Handle View Item button click
        $(document).on('click', '.view-item', function() {
            var itemId = $(this).data('id');
            
            $.get("{{ url('shop/item') }}/" + itemId, function(data) {
                var html = `
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Item Name:</strong> ${data.item_name}</p>
                            <p><strong>Price:</strong> ${parseFloat(data.price).toFixed(2)}</p>
                            <p><strong>Date Added:</strong> ${data.date ? data.date.split('T')[0] : 'N/A'}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Serial Number:</strong> ${data.serial_number || 'N/A'}</p>
                            <p><strong>Warranty:</strong> ${data.warranty || 'N/A'}</p>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <p><strong>Description:</strong></p>
                            <p>${data.description || 'No description available'}</p>
                        </div>
                    </div>
                `;
                
                $('#itemDetailsContent').html(html);
                $('#viewItemModal').modal('show');
            }).fail(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load item details',
                    confirmButtonColor: '#0d6efd'
                });
            });
        });
    });
</script>
@endpush
@endsection