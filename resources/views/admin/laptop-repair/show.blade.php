@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Repair Details #{{ $repair->customer_number }}</h4>
        <a href="{{ route('admin.laptop-repair.index') }}" class="btn btn-outline-success">
            <i class="fas fa-arrow-left me-2"></i> Back to List
        </a>
    </div>

    <div class="card mb-4 shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-md-6">
                    <h5 class="text-primary mb-3"><i class="fas fa-user me-2"></i>Customer Info</h5>
                    <ul class="list-unstyled text-dark">
                        <li><strong>Name:</strong> {{ $repair->customer_name }}</li>
                        <li><strong>Contact:</strong> {{ $repair->contact }}</li>
                        <li><strong>Note Number:</strong> {{ $repair->note_number }}</li>
                    </ul>
                </div>

                <div class="col-md-6">
                    <h5 class="text-primary mb-3"><i class="fas fa-laptop me-2"></i>Device Info</h5>
                    <ul class="list-unstyled text-dark">
                        <li><strong>Device:</strong> {{ $repair->device }}</li>
                        <li><strong>Serial No:</strong> {{ $repair->serial_number }}</li>
                        <li><strong>Fault:</strong> {{ $repair->fault }}</li>
                    </ul>
                </div>
            </div>

            <hr class="my-4">

            <div class="row g-4">
                <div class="col-md-4">
                    <p class="mb-1 fw-bold text-dark">Repair Price</p>
                    <span class="text-success fs-5">Rs. {{ number_format($repair->repair_price, 2) }}</span>
                </div>
                <div class="col-md-4">
                    <p class="mb-1 fw-bold text-dark">Status</p>
                    <span class="badge 
                        @if($repair->status == 'completed') bg-success
                        @elseif($repair->status == 'in_progress') bg-warning text-dark
                        @elseif($repair->status == 'pending') bg-info text-dark
                        @elseif($repair->status == 'cancelled') bg-danger
                        @else bg-secondary
                        @endif py-2 px-3">
                        {{ ucfirst(str_replace('_', ' ', $repair->status)) }}
                    </span>
                </div>
                <div class="col-md-4">
                    <p class="mb-1 fw-bold text-dark">Date</p>
                    <span class="text-dark">{{ $repair->date->format('M d, Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    @if(is_array($repair->images) && count($repair->images) > 0)
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body p-4">
            <h5 class="text-primary mb-3"><i class="fas fa-image me-2"></i>Device Images</h5>
            <div class="row g-3">
                @foreach($repair->images as $img)
                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <a href="#" class="image-popup" data-image="{{ asset('storage/' . $img) }}">
                        <img src="{{ asset('storage/' . $img) }}" 
                             class="img-thumbnail w-100" 
                             style="object-fit: cover; height: 120px; cursor: pointer;">
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @else
    <div class="alert alert-info rounded-3 shadow-sm">No images available for this repair.</div>
    @endif
</div>

<!-- Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-3 overflow-hidden">
            <div class="modal-header bg-light">
                <h5 class="modal-title">Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body bg-light d-flex justify-content-center p-0">
                <img id="modalImage" src="" class="img-fluid" style="max-height: 70vh;">
            </div>
            <div class="modal-footer bg-light">
                <button class="btn btn-danger" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
        document.querySelectorAll('.image-popup').forEach(function (element) {
            element.addEventListener('click', function (e) {
                e.preventDefault();
                const imgUrl = this.getAttribute('data-image');
                document.getElementById('modalImage').src = imgUrl;
                imageModal.show();
            });
        });
    });
</script>
@endpush

@push('styles')
<style>
    .card-title {
        font-weight: 600;
    }
    .img-thumbnail {
        border-radius: 10px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .img-thumbnail:hover {
        transform: scale(1.05);
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
    }
</style>
@endpush
