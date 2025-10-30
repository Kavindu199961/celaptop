@extends('layouts.app')

@section('content')
@php
    // Decode images JSON string to array
    $images = $repairItem->images ? json_decode($repairItem->images, true) : [];
@endphp

<div class="col-12 col-md-12 col-lg-10 mx-auto">
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
            <h4>Repair Item Details</h4>
            <div>
                <a class="btn btn-primary" href="{{ route('user.shop_names.index')}}">
                    <i class="fas fa-arrow-left"></i> Back to Shops
                </a>
            </div>
        </div>

        <div class="card-body">
            <!-- Basic Information Section -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="info-card">
                        <h5 class="info-card-title">
                            <i class="fas fa-tag text-primary"></i>
                            Basic Information
                        </h5>
                        <div class="info-content">
                            <div class="info-item">
                                <span class="info-label">Item Number:</span>
                                <span class="info-value">{{ $repairItem->item_number }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Item Name:</span>
                                <span class="info-value">{{ $repairItem->item_name }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Serial Number:</span>
                                <span class="info-value">{{ $repairItem->serial_number ?? 'N/A' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Date:</span>
                                <span class="info-value">{{ $repairItem->date->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="info-card">
                        <h5 class="info-card-title">
                            <i class="fas fa-info-circle text-info"></i>
                            Status & Pricing
                        </h5>
                        <div class="info-content">
                            <div class="info-item">
                                <span class="info-label">Status:</span>
                                <span class="info-value">
                                    <span class="badge 
                                        @if($repairItem->status == 'pending') bg-secondary
                                        @elseif($repairItem->status == 'in_progress') bg-primary
                                        @elseif($repairItem->status == 'completed') bg-success
                                        @elseif($repairItem->status == 'canceled') bg-danger
                                        @else bg-secondary @endif">
                                        {{ ucfirst(str_replace('_', ' ', $repairItem->status)) }}
                                    </span>
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Price:</span>
                                <span class="info-value">
                                    @if($repairItem->price)
                                        LKR {{ number_format($repairItem->price, 2) }}
                                    @else
                                        N/A
                                    @endif
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Shop:</span>
                                <span class="info-value">{{ $repairItem->shop->name }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Created:</span>
                                <span class="info-value">{{ $repairItem->created_at->format('M d, Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hardware Specifications Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="info-card">
                        <h5 class="info-card-title">
                            <i class="fas fa-microchip text-warning"></i>
                            Hardware Specifications
                        </h5>
                        <div class="info-content">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="info-item">
                                        <span class="info-label">RAM:</span>
                                        <span class="info-value">{{ $repairItem->ram ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="spec-badges">
                                        @if($repairItem->hdd)
                                            <span class="spec-badge bg-secondary">
                                                <i class="fas fa-hdd"></i> HDD: {{ $repairItem->hdd }}
                                            </span>
                                        @endif
                                        @if($repairItem->ssd)
                                            <span class="spec-badge bg-primary">
                                                <i class="fas fa-save"></i> SSD: {{ $repairItem->ssd }}
                                            </span>
                                        @endif
                                        @if($repairItem->nvme)
                                            <span class="spec-badge bg-success">
                                                <i class="fas fa-bolt"></i> NVMe: {{ $repairItem->nvme }}
                                            </span>
                                        @endif
                                        @if($repairItem->battery)
                                            <span class="spec-badge bg-warning text-dark">
                                                <i class="fas fa-battery-half"></i> Battery
                                            </span>
                                        @endif
                                        @if($repairItem->dvd_rom)
                                            <span class="spec-badge bg-dark">
                                                <i class="fas fa-compact-disc"></i> DVD ROM
                                            </span>
                                        @endif
                                        @if($repairItem->keyboard)
                                            <span class="spec-badge bg-danger">
                                                <i class="fas fa-keyboard"></i> Keyboard
                                            </span>
                                        @endif
                                        @if(!$repairItem->hdd && !$repairItem->ssd && !$repairItem->nvme && !$repairItem->battery && !$repairItem->dvd_rom && !$repairItem->keyboard)
                                            <span class="text-muted">No hardware specifications added</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description Section -->
            @if($repairItem->description)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="info-card">
                        <h5 class="info-card-title">
                            <i class="fas fa-file-alt text-success"></i>
                            Description
                        </h5>
                        <div class="info-content">
                            <p class="description-text">{{ $repairItem->description }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Images Section -->
            <div class="row">
                <div class="col-12">
                    <div class="info-card">
                        <h5 class="info-card-title">
                            <i class="fas fa-images text-info"></i>
                            Images
                            @if(count($images) > 0)
                                <span class="badge bg-primary ms-2">{{ count($images) }}</span>
                            @endif
                        </h5>
                        <div class="info-content">
                            @if(count($images) > 0)
                                <div class="row" id="images-container">
                                    @foreach($images as $index => $image)
                                        <div class="col-md-3 col-sm-4 col-6 mb-3">
                                            <div class="image-card">
                                                <img src="{{ Storage::url($image) }}" 
                                                     alt="Repair Image {{ $index + 1 }}"
                                                     class="img-fluid rounded image-thumbnail"
                                                     data-image-src="{{ Storage::url($image) }}">
                                                <div class="image-actions mt-2">
                                                    <button class="btn btn-sm btn-outline-primary view-image-btn" 
                                                            data-image-src="{{ Storage::url($image) }}">
                                                        <i class="fas fa-expand"></i> View
                                                    </button>
                                                    <a href="{{ Storage::url($image) }}" 
                                                       class="btn btn-sm btn-outline-success" 
                                                       download="repair_image_{{ $index + 1 }}_{{ $repairItem->item_number }}.jpg">
                                                        <i class="fas fa-download"></i> Download
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-images fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No images uploaded for this repair item</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="d-flex justify-content-end gap-2">
                        <!-- Add any action buttons here if needed -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Repair Item Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Repair Image" class="img-fluid rounded">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="#" id="downloadImage" class="btn btn-primary" download>
                    <i class="fas fa-download"></i> Download
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .info-card {
        background: #fff;
        border: 1px solid #e3e6f0;
        border-radius: 0.5rem;
        padding: 1.5rem;
        height: 100%;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }

    .info-card-title {
        color: #4e73df;
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #eaecf4;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.75rem;
        padding: 0.5rem 0;
        border-bottom: 1px solid #f8f9fc;
    }

    .info-label {
        font-weight: 600;
        color: #5a5c69;
        min-width: 140px;
    }

    .info-value {
        color: #858796;
        flex: 1;
        text-align: right;
    }

    .spec-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .spec-badge {
        padding: 0.5rem 1rem;
        border-radius: 0.35rem;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .description-text {
        color: #858796;
        line-height: 1.6;
        margin: 0;
    }

    .image-card {
        text-align: center;
        padding: 1rem;
        border: 1px solid #e3e6f0;
        border-radius: 0.5rem;
        background: #f8f9fc;
        transition: all 0.3s ease;
    }

    .image-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .image-thumbnail {
        width: 100%;
        height: 150px;
        object-fit: cover;
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .image-thumbnail:hover {
        transform: scale(1.05);
    }

    .image-actions {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }

    #modalImage {
        max-height: 70vh;
        width: auto;
        max-width: 100%;
    }

    @media (max-width: 768px) {
        .info-item {
            flex-direction: column;
        }
        
        .info-label {
            min-width: auto;
            margin-bottom: 0.25rem;
        }

        .info-value {
            text-align: left;
        }
        
        .image-actions {
            flex-direction: column;
        }
        
        .image-actions .btn {
            width: 100%;
        }
    }

    /* Print Styles */
    @media print {
        .btn, .image-actions, .modal {
            display: none !important;
        }
        
        .info-card {
            box-shadow: none;
            border: 1px solid #000;
        }
    }
</style>

<script>
    function openImageModal(imageSrc) {
        const modalImage = document.getElementById('modalImage');
        const downloadImage = document.getElementById('downloadImage');
        
        // Set image source
        modalImage.src = imageSrc;
        
        // Set download link
        downloadImage.href = imageSrc;
        downloadImage.download = imageSrc.split('/').pop() || 'repair_image.jpg';
        
        // Initialize Bootstrap modal
        const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
        imageModal.show();
    }

    // Initialize image gallery with click functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Add click event to image thumbnails
        const imageThumbnails = document.querySelectorAll('.image-thumbnail');
        imageThumbnails.forEach((img) => {
            img.addEventListener('click', function() {
                const imageSrc = this.getAttribute('data-image-src') || this.src;
                openImageModal(imageSrc);
            });
        });

        // Add click event to view buttons
        const viewButtons = document.querySelectorAll('.view-image-btn');
        viewButtons.forEach((button) => {
            button.addEventListener('click', function() {
                const imageSrc = this.getAttribute('data-image-src');
                openImageModal(imageSrc);
            });
        });
    });

    function confirmDelete() {
        return confirm('Are you sure you want to delete this repair item? This action cannot be undone.');
    }
</script>
@endsection