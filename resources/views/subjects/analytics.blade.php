@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Analytics: {{ $subject->subject_name }}
                    </h5>
                    <div>
                        <a href="{{ route('subjects.show', $subject->id) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Back to Subject
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card text-center bg-primary text-white">
                                <div class="card-body">
                                    <h3 class="card-title">{{ $stats['total_students'] }}</h3>
                                    <p class="card-text">Total Students</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center bg-success text-white">
                                <div class="card-body">
                                    <h3 class="card-title">{{ number_format($stats['average_score'], 1) }}</h3>
                                    <p class="card-text">Average Score</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center bg-info text-white">
                                <div class="card-body">
                                    <h3 class="card-title">{{ $stats['highest_score'] }}</h3>
                                    <p class="card-text">Highest Score</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center bg-warning text-white">
                                <div class="card-body">
                                    <h3 class="card-title">{{ number_format($stats['pass_rate'], 1) }}%</h3>
                                    <p class="card-text">Pass Rate</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Results Table -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">All Results</h6>
                        </div>
                        <div class="card-body">
                            @if($subject->results->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Student</th>
                                                <th>Class</th>
                                                <th>Term</th>
                                                <th>Score</th>
                                                <th>Percentage</th>
                                                <th>Grade</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($subject->results as $result)
                                                <tr>
                                                    <td>{{ $result->student->name ?? 'N/A' }}</td>
                                                    <td>{{ $result->schoolClass->name ?? 'N/A' }}</td>
                                                    <td>{{ $result->term }}</td>
                                                    <td>{{ $result->score }}/{{ $subject->max_marks }}</td>
                                                    <td>{{ number_format(($result->score / $subject->max_marks) * 100, 1) }}%</td>
                                                    <td>
                                                        <span class="badge bg-primary">{{ $result->grade }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $result->score >= $subject->pass_marks ? 'success' : 'danger' }}">
                                                            {{ $result->score >= $subject->pass_marks ? 'Passed' : 'Failed' }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $result->created_at->format('M d, Y') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-chart-line fa-3x mb-3"></i>
                                    <p>No results available for analytics.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Grade Distribution -->
                    @if($subject->results->count() > 0)
                    <div class="card mt-4">
                        <div class="card-header">
                            <h6 class="mb-0">Grade Distribution</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <canvas id="gradeChart" height="200"></canvas>
                                </div>
                                <div class="col-md-4">
                                    <h6>Grade Summary</h6>
                                    <table class="table table-sm">
                                        @php
                                            $grades = ['A+', 'A', 'B+', 'B', 'C', 'D', 'F'];
                                            $gradeCounts = array_fill_keys($grades, 0);
                                            foreach($subject->results as $result) {
                                                if(isset($gradeCounts[$result->grade])) {
                                                    $gradeCounts[$result->grade]++;
                                                }
                                            }
                                        @endphp
                                        @foreach($gradeCounts as $grade => $count)
                                            <tr>
                                                <td>{{ $grade }}</td>
                                                <td>{{ $count }}</td>
                                                <td>{{ $stats['total_students'] > 0 ? number_format(($count / $stats['total_students']) * 100, 1) : 0 }}%</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if($subject->results->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('gradeChart').getContext('2d');
        const grades = ['A+', 'A', 'B+', 'B', 'C', 'D', 'F'];
        const gradeCounts = Array(7).fill(0);
        
        @foreach($subject->results as $result)
            const gradeIndex = grades.indexOf('{{ $result->grade }}');
            if (gradeIndex !== -1) {
                gradeCounts[gradeIndex]++;
            }
        @endforeach

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: grades,
                datasets: [{
                    label: 'Number of Students',
                    data: gradeCounts,
                    backgroundColor: [
                        '#10b981', '#34d399', '#60a5fa', '#3b82f6', '#f59e0b', '#f97316', '#ef4444'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Students'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Grades'
                        }
                    }
                }
            }
        });
    });
</script>
@endif
@endpush