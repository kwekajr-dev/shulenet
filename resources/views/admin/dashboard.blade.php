@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .dashboard-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }
        
        .management-section {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
            overflow: hidden;
        }
        
        .section-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 1px solid #dee2e6;
            padding: 1.5rem;
        }
        
        .section-header h4 {
            margin: 0;
            color: #495057;
            font-weight: 600;
        }
        
        .table-container {
            max-height: 500px;
            overflow-y: auto;
        }
        
        .table th {
            background-color: #f8f9fa;
            border-top: none;
            font-weight: 600;
            color: #495057;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .btn-gradient {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-gradient:hover {
            background: linear-gradient(45deg, #764ba2, #667eea);
            color: white;
            transform: translateY(-1px);
        }
        
        .action-buttons .btn {
            margin-right: 0.25rem;
            margin-bottom: 0.25rem;
        }
        
        .nav-tabs {
            border-bottom: 2px solid #dee2e6;
            margin-bottom: 2rem;
        }
        
        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 500;
            padding: 1rem 1.5rem;
            transition: all 0.3s ease;
        }
        
        .nav-tabs .nav-link.active {
            color: #667eea;
            border-bottom: 3px solid #667eea;
            background: none;
            font-weight: 600;
        }
        
        .nav-tabs .nav-link:hover {
            border-color: transparent;
            color: #667eea;
        }
        
        .badge-role {
            font-size: 0.75rem;
            padding: 0.5rem 0.75rem;
        }
        
        .quick-action-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            transition: all 0.3s ease;
        }
        
        .quick-action-card:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }
        
        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 0.5rem 0.5rem 0 0;
        }
        
        .modal-header .btn-close {
            filter: invert(1);
        }
        
        @media (max-width: 768px) {
            .stat-card {
                margin-bottom: 1rem;
            }
            
            .action-buttons {
                display: flex;
                flex-wrap: wrap;
                gap: 0.25rem;
            }
            
            .action-buttons .btn {
                flex: 1;
                min-width: 80px;
            }
        }
    </style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Dashboard Header -->
    <div class="dashboard-header p-4 mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="h2 mb-1">Admin Dashboard</h1>
                <p class="mb-0 opacity-75">Welcome back! Here's what's happening in your school today.</p>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="d-flex justify-content-md-end justify-content-start mt-3 mt-md-0">
                    <button class="btn btn-light btn-sm me-2">
                        <i class="fas fa-download"></i> Export Data
                    </button>
                    <button class="btn btn-outline-light btn-sm">
                        <i class="fas fa-cog"></i> Settings
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
            <div class="card stat-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted text-uppercase mb-2">Total Users</h6>
                            <h3 class="mb-0 font-weight-bold text-primary">{{ $users->count() }}</h3>
                            <small class="text-success">
                                <i class="fas fa-arrow-up"></i> +12% from last month
                            </small>
                        </div>
                        <div class="text-primary">
                            <i class="fas fa-users fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
            <div class="card stat-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted text-uppercase mb-2">Students</h6>
                            <h3 class="mb-0 font-weight-bold text-success">{{ $students->count() }}</h3>
                            <small class="text-success">
                                <i class="fas fa-arrow-up"></i> +8% from last month
                            </small>
                        </div>
                        <div class="text-success">
                            <i class="fas fa-graduation-cap fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
            <div class="card stat-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted text-uppercase mb-2">Teachers</h6>
                            <h3 class="mb-0 font-weight-bold text-info">{{ $teachers->count() }}</h3>
                            <small class="text-info">
                                <i class="fas fa-minus"></i> No change
                            </small>
                        </div>
                        <div class="text-info">
                            <i class="fas fa-chalkboard-teacher fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
            <div class="card stat-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted text-uppercase mb-2">Active Sessions</h6>
                            <h3 class="mb-0 font-weight-bold text-warning">{{ rand(20, 50) }}</h3>
                            <small class="text-warning">
                                <i class="fas fa-clock"></i> Real-time
                            </small>
                        </div>
                        <div class="text-warning">
                            <i class="fas fa-wifi fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="management-section">
                <div class="section-header">
                    <h4><i class="fas fa-bolt text-warning"></i> Quick Actions</h4>
                </div>
                <div class="p-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="quick-action-card p-3 text-center h-100">
                                <i class="fas fa-user-plus fa-2x mb-2"></i>
                                <h6>Add User</h6>
                                <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                    Create
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="quick-action-card p-3 text-center h-100">
                                <i class="fas fa-graduation-cap fa-2x mb-2"></i>
                                <h6>Add Student</h6>
                                <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                                    Create
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="quick-action-card p-3 text-center h-100">
                                <i class="fas fa-user-cog fa-2x mb-2"></i>
                                <h6>Role Management</h6>
                                <a href="{{ route('admin.teacherManagement') }}" class="btn btn-light btn-sm">
                                    Manage
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="quick-action-card p-3 text-center h-100">
                                <i class="fas fa-file-invoice fa-2x mb-2"></i>
                                <h6>Payments</h6>
                                <a href="#" class="btn btn-light btn-sm">
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="management-section">
                <div class="section-header">
                    <h4><i class="fas fa-chart-line text-info"></i> System Health</h4>
                </div>
                <div class="p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Database</span>
                        <span class="badge bg-success">Online</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Server Load</span>
                        <span class="badge bg-warning">Medium</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Backup Status</span>
                        <span class="badge bg-success">Up to date</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Storage</span>
                        <span class="badge bg-info">75% Used</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Management Tabs -->
    <ul class="nav nav-tabs" id="managementTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab">
                <i class="fas fa-users"></i> User Management
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="students-tab" data-bs-toggle="tab" data-bs-target="#students" type="button" role="tab">
                <i class="fas fa-graduation-cap"></i> Students
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="teachers-tab" data-bs-toggle="tab" data-bs-target="#teachers" type="button" role="tab">
                <i class="fas fa-chalkboard-teacher"></i> Teachers
            </button>
        </li>
    </ul>

    <div class="tab-content" id="managementTabsContent">
        <!-- Users Management Tab -->
        <div class="tab-pane fade show active" id="users" role="tabpanel">
            <div class="management-section">
                <div class="section-header d-flex justify-content-between align-items-center">
                    <h4><i class="fas fa-users text-primary"></i> Employee/Parent Management</h4>
                    <button class="btn btn-gradient" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="fas fa-plus"></i> Add New User
                    </button>
                </div>
                
                <div class="table-container">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td><span class="badge bg-light text-dark">#{{ $user->id }}</span></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <strong>{{ $user->name }}</strong>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge badge-role {{ $user->type === 'admin' ? 'bg-danger' : ($user->type === 'teacher' ? 'bg-info' : 'bg-success') }}">
                                        {{ ucfirst($user->type) }}
                                    </span>
                                </td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td class="action-buttons">
                                    <button class="btn btn-sm btn-outline-primary edit-user" 
                                            data-user-id="{{ $user->id }}" 
                                            data-user-name="{{ $user->name }}" 
                                            data-user-email="{{ $user->email }}" 
                                            data-user-type="{{ $user->type }}"
                                            title="Edit User">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger delete-user" 
                                            data-user-id="{{ $user->id }}" 
                                            {{ Auth::id() == $user->id ? 'disabled' : '' }}
                                            title="Delete User">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Students Management Tab -->
        <div class="tab-pane fade" id="students" role="tabpanel">
            <div class="management-section">
                <div class="section-header d-flex justify-content-between align-items-center">
                    <h4><i class="fas fa-graduation-cap text-success"></i> Students Management</h4>
                    <button class="btn btn-gradient" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                        <i class="fas fa-plus"></i> Add New Student
                    </button>
                </div>
                
                <div class="table-container">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Date of Birth</th>
                                <th>Parent</th>
                                <th>Age</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                            <tr>
                                <td><span class="badge bg-light text-dark">#{{ $student->id }}</span></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-success rounded-circle d-flex align-items-center justify-content-center me-2">
                                            {{ strtoupper(substr($student->name, 0, 1)) }}
                                        </div>
                                        <strong>{{ $student->name }}</strong>
                                    </div>
                                </td>
                                <td>{{ $student->date_of_birth }}</td>
                                <td>
                                    <div>
                                        <strong>{{ $student->parent->name }}</strong><br>
                                        <small class="text-muted">{{ $student->parent->email }}</small>
                                    </div>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($student->date_of_birth)->age }} years</td>
                                <td class="action-buttons">
                                    <button class="btn btn-sm btn-outline-primary edit-student" 
                                            data-student-id="{{ $student->id }}" 
                                            data-student-name="{{ $student->name }}" 
                                            data-student-dob="{{ $student->date_of_birth }}" 
                                            data-student-parent="{{ $student->parent_id }}"
                                            title="Edit Student">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger delete-student" 
                                            data-student-id="{{ $student->id }}"
                                            title="Delete Student">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Teachers Management Tab -->
        <div class="tab-pane fade" id="teachers" role="tabpanel">
            <div class="management-section">
                <div class="section-header d-flex justify-content-between align-items-center">
                    <h4><i class="fas fa-chalkboard-teacher text-info"></i> Teachers Overview</h4>
                    <a href="{{ route('admin.teacherManagement') }}" class="btn btn-gradient">
                        <i class="fas fa-cog"></i> Advanced Management
                    </a>
                </div>
                
                <div class="table-container">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($teachers as $teacher)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-info rounded-circle d-flex align-items-center justify-content-center me-2">
                                            {{ strtoupper(substr($teacher->name, 0, 1)) }}
                                        </div>
                                        <strong>{{ $teacher->name }}</strong>
                                    </div>
                                </td>
                                <td>{{ $teacher->email }}</td>
                                <td>
                                    @if($teacher->teacherRole)
                                        <span class="badge bg-primary badge-role">{{ $teacher->teacherRole->name }}</span>
                                    @else
                                        <span class="text-muted">No role assigned</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-success">Active</span>
                                </td>
                                <td>{{ $teacher->created_at->format('M d, Y') }}</td>
                                <td class="action-buttons">
                                    <button class="btn btn-sm btn-outline-primary" 
                                            title="Assign Role"
                                            onclick="location.href='{{ route('admin.teacherManagement') }}'">
                                        <i class="fas fa-user-tag"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-info" title="View Profile">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-user-plus"></i> Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addUserForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select class="form-select" name="type" required>
                            <option value="">Select Role</option>
                            <option value="admin">Admin</option>
                            <option value="teacher">Teacher</option>
                            <option value="parent">Parent</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gradient">
                        <i class="fas fa-plus"></i> Add User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit"></i> Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editUserForm">
                @csrf
                <input type="hidden" id="edit_user_id" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" id="edit_user_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_user_email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password (leave blank to keep current)</label>
                        <input type="password" class="form-control" name="password">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select class="form-select" id="edit_user_type" name="type" required>
                            <option value="admin">Admin</option>
                            <option value="teacher">Teacher</option>
                            <option value="parent">Parent</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gradient">
                        <i class="fas fa-save"></i> Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Student Modal -->
<div class="modal fade" id="addStudentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-graduation-cap"></i> Add New Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addStudentForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" name="date_of_birth" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Parent</label>
                        <select class="form-select" name="parent_id" required>
                            <option value="">Select Parent</option>
                            @foreach($users->where('type', 'parent') as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->name }} ({{ $parent->email }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gradient">
                        <i class="fas fa-plus"></i> Add Student
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Student Modal -->
<div class="modal fade" id="editStudentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit"></i> Edit Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editStudentForm">
                @csrf
                <input type="hidden" id="edit_student_id" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" id="edit_student_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="edit_student_dob" name="date_of_birth" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Parent</label>
                        <select class="form-select" id="edit_student_parent" name="parent_id" required>
                            <option value="">Select Parent</option>
                            @foreach($users->where('type', 'parent') as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->name }} ({{ $parent->email }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gradient">
                        <i class="fas fa-save"></i> Update Student
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Add User Form Submission
            document.getElementById('addUserForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                
                showLoadingState(this.querySelector('button[type="submit"]'), 'Adding...');
                
                fetch("{{ route('admin.addUser') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('success', data.message || 'User added successfully!');
                        bootstrap.Modal.getInstance(document.getElementById('addUserModal')).hide();
                        this.reset();
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showToast('error', data.error || 'Unknown error occurred');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', 'An error occurred. Please try again.');
                })
                .finally(() => {
                    resetLoadingState(this.querySelector('button[type="submit"]'), '<i class="fas fa-plus"></i> Add User');
                });
            });

            // Edit User Button Click
            document.querySelectorAll('.edit-user').forEach(button => {
                button.addEventListener('click', function() {
                    const userId = this.getAttribute('data-user-id');
                    const userName = this.getAttribute('data-user-name');
                    const userEmail = this.getAttribute('data-user-email');
                    const userType = this.getAttribute('data-user-type');
                    
                    document.getElementById('edit_user_id').value = userId;
                    document.getElementById('edit_user_name').value = userName;
                    document.getElementById('edit_user_email').value = userEmail;
                    document.getElementById('edit_user_type').value = userType;
                    
                    const modal = new bootstrap.Modal(document.getElementById('editUserModal'));
                    modal.show();
                });
            });

            // Edit User Form Submission
            document.getElementById('editUserForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const userId = document.getElementById('edit_user_id').value;
                
                showLoadingState(this.querySelector('button[type="submit"]'), 'Updating...');
                
                fetch(`/admin/users/${userId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('success', data.message || 'User updated successfully!');
                        bootstrap.Modal.getInstance(document.getElementById('editUserModal')).hide();
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showToast('error', data.error || 'Unknown error occurred');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', 'An error occurred. Please try again.');
                })
                .finally(() => {
                    resetLoadingState(this.querySelector('button[type="submit"]'), '<i class="fas fa-save"></i> Update User');
                });
            });

            // Delete User Button Click
            document.querySelectorAll('.delete-user').forEach(button => {
                button.addEventListener('click', function() {
                    if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) return;
                    
                    const userId = this.getAttribute('data-user-id');
                    
                    fetch(`/admin/users/${userId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast('success', data.message || 'User deleted successfully!');
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            showToast('error', data.error || 'Unknown error occurred');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('error', 'An error occurred. Please try again.');
                    });
                });
            });

            // Add Student Form Submission
            document.getElementById('addStudentForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                
                showLoadingState(this.querySelector('button[type="submit"]'), 'Adding...');
                
                fetch("{{ route('admin.addStudent') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('success', data.message || 'Student added successfully!');
                        bootstrap.Modal.getInstance(document.getElementById('addStudentModal')).hide();
                        this.reset();
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showToast('error', data.error || 'Unknown error occurred');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', 'An error occurred. Please try again.');
                })
                .finally(() => {
                    resetLoadingState(this.querySelector('button[type="submit"]'), '<i class="fas fa-plus"></i> Add Student');
                });
            });

            // Edit Student Button Click
            document.querySelectorAll('.edit-student').forEach(button => {
                button.addEventListener('click', function() {
                    const studentId = this.getAttribute('data-student-id');
                    const studentName = this.getAttribute('data-student-name');
                    const studentDob = this.getAttribute('data-student-dob');
                    const studentParent = this.getAttribute('data-student-parent');
                    
                    document.getElementById('edit_student_id').value = studentId;
                    document.getElementById('edit_student_name').value = studentName;
                    document.getElementById('edit_student_dob').value = studentDob;
                    document.getElementById('edit_student_parent').value = studentParent;
                    
                    const modal = new bootstrap.Modal(document.getElementById('editStudentModal'));
                    modal.show();
                });
            });

            // Edit Student Form Submission
            document.getElementById('editStudentForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const studentId = document.getElementById('edit_student_id').value;
                
                showLoadingState(this.querySelector('button[type="submit"]'), 'Updating...');
                
                fetch(`/admin/students/${studentId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('success', data.message || 'Student updated successfully!');
                        bootstrap.Modal.getInstance(document.getElementById('editStudentModal')).hide();
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showToast('error', data.error || 'Unknown error occurred');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', 'An error occurred. Please try again.');
                })
                .finally(() => {
                    resetLoadingState(this.querySelector('button[type="submit"]'), '<i class="fas fa-save"></i> Update Student');
                });
            });

            // Delete Student Button Click
            document.querySelectorAll('.delete-student').forEach(button => {
                button.addEventListener('click', function() {
                    if (!confirm('Are you sure you want to delete this student? This action cannot be undone.')) return;
                    
                    const studentId = this.getAttribute('data-student-id');
                    
                    fetch(`/admin/students/${studentId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast('success', data.message || 'Student deleted successfully!');
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            showToast('error', data.error || 'Unknown error occurred');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('error', 'An error occurred. Please try again.');
                    });
                });
            });

            // Tab functionality with URL hash support
            const tabs = document.querySelectorAll('#managementTabs button[data-bs-toggle="tab"]');
            tabs.forEach(tab => {
                tab.addEventListener('shown.bs.tab', function(e) {
                    const targetId = e.target.getAttribute('data-bs-target').substring(1);
                    window.location.hash = targetId;
                });
            });

            // Load tab from URL hash on page load
            if (window.location.hash) {
                const hash = window.location.hash.substring(1);
                const tab = document.querySelector(`button[data-bs-target="#${hash}"]`);
                if (tab) {
                    const tabInstance = new bootstrap.Tab(tab);
                    tabInstance.show();
                }
            }

            // Utility Functions
            function showLoadingState(button, text) {
                button.disabled = true;
                button.innerHTML = `<i class="fas fa-spinner fa-spin"></i> ${text}`;
            }

            function resetLoadingState(button, originalHtml) {
                button.disabled = false;
                button.innerHTML = originalHtml;
            }

            function showToast(type, message) {
                // Create toast container if it doesn't exist
                let toastContainer = document.getElementById('toastContainer');
                if (!toastContainer) {
                    toastContainer = document.createElement('div');
                    toastContainer.id = 'toastContainer';
                    toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
                    toastContainer.style.zIndex = '9999';
                    document.body.appendChild(toastContainer);
                }

                // Create toast element
                const toast = document.createElement('div');
                const bgClass = type === 'success' ? 'bg-success' : 'bg-danger';
                const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
                
                toast.className = `toast align-items-center text-white ${bgClass} border-0`;
                toast.setAttribute('role', 'alert');
                toast.setAttribute('aria-live', 'assertive');
                toast.setAttribute('aria-atomic', 'true');
                
                toast.innerHTML = `
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="fas ${icon} me-2"></i>${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                `;
                
                toastContainer.appendChild(toast);
                
                const bsToast = new bootstrap.Toast(toast, {
                    delay: 4000
                });
                bsToast.show();
                
                toast.addEventListener('hidden.bs.toast', function () {
                    toast.remove();
                });
            }

            // Auto-refresh stats every 5 minutes
            setInterval(() => {
                console.log('Auto-refreshing dashboard stats...');
                // You can implement AJAX refresh of statistics here
            }, 300000);

            // Initialize any additional features
            initializeDashboardFeatures();
        });

        function initializeDashboardFeatures() {
            // Add smooth scrolling to quick actions
            document.querySelectorAll('.quick-action-card').forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.05) translateY(-2px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1) translateY(0)';
                });
            });

            // Add loading animation to stat cards on hover
            document.querySelectorAll('.stat-card').forEach(card => {
                card.addEventListener('click', function() {
                    // You can implement stat card drill-down functionality here
                    console.log('Stat card clicked:', this);
                });
            });
        }

        // Global utility function for AJAX error handling
        window.handleAjaxError = function(xhr) {
            let message = 'An error occurred. Please try again.';
            
            if (xhr.responseJSON) {
                if (xhr.responseJSON.errors) {
                    const errors = Object.values(xhr.responseJSON.errors).flat();
                    message = errors.join('\n');
                } else if (xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
            }
            
            showToast('error', message);
        };
    </script>
@endsection