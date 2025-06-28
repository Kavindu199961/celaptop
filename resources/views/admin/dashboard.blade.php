@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container">
    <h1>Admin Dashboard</h1>
    <p>Welcome to the Celaptop Admin Panel</p>
    
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Admin Information</h5>
            <p class="card-text">You are logged in as: {{ auth()->user()->email }}</p>
        </div>
    </div>
</div>
@endsection