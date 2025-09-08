@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Bulk Assign Students to Class</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('assignments.bulk.store') }}" method="POST">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="class_id" class="form-label">Class *</label>
                                <select class="form-select @error('class_id') is-invalid @enderror" 
                                        id="class_id" name="class_id" required>
                                    <option value="">Select Class</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" 
                                                {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }} - {{ $class->academic_year }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('class_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="academic_year" class="form-label">Academic Year *</label>
                                <select class="form-select @error('academic_year') is-invalid @enderror" 
                                        id="academic_year" name="academic_year" required>
                                    <option value="">Select Academic Year</option>
                                    @foreach($academicYears as $year => $label)
                                        <option value="{{ $year }}" 
                                                {{ old('academic_year') == $year ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('academic_year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Select Students *</label>
                            <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                                @foreach($students as $student)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               name="student_ids[]" value="{{ $student->id }}" 
                                               id="student_{{ $student->id }}"
                                               {{ in_array($student->id, old('student_ids', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="student_{{ $student->id }}">
                                            {{ $student->name }} ({{ $student->email }})
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('student_ids')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        @if(session('errors'))
                            <div class="alert alert-warning">
                                <strong>Some assignments failed:</strong>
                                <ul class="mb-0">
                                    @foreach(session('errors') as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('assignments.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-users"></i> Bulk Assign
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection