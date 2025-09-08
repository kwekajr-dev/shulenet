@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Classes Management</h5>
                    <a href="{{ route('classes.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add New Class
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Class Name</th>
                                    <th>Academic Year</th>
                                    <th>Class Teacher</th>
                                    <th>Subjects Count</th>
                                    <th>Students Count</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($classes as $class)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $class->name }}</td>
                                        <td>{{ $class->academic_year }}</td>
                                        <td>{{ $class->teacher->name ?? 'Not Assigned' }}</td>
                                        <td>{{ $class->subjects->count() }}</td>
                                        <td>{{ $class->students->count() }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('classes.subjects', $class->id) }}" 
                                                   class="btn btn-info btn-sm" title="Manage Subjects">
                                                    <i class="fas fa-book"></i>
                                                </a>
                                                <a href="{{ route('classes.edit', $class->id) }}" 
                                                   class="btn btn-warning btn-sm" title="Edit Class">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('classes.destroy', $class->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" 
                                                            onclick="return confirm('Are you sure you want to delete this class?')"
                                                            title="Delete Class">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No classes found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection