@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-book me-2"></i>Subject Details: {{ $subject->subject_name }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="140">Subject Name:</th>
                                    <td>{{ $subject->subject_name }}</td>
                                </tr>
                                <tr>
                                    <th>Subject Code:</th>
                                    <td>{{ $subject->subject_code }}</td>
                                </tr>
                                <tr>
                                    <th>Class:</th>
                                    <td>{{ $subject->schoolClass->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Academic Year:</th>
                                    <td>{{ $subject->schoolClass->academic_year ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="140">Teacher:</th>
                                    <td>{{ $subject->teacher->name ?? 'Not Assigned' }}</td>
                                </tr>
                                <tr>
                                    <th>Max Marks:</th>
                                    <td>{{ $subject->max_marks }}</td>
                                </tr>
                                <tr>
                                    <th>Pass Marks:</th>
                                    <td>{{ $subject->pass_marks }}</td>
                                </tr>
                                <tr>
                                    <th>Created:</th>
                                    <td>{{ $subject->created_at->format('M d, Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h6 class="border-bottom pb-2">Recent Results</h6>
                        @if($subject->results->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Student</th>
                                            <th>Term</th>
                                            <th>Score</th>
                                            <th>Grade</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($subject->results->take(5) as $result)
                                            <tr>
                                                <td>{{ $result->student->name ?? 'N/A' }}</td>
                                                <td>{{ $result->term }}</td>
                                                <td>{{ $result->score }}/{{ $subject->max_marks }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $result->score >= $subject->pass_marks ? 'success' : 'danger' }}">
                                                        {{ $result->grade }}
                                                    </span>
                                                </td>
                                                <td>{{ $result->created_at->format('M d, Y') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($subject->results->count() > 5)
                                <div class="text-center mt-2">
                                    <a href="{{ route('subjects.analytics', $subject->id) }}" class="btn btn-sm btn-outline-primary">
                                        View All Results
                                    </a>
                                </div>
                            @endif
                        @else
                            <p class="text-muted">No results recorded yet.</p>
                        @endif
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('subjects.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back to Subjects
                        </a>
                        <div>
                            <a href="{{ route('subjects.edit', $subject->id) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-1"></i> Edit
                            </a>
                            <a href="{{ route('subjects.analytics', $subject->id) }}" class="btn btn-primary">
                                <i class="fas fa-chart-bar me-1"></i> Analytics
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('classes.subjects', $subject->class_id) }}" class="btn btn-outline-primary">
                            <i class="fas fa-list me-2"></i> View Class Subjects
                        </a>
                        <a href="#" class="btn btn-outline-success">
                            <i class="fas fa-plus me-2"></i> Add Results
                        </a>
                        <a href="#" class="btn btn-outline-info">
                            <i class="fas fa-download me-2"></i> Export Data
                        </a>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">Subject Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="mb-3">
                            <span class="h2 d-block">{{ $subject->results->count() }}</span>
                            <small class="text-muted">Total Results</small>
                        </div>
                        <div class="mb-3">
                            <span class="h4 d-block text-success">
                                {{ $subject->results->where('score', '>=', $subject->pass_marks)->count() }}
                            </span>
                            <small class="text-muted">Passed Students</small>
                        </div>
                        <div>
                            <span class="h4 d-block text-danger">
                                {{ $subject->results->where('score', '<', $subject->pass_marks)->count() }}
                            </span>
                            <small class="text-muted">Failed Students</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection