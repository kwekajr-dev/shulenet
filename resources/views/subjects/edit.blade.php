@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-edit me-2"></i>Edit Subject: {{ $subject->subject_name }}
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('subjects.update', $subject->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <label for="class_id" class="col-md-4 col-form-label text-md-end">Class</label>
                            <div class="col-md-6">
                                <select class="form-select @error('class_id') is-invalid @enderror" 
                                        id="class_id" name="class_id" required>
                                    <option value="">Select Class</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ old('class_id', $subject->class_id) == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }} - {{ $class->academic_year }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('class_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="subject_name" class="col-md-4 col-form-label text-md-end">Subject Name</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control @error('subject_name') is-invalid @enderror" 
                                       id="subject_name" name="subject_name" value="{{ old('subject_name', $subject->subject_name) }}" required>
                                @error('subject_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="subject_code" class="col-md-4 col-form-label text-md-end">Subject Code</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control @error('subject_code') is-invalid @enderror" 
                                       id="subject_code" name="subject_code" value="{{ old('subject_code', $subject->subject_code) }}" required>
                                @error('subject_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="max_marks" class="form-label">Maximum Marks</label>
                                        <input type="number" class="form-control @error('max_marks') is-invalid @enderror" 
                                               id="max_marks" name="max_marks" value="{{ old('max_marks', $subject->max_marks) }}" required>
                                        @error('max_marks')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="pass_marks" class="form-label">Passing Marks</label>
                                        <input type="number" class="form-control @error('pass_marks') is-invalid @enderror" 
                                               id="pass_marks" name="pass_marks" value="{{ old('pass_marks', $subject->pass_marks) }}" required>
                                        @error('pass_marks')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="teacher_id" class="col-md-4 col-form-label text-md-end">Subject Teacher</label>
                            <div class="col-md-6">
                                <select class="form-select @error('teacher_id') is-invalid @enderror" 
                                        id="teacher_id" name="teacher_id" required>
                                    <option value="">Select Teacher</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ old('teacher_id', $subject->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->name }} ({{ $teacher->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('teacher_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Update Subject
                                </button>
                                <a href="{{ route('subjects.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i> Cancel
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