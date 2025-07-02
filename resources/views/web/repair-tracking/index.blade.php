@extends('layouts.app')

@section('content')
<section id="tracking" class="py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h2>Track Your Repair</h2>
                <p class="lead">Enter your repair tracking number or note number</p>
                
                <form method="GET" action="{{ route('web.repair-tracking.index') }}" class="tracking-form">
                    @csrf
                    <div class="mb-3">
                        <label for="tracking_number" class="form-label">Tracking/Note Number</label>
                        <input type="text" class="form-control" id="tracking_number" name="tracking_number" 
                               placeholder="e.g. CE-0001 or 425" required value="{{ old('tracking_number', $request->tracking_number ?? '') }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Track Repair</button>
                </form>
            </div>
            
            <div class="col-lg-6">
                @if($request->has('tracking_number') || $repair)
                    @if($repair)
                        <h3>Repair Status</h3>
                        <div class="repair-status status-{{ $status }}">
                            <h4>
                                <i class="fas fa-ticket-alt me-2"></i>
                                @if(str_contains($repair->customer_number, 'CE-'))
                                    Ticket #{{ $repair->customer_number }}
                                @else
                                    Note #{{ $repair->note_number }}
                                @endif
                            </h4>
                            <p class="mb-0"><strong>Customer:</strong> {{ $repair->customer_name }}</p>
                            <p class="mb-0"><strong>Contact:</strong> {{ $repair->contact }}</p>
                            <p class="mb-0"><strong>Device:</strong> {{ $repair->device }}</p>
                            <p class="mb-0"><strong>Serial:</strong> {{ $repair->serial_number }}</p>
                            <p class="mb-0"><strong>Fault:</strong> {{ $repair->fault }}</p>
                            @if($repair->repair_price)
                                <p class="mb-0"><strong>Price:</strong> Rs. {{ number_format($repair->repair_price, 2) }}</p>
                            @endif
                        </div>
                        
                        <h4 class="mt-4">Repair Progress</h4>
                        <div class="repair-progress">
                            @foreach($steps as $step)
                                <div class="progress-step {{ $step['completed'] ? 'completed' : '' }} {{ $step['active'] ?? false ? 'active' : '' }}">
                                    <div class="step-icon">
                                        @if($step['completed'] ?? false)
                                            <i class="fas fa-check-circle"></i>
                                        @elseif($step['active'] ?? false)
                                            <i class="fas fa-spinner fa-pulse"></i>
                                        @else
                                            <i class="far fa-circle"></i>
                                        @endif
                                    </div>
                                    <div class="step-content">
                                        <h5>{{ $step['label'] }}</h5>
                                        <p>{{ $step['description'] }}</p>
                                        @if($step['active'] ?? false && $status == 'ongoing' && $repair->date)
                                            <div class="estimated-time">
                                                <small>Received on: {{ $repair->date->format('M j, Y') }}</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @if($status == 'completed')
                            <div class="alert alert-success mt-3">
                                <i class="fas fa-check-circle me-2"></i>
                                This repair was completed successfully.
                            </div>
                        @endif
                    @else
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            No repair found with the provided tracking number. Please check your number and try again.
                        </div>
                    @endif
                @else
                    <div id="trackingInfo">
                        <h3>How Tracking Works</h3>
                        <p>When you bring your device in for repair, we provide you with either:</p>
                        <ul>
                            <li>A customer number (CE-0001 format)</li>
                            <li>Or a note number (numeric)</li>
                        </ul>
                        <p>You can use either number to track your repair status.</p>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Can't find your number? Contact us with your phone number used during drop-off.
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .repair-status {
        padding: 20px;
        border-radius: 5px;
        margin-bottom: 20px;
    }
    
    .status-ongoing {
        background-color: #cce5ff;
        color: #004085;
        border-left: 5px solid #004085;
    }
    
    .status-completed {
        background-color: #d4edda;
        color: #155724;
        border-left: 5px solid #155724;
    }
    
    .repair-progress {
        position: relative;
        padding-left: 30px;
    }
    
    .progress-step {
        position: relative;
        padding-bottom: 20px;
    }
    
    .progress-step:not(:last-child):before {
        content: '';
        position: absolute;
        left: 15px;
        top: 30px;
        height: calc(100% - 30px);
        width: 2px;
        background: #dee2e6;
    }
    
    .progress-step.completed:not(:last-child):before {
        background: #28a745;
    }
    
    .step-icon {
        position: absolute;
        left: -30px;
        top: 0;
        width: 30px;
        height: 30px;
        text-align: center;
        line-height: 30px;
        color: #6c757d;
    }
    
    .progress-step.completed .step-icon {
        color: #28a745;
    }
    
    .progress-step.active .step-icon {
        color: #007bff;
    }
    
    .step-content {
        padding: 5px 0 15px 15px;
    }
    
    .estimated-time {
        font-style: italic;
        color: #6c757d;
    }
</style>
@endpush