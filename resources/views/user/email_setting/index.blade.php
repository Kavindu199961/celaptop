@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Email Settings</div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('user.email-settings.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group row">
                            <label for="smtp_host" class="col-md-4 col-form-label text-md-right">SMTP Host</label>
                            <div class="col-md-6">
                                <input id="smtp_host" type="text" class="form-control @error('smtp_host') is-invalid @enderror" 
                                       name="smtp_host" value="{{ old('smtp_host', auth()->user()->smtp_host) }}" required>
                                @error('smtp_host')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="smtp_port" class="col-md-4 col-form-label text-md-right">SMTP Port</label>
                            <div class="col-md-6">
                                <input id="smtp_port" type="number" class="form-control @error('smtp_port') is-invalid @enderror" 
                                       name="smtp_port" value="{{ old('smtp_port', auth()->user()->smtp_port) }}" required>
                                @error('smtp_port')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="smtp_encryption" class="col-md-4 col-form-label text-md-right">Encryption</label>
                            <div class="col-md-6">
                                <select id="smtp_encryption" class="form-control @error('smtp_encryption') is-invalid @enderror" 
                                        name="smtp_encryption" required>
                                    <option value="tls" {{ old('smtp_encryption', auth()->user()->smtp_encryption) == 'tls' ? 'selected' : '' }}>TLS</option>
                                    <option value="ssl" {{ old('smtp_encryption', auth()->user()->smtp_encryption) == 'ssl' ? 'selected' : '' }}>SSL</option>
                                </select>
                                @error('smtp_encryption')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email_username" class="col-md-4 col-form-label text-md-right">SMTP Username</label>
                            <div class="col-md-6">
                                <input id="email_username" type="text" class="form-control @error('email_username') is-invalid @enderror" 
                                       name="email_username" value="{{ old('email_username', auth()->user()->email_username) }}" required>
                                @error('email_username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email_password" class="col-md-4 col-form-label text-md-right">SMTP Password</label>
                            <div class="col-md-6">
                                <input id="email_password" type="password" class="form-control @error('email_password') is-invalid @enderror" 
                                       name="email_password" placeholder="Leave blank to keep current password">
                                @error('email_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Save Settings
                                </button>
                                <a href="" class="btn btn-secondary">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection