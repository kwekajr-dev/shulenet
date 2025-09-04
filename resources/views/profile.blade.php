@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-user me-2"></i>User Profile</h4>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Full Name</label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                                       name="name" value="{{ old('name', $user->name) }}" required autocomplete="name" autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email', $user->email) }}" required autocomplete="email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">User Type</label>
                                <input type="text" class="form-control" value="{{ ucfirst($user->type) }}" disabled>
                                <small class="text-muted">User type cannot be changed</small>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Account Created</label>
                                <input type="text" class="form-control" value="{{ $user->created_at->format('M d, Y') }}" disabled>
                            </div>
                        </div>

                        <hr>

                        <h5 class="mb-3">Change Password</h5>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input id="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                       name="current_password" autocomplete="current-password">
                                @error('current_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="new_password" class="form-label">New Password</label>
                                <input id="new_password" type="password" class="form-control @error('new_password') is-invalid @enderror" 
                                       name="new_password" autocomplete="new-password">
                                @error('new_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                                <input id="new_password_confirmation" type="password" class="form-control" 
                                       name="new_password_confirmation" autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Update Profile
                                </button>
                                
                                <a href="{{ url()->previous() }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>Back
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Additional Information Card -->
            <div class="card shadow mt-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Account Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>User ID:</strong> {{ $user->id }}<br>
                            <strong>Email Verified:</strong> 
                            @if($user->email_verified_at)
                                <span class="badge bg-success">Yes ({{ $user->email_verified_at->format('M d, Y') }})</span>
                            @else
                                <span class="badge bg-warning text-dark">Not Verified</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <strong>Last Updated:</strong> {{ $user->updated_at->format('M d, Y H:i') }}<br>
                            <strong>Status:</strong> 
                            <span class="badge bg-success">Active</span>
                        </div>
                    </div>
                    
                    @if($user->isTeacher() && $user->teacherRole)
                        <hr>
                        <h6>Teacher Information</h6>
                        <strong>Role:</strong> {{ $user->teacherRole->name }}<br>
                        <strong>Permissions:</strong> 
                        @if($user->teacherRole->permissions->count() > 0)
                            @foreach($user->teacherRole->permissions as $permission)
                                <span class="badge bg-primary me-1">{{ $permission->permission }}</span>
                            @endforeach
                        @else
                            <span class="text-muted">No special permissions</span>
                        @endif
                    @endif
                    
                    @if($user->isParent() && $user->students->count() > 0)
                        <hr>
                        <h6>Parent Information</h6>
                        <strong>Children:</strong> {{ $user->students->count() }}<br>
                        <strong>Children Names:</strong> 
                        @foreach($user->students as $student)
                            <span class="badge bg-info me-1">{{ $student->name }}</span>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border: none;
        border-radius: 10px;
    }
    
    .card-header {
        border-radius: 10px 10px 0 0 !important;
    }
    
    .form-label {
        font-weight: 500;
        color: #495057;
    }
    
    .btn {
        border-radius: 6px;
        padding: 8px 20px;
    }
</style>
@endsection