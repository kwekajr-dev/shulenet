@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">All Subjects Management</h5>
                    <a href="{{ route('subjects.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add New Subject
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Subject Name</th>
                                    <th>Subject Code</th>
                                    <th>Class</th>
                                    <th>Teacher</th>
                                    <th>Max Marks</th>
                                    <th>Pass Marks</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subjects as $subject)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $subject->subject_name }}</td>
                                        <td>{{ $subject->subject_code }}</td>
                                        <td>{{ $subject->schoolClass->name ?? 'N/A' }}</td>
                                        <td>{{ $subject->teacher->name ?? 'Not Assigned' }}</td>
                                        <td>{{ $subject->max_marks }}</td>
                                        <td>{{ $subject->pass_marks }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('subjects.show', $subject->id) }}" 
                                                   class="btn btn-info btn-sm" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('subjects.edit', $subject->id) }}" 
                                                   class="btn btn-warning btn-sm" title="Edit Subject">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('subjects.analytics', $subject->id) }}" 
                                                   class="btn btn-primary btn-sm" title="View Analytics">
                                                    <i class="fas fa-chart-bar"></i>
                                                </a>
                                                <form action="{{ route('subjects.destroy', $subject->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" 
                                                            onclick="return confirm('Are you sure you want to delete this subject? This will also delete all related results.')"
                                                            title="Delete Subject">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            <i class="fas fa-book-open fa-2x mb-3"></i>
                                            <p>No subjects found. <a href="{{ route('subjects.create') }}">Create the first subject</a></p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($subjects->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $subjects->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection