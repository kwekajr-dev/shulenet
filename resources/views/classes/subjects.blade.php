@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Subjects for Class: {{ $class->name }}</h5>
                    <a href="{{ route('classes.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back to Classes
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0">Add New Subject</h6>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('classes.subjects.store', $class->id) }}" method="POST">
                                        @csrf
                                        
                                        <div class="mb-3">
                                            <label for="subject_name" class="form-label">Subject Name</label>
                                            <input type="text" class="form-control @error('subject_name') is-invalid @enderror" 
                                                   id="subject_name" name="subject_name" value="{{ old('subject_name') }}" required>
                                            @error('subject_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="subject_code" class="form-label">Subject Code</label>
                                            <input type="text" class="form-control @error('subject_code') is-invalid @enderror" 
                                                   id="subject_code" name="subject_code" value="{{ old('subject_code') }}" required>
                                            @error('subject_code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="max_marks" class="form-label">Maximum Marks</label>
                                                <input type="number" class="form-control @error('max_marks') is-invalid @enderror" 
                                                       id="max_marks" name="max_marks" value="{{ old('max_marks', 100) }}" required>
                                                @error('max_marks')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label for="pass_marks" class="form-label">Passing Marks</label>
                                                <input type="number" class="form-control @error('pass_marks') is-invalid @enderror" 
                                                       id="pass_marks" name="pass_marks" value="{{ old('pass_marks', 40) }}" required>
                                                @error('pass_marks')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="teacher_id" class="form-label">Subject Teacher</label>
                                            <select class="form-select @error('teacher_id') is-invalid @enderror" 
                                                    id="teacher_id" name="teacher_id" required>
                                                <option value="">Select Teacher</option>
                                                @foreach($teachers as $teacher)
                                                    <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                                        {{ $teacher->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('teacher_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <button type="submit" class="btn btn-primary">Add Subject</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Class Subjects ({{ $class->subjects->count() }})</h6>
                                </div>
                                <div class="card-body">
                                    @if($class->subjects->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Subject</th>
                                                        <th>Code</th>
                                                        <th>Teacher</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($class->subjects as $subject)
                                                        <tr>
                                                            <td>{{ $subject->subject_name }}</td>
                                                            <td>{{ $subject->subject_code }}</td>
                                                            <td>{{ $subject->teacher->name ?? 'Not Assigned' }}</td>
                                                            <td>
                                                                <form action="{{ route('classes.subjects.destroy', [$class->id, $subject->id]) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger btn-sm" 
                                                                            onclick="return confirm('Are you sure you want to delete this subject?')">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted">No subjects added yet.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection