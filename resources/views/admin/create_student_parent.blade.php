@extends('layouts.app')

@section('title', 'Create Student & Parent - Admin Panel')

@section('styles')
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #4e73df;
            color: white;
            border-radius: 10px 10px 0 0 !important;
        }
        .btn-primary {
            background-color: #4e73df;
            border-color: #4e73df;
        }
        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2e59d9;
        }
        .required-field::after {
            content: "*";
            color: red;
            margin-left: 4px;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h5 class="m-0 font-weight-bold"><i class="fas fa-user-graduate me-2"></i>Create Student & Parent</h5>
                    <a href="{{ route('admin.userManagement') }}" class="btn btn-sm btn-light">
                        <i class="fas fa-arrow-left me-1"></i> Back 
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i> Please fix the following errors:
                            <ul class="mb-0 mt-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form id="createStudentParentForm" action="{{ route('admin.create_student_parent') }}" method="POST">
                        @csrf
                        
                        <!-- Parent Information Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3"><i class="fas fa-user me-2"></i>Parent Information</h5>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="parent_name" class="form-label required-field">Parent Name</label>
                                <input type="text" class="form-control" id="parent_name" name="parent_name" 
                                       value="{{ old('parent_name') }}" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="parent_email" class="form-label required-field">Email Address</label>
                                <input type="email" class="form-control" id="parent_email" name="parent_email" 
                                       value="{{ old('parent_email') }}" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="parent_password" class="form-label required-field">Password</label>
                                <input type="password" class="form-control" id="parent_password" name="parent_password" required>
                                <div class="form-text">Minimum 8 characters</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="parent_password_confirmation" class="form-label required-field">Confirm Password</label>
                                <input type="password" class="form-control" id="parent_password_confirmation" 
                                       name="parent_password_confirmation" required>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <!-- Student Information Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3"><i class="fas fa-user-graduate me-2"></i>Student Information</h5>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="student_name" class="form-label required-field">Student Name</label>
                                <input type="text" class="form-control" id="student_name" name="student_name" 
                                       value="{{ old('student_name') }}" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="date_of_birth" class="form-label required-field">Date of Birth</label>
                                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" 
                                       value="{{ old('date_of_birth') }}" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="student_email" class="form-label">Student Email (Optional)</label>
                                <input type="email" class="form-control" id="student_email" name="student_email" 
                                       value="{{ old('student_email') }}">
                                <div class="form-text">If provided, will create a separate login for the student</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="student_password" class="form-label">Student Password</label>
                                <input type="password" class="form-control" id="student_password" name="student_password">
                                <div class="form-text">Required if email is provided</div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-save me-2"></i>Create Student & Parent
                                </button>
                                <button type="reset" class="btn btn-outline-secondary ms-2">
                                    <i class="fas fa-undo me-2"></i>Reset Form
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form validation
            const form = document.getElementById('createStudentParentForm');
            
            form.addEventListener('submit', function(event) {
                let isValid = true;
                
                // Validate parent fields
                const parentName = document.getElementById('parent_name');
                const parentEmail = document.getElementById('parent_email');
                const parentPassword = document.getElementById('parent_password');
                const parentPasswordConfirmation = document.getElementById('parent_password_confirmation');
                
                if (!parentName.value.trim()) {
                    isValid = false;
                    highlightError(parentName);
                } else {
                    removeHighlight(parentName);
                }
                
                if (!parentEmail.value.trim()) {
                    isValid = false;
                    highlightError(parentEmail);
                } else {
                    removeHighlight(parentEmail);
                }
                
                if (!parentPassword.value || parentPassword.value.length < 8) {
                    isValid = false;
                    highlightError(parentPassword);
                } else {
                    removeHighlight(parentPassword);
                }
                
                if (parentPassword.value !== parentPasswordConfirmation.value) {
                    isValid = false;
                    highlightError(parentPasswordConfirmation);
                    alert('Password confirmation does not match');
                } else {
                    removeHighlight(parentPasswordConfirmation);
                }
                
                // Validate student fields
                const studentName = document.getElementById('student_name');
                const dateOfBirth = document.getElementById('date_of_birth');
                const studentEmail = document.getElementById('student_email');
                const studentPassword = document.getElementById('student_password');
                
                if (!studentName.value.trim()) {
                    isValid = false;
                    highlightError(studentName);
                } else {
                    removeHighlight(studentName);
                }
                
                if (!dateOfBirth.value) {
                    isValid = false;
                    highlightError(dateOfBirth);
                } else {
                    removeHighlight(dateOfBirth);
                }
                
                // If student email is provided, password is required
                if (studentEmail.value && !studentPassword.value) {
                    isValid = false;
                    highlightError(studentPassword);
                    alert('Student password is required when email is provided');
                } else if (studentEmail.value && studentPassword.value.length < 8) {
                    isValid = false;
                    highlightError(studentPassword);
                    alert('Student password must be at least 8 characters');
                } else {
                    removeHighlight(studentPassword);
                }
                
                if (!isValid) {
                    event.preventDefault();
                    alert('Please fill all required fields correctly');
                }
            });
            
            function highlightError(element) {
                element.classList.add('is-invalid');
            }
            
            function removeHighlight(element) {
                element.classList.remove('is-invalid');
            }
        });
    </script>
@endsection