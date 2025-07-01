@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Laptop Repair Tracking</h4>
                        <a href="" class="btn btn-light btn-sm">
                            <i class="fas fa-plus"></i> New Repair
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Search Form -->
                    <div class="search-section mb-5 p-4 bg-light rounded">
                        <h5 class="mb-4">Track Your Repair Status</h5>
                        <form method="GET" action="{{ route('web.repair-tracking.index') }}">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <input type="text" name="tracking_number" 
                                            class="form-control form-control-lg"
                                            placeholder="Enter your repair ticket number"
                                            value="{{ request('tracking_number') }}"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary btn-lg w-100">
                                        <i class="fas fa-truck"></i> Track Now
                                    </button>
                                </div>
                            </div>
                            @error('tracking_number')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </form>
                    </div>

                    <!-- Results Section -->
                    @if(isset($repair))
                        <div class="results-section">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> Showing results for repair ticket: 
                                <strong>{{ $repair->customer_number }}</strong>
                            </div>

                            <!-- Status Banner -->
                            <div class="status-banner mb-4 text-center py-3 rounded">
                                @if($repair->status == 'pending')
                                    <div class="status-pending">
                                        <i class="fas fa-clock fa-2x fa-beat-fade"></i>
                                        <h3 class="mt-2">Repair Pending</h3>
                                        <p class="mb-0">Your repair request has been received and is awaiting processing</p>
                                    </div>
                                @elseif($repair->status == 'in_progress')
                                    <div class="status-in-progress">
                                        <i class="fas fa-tools fa-2x fa-bounce"></i>
                                        <h3 class="mt-2">Repair In Progress</h3>
                                        <p class="mb-0">Our technicians are currently working on your device</p>
                                    </div>
                                @elseif($repair->status == 'completed')
                                    <div class="status-completed">
                                        <i class="fas fa-check-circle fa-2x"></i>
                                        <h3 class="mt-2">Repair Completed!</h3>
                                        <p class="mb-0">Your device is ready for pickup/delivery</p>
                                    </div>
                                @elseif($repair->status == 'cancelled')
                                    <div class="status-cancelled">
                                        <i class="fas fa-times-circle fa-2x"></i>
                                        <h3 class="mt-2">Repair Cancelled</h3>
                                        <p class="mb-0">This repair request has been cancelled</p>
                                    </div>
                                @endif
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="card mb-4">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">Customer Details</h6>
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-unstyled">
                                                <li class="mb-2">
                                                    <strong>Name:</strong> {{ $repair->customer_name }}
                                                </li>
                                                <li class="mb-2">
                                                    <strong>Contact:</strong> {{ $repair->contact }}
                                                </li>
                                                <li class="mb-2">
                                                    <strong>Repair Number:</strong> 
                                                    <span class="badge bg-primary">{{ $repair->customer_number }}</span>
                                                </li>
                                                <li class="mb-2">
                                                    <strong>Date Received:</strong> 
                                                    {{ $repair->date->format('M d, Y') }}
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card mb-4">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">Device Details</h6>
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-unstyled">
                                                <li class="mb-2">
                                                    <strong>Device:</strong> {{ $repair->device }}
                                                </li>
                                                <li class="mb-2">
                                                    <strong>Serial Number:</strong> {{ $repair->serial_number }}
                                                </li>
                                                <li class="mb-2">
                                                    <strong>Fault Description:</strong> {{ $repair->fault }}
                                                </li>
                                                <li class="mb-2">
                                                    <strong>Repair Price:</strong> 
                                                    ${{ number_format($repair->repair_price, 2) }}
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Timeline -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Repair Progress</h6>
                                </div>
                                <div class="card-body">
                                    <div class="timeline">
                                        <div class="timeline-step {{ $repair->status != 'cancelled' ? ($repair->status == 'pending' ? 'active' : 'completed') : '' }}">
                                            <div class="timeline-icon">
                                                @if($repair->status == 'pending')
                                                    <i class="fas fa-clock fa-spin"></i>
                                                @else
                                                    <i class="fas fa-check"></i>
                                                @endif
                                            </div>
                                            <div class="timeline-content">
                                                <h6>Repair Request Received</h6>
                                                <p class="mb-0 text-muted">We've received your repair request</p>
                                                @if($repair->status == 'pending')
                                                    <small class="text-info">
                                                        <i class="fas fa-info-circle"></i> Current Status
                                                    </small>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="timeline-step {{ in_array($repair->status, ['in_progress', 'completed']) ? ($repair->status == 'in_progress' ? 'active' : 'completed') : '' }}">
                                            <div class="timeline-icon">
                                                @if($repair->status == 'in_progress')
                                                    <i class="fas fa-tools fa-bounce"></i>
                                                @elseif(in_array($repair->status, ['completed']))
                                                    <i class="fas fa-check"></i>
                                                @endif
                                            </div>
                                            <div class="timeline-content">
                                                <h6>Repair In Progress</h6>
                                                <p class="mb-0 text-muted">Technicians are working on your device</p>
                                                @if($repair->status == 'in_progress')
                                                    <small class="text-info">
                                                        <i class="fas fa-info-circle"></i> Current Status
                                                    </small>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="timeline-step {{ $repair->status == 'completed' ? 'active' : '' }}">
                                            <div class="timeline-icon">
                                                @if($repair->status == 'completed')
                                                    <i class="fas fa-check-circle"></i>
                                                @endif
                                            </div>
                                            <div class="timeline-content">
                                                <h6>Repair Completed</h6>
                                                <p class="mb-0 text-muted">Your device is ready for pickup</p>
                                                @if($repair->status == 'completed')
                                                    <small class="text-info">
                                                        <i class="fas fa-info-circle"></i> Current Status
                                                    </small>
                                                @endif
                                            </div>
                                        </div>

                                        @if($repair->status == 'cancelled')
                                            <div class="timeline-step cancelled">
                                                <div class="timeline-icon">
                                                    <i class="fas fa-times"></i>
                                                </div>
                                                <div class="timeline-content">
                                                    <h6>Repair Cancelled</h6>
                                                    <p class="mb-0 text-muted">This repair was cancelled</p>
                                                    <small class="text-danger">
                                                        <i class="fas fa-info-circle"></i> Final Status
                                                    </small>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if($repair->status == 'completed')
                                <div class="alert alert-success">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle fa-2x me-3"></i>
                                        <div>
                                            <h5 class="mb-1">Repair Completed!</h5>
                                            <p class="mb-0">Your device was successfully repaired on 
                                                {{ $repair->completed_at->format('M d, Y h:i A') }}.
                                            </p>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="text-center mt-3">
                                        <button class="btn btn-success me-2">
                                            <i class="fas fa-print"></i> Print Receipt
                                        </button>
                                        <button class="btn btn-primary">
                                            <i class="fas fa-star"></i> Rate Our Service
                                        </button>
                                    </div>
                                </div>
                            @elseif($repair->status == 'cancelled')
                                <div class="alert alert-danger">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-times-circle fa-2x me-3"></i>
                                        <div>
                                            <h5 class="mb-1">Repair Cancelled</h5>
                                            <p class="mb-0">This repair request was cancelled on 
                                                {{ $repair->updated_at->format('M d, Y h:i A') }}.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @elseif(request()->has('tracking_number'))
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> 
                            No repair found with the provided tracking number. Please check and try again.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .search-section {
        border-left: 4px solid #0d6efd;
    }
    
    .status-banner {
        background: rgba(13, 110, 253, 0.1);
        border-left: 4px solid #0d6efd;
    }
    
    .status-pending {
        color: #ffc107;
    }
    
    .status-in-progress {
        color: #0d6efd;
    }
    
    .status-completed {
        color: #198754;
    }
    
    .status-cancelled {
        color: #dc3545;
    }
    
    .timeline {
        position: relative;
        padding-left: 50px;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        top: 0;
        left: 25px;
        height: 100%;
        width: 2px;
        background: #e9ecef;
    }
    
    .timeline-step {
        position: relative;
        padding-bottom: 25px;
    }
    
    .timeline-icon {
        position: absolute;
        left: -40px;
        top: 0;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        border: 2px solid #e9ecef;
        color: #6c757d;
    }
    
    .timeline-content {
        padding: 10px 15px;
        background: #f8f9fa;
        border-radius: 6px;
        transition: all 0.3s ease;
    }
    
    .timeline-step.completed .timeline-icon {
        border-color: #28a745;
        background: #28a745;
        color: white;
    }
    
    .timeline-step.active .timeline-icon {
        border-color: #0d6efd;
        background: #0d6efd;
        color: white;
        animation: pulse 2s infinite;
    }
    
    .timeline-step.cancelled .timeline-icon {
        border-color: #dc3545;
        background: #dc3545;
        color: white;
    }
    
    .timeline-step.completed .timeline-content {
        border-left: 3px solid #28a745;
    }
    
    .timeline-step.active .timeline-content {
        border-left: 3px solid #0d6efd;
        transform: translateX(5px);
    }
    
    .timeline-step.cancelled .timeline-content {
        border-left: 3px solid #dc3545;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }
    
    .fa-beat-fade {
        animation: beat-fade 2s ease infinite;
    }
    
    @keyframes beat-fade {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.2); opacity: 0.7; }
        100% { transform: scale(1); opacity: 1; }
    }
</style>
@endsection 