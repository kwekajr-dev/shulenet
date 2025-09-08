@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Student Class Assignments</h5>
                    <div>
                        <a href="{{ route('assignments.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> New Assignment
                        </a>
                        <a href="{{ route('assignments.bulk.create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-users"></i> Bulk Assign
                        </a>
                    </div>
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
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Class</th>
                                    <th>Academic Year</th>
                                    <th>Status</th>
                                    <th>Assigned By</th>
                                    <th>Assigned At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assignments as $assignment)
                                    <tr>
                                        <td>{{ $assignment->student->name }}</td>
                                        <td>{{ $assignment->class->name }}</td>
                                        <td>{{ $assignment->academic_year }}</td>
                                        <td>
                                            <span class="badge bg-{{ $assignment->status === 'active' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($assignment->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $assignment->assignedBy->name }}</td>
                                        <td>{{ $assignment->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('assignments.edit', $assignment->id) }}" 
                                                   class="btn btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('assignments.destroy', $assignment->id) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" 
                                                            onclick="return confirm('Are you sure you want to delete this assignment?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No assignments found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $assignments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection