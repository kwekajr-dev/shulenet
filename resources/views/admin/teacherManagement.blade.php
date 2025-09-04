<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Role & Permission Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .permission-card {
            transition: all 0.3s;
        }
        .permission-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .role-badge {
            font-size: 0.85rem;
        }
        .activity-status {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
        }
        .nav-tabs .nav-link.active {
            font-weight: 600;
            border-bottom: 3px solid #0d6efd;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h3 mb-0">Teacher Role & Permission Management</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.teacherManagement') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Teacher Management</li>
                    </ol>
                </nav>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <ul class="nav nav-tabs mb-4" id="managementTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="roles-tab" data-bs-toggle="tab" data-bs-target="#roles" type="button" role="tab">Roles & Permissions</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="teachers-tab" data-bs-toggle="tab" data-bs-target="#teachers" type="button" role="tab">Teacher Assignments</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="activities-tab" data-bs-toggle="tab" data-bs-target="#activities" type="button" role="tab">Activities</button>
            </li>
        </ul>

        <div class="tab-content" id="managementTabsContent">
            <!-- Roles & Permissions Tab -->
            <div class="tab-pane fade show active" id="roles" role="tabpanel">
                <div class="row">
                    <div class="col-md-5 mb-4">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0">Create New Role</h5>
                            </div>
                            <div class="card-body">
                                <form id="createRoleForm" action="{{ route('admin.createTeacherRole') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="roleName" class="form-label">Role Name</label>
                                        <input type="text" class="form-control" id="roleName" name="name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="roleDescription" class="form-label">Description</label>
                                        <textarea class="form-control" id="roleDescription" name="description" rows="2"></textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Permissions</label>
                                        <div class="border p-3 rounded">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input permission-check" type="checkbox" name="permissions[]" value="payment_confirmation" id="paymentPermission">
                                                <label class="form-check-label fw-bold" for="paymentPermission">
                                                    Payment Confirmation (Accountant)
                                                </label>
                                                <small class="d-block text-muted">Ability to confirm and manage payment records</small>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input permission-check" type="checkbox" name="permissions[]" value="attendance_confirmation" id="attendancePermission">
                                                <label class="form-check-label fw-bold" for="attendancePermission">
                                                    Attendance Confirmation (Class Teacher)
                                                </label>
                                                <small class="d-block text-muted">Ability to confirm and manage student attendance</small>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input permission-check" type="checkbox" name="permissions[]" value="manage_students" id="manageStudents">
                                                <label class="form-check-label" for="manageStudents">Manage Students</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input permission-check" type="checkbox" name="permissions[]" value="manage_grades" id="manageGrades">
                                                <label class="form-check-label" for="manageGrades">Manage Grades</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input permission-check" type="checkbox" name="permissions[]" value="manage_events" id="manageEvents">
                                                <label class="form-check-label" for="manageEvents">Manage Events</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input permission-check" type="checkbox" name="permissions[]" value="view_reports" id="viewReports">
                                                <label class="form-check-label" for="viewReports">View Reports</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-plus-circle me-1"></i> Create Role
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-7">
                        <div class="card">
                            <div class="card-header bg-secondary text-white">
                                <h5 class="card-title mb-0">Existing Roles</h5>
                            </div>
                            <div class="card-body">
                                @if($roles && count($roles) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Role Name</th>
                                                    <th>Description</th>
                                                    <th>Permissions</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($roles as $role)
                                                    <tr>
                                                        <td class="fw-bold">{{ $role->name }}</td>
                                                        <td>{{ $role->description ?? 'N/A' }}</td>
<td>
    @php
        // Debug: check what type permissions is
        // {{-- dd($role->permissions) --}}
    @endphp
    
    @if(is_object($role->permissions) && method_exists($role->permissions, 'count') && $role->permissions->count() > 0)
        @foreach($role->permissions as $permission)
            <span class="badge bg-info role-badge mb-1">{{ $permission->permission }}</span>
        @endforeach
    @else
        <span class="text-muted">No permissions</span>
    @endif
</td>
                                                        <td>
                                                            <button class="btn btn-sm btn-outline-primary edit-role" data-id="{{ $role->id }}" data-bs-toggle="tooltip" title="Edit Role">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <form action="{{ route('admin.deleteRole', $role->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this role?')" data-bs-toggle="tooltip" title="Delete Role">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-info-circle fa-2x text-muted mb-3"></i>
                                        <p class="text-muted">No roles created yet. Create your first role to get started.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Teacher Assignments Tab -->
            <div class="tab-pane fade" id="teachers" role="tabpanel">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h5 class="card-title mb-0">Assign Roles to Teachers</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="teachersTable">
                                        <thead>
                                            <tr>
                                                <th>Teacher Name</th>
                                                <th>Email</th>
                                                <th>Current Role</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($teachers && count($teachers) > 0)
                                                @foreach($teachers as $teacher)
                                                    <tr>
                                                        <td>{{ $teacher->name }}</td>
                                                        <td>{{ $teacher->email }}</td>
                                                        <td>
                                                            @if($teacher->teacherRole)
                                                                <span class="badge bg-primary">{{ $teacher->teacherRole->name }}</span>
                                                            @else
                                                                <span class="text-muted">No role assigned</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-sm btn-outline-primary assign-role" data-teacher-id="{{ $teacher->id }}" data-bs-toggle="modal" data-bs-target="#assignRoleModal">
                                                                <i class="fas fa-user-tag me-1"></i> Assign Role
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="4" class="text-center py-4">
                                                        <i class="fas fa-info-circle fa-2x text-muted mb-3"></i>
                                                        <p class="text-muted">No teachers found.</p>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Activities Tab -->
            <div class="tab-pane fade" id="activities" role="tabpanel">
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <h5 class="card-title mb-0">Assign New Activity</h5>
                            </div>
                            <div class="card-body">
                                <form id="assignActivityForm" action="{{ route('admin.assignTeacherActivity') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="teacherSelect" class="form-label">Select Teacher</label>
                                        <select class="form-select" id="teacherSelect" name="teacher_id" required>
                                            <option value="">Choose a teacher...</option>
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="activityType" class="form-label">Activity Type</label>
                                        <select class="form-select" id="activityType" name="activity_type" required>
                                            <option value="">Select activity type...</option>
                                            <option value="payment_verification">Payment Verification</option>
                                            <option value="attendance_confirmation">Attendance Confirmation</option>
                                            <option value="grade_submission">Grade Submission</option>
                                            <option value="event_organization">Event Organization</option>
                                            <option value="report_generation">Report Generation</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="activityDetails" class="form-label">Activity Details</label>
                                        <textarea class="form-control" id="activityDetails" name="activity_details" rows="3"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="dueDate" class="form-label">Due Date (optional)</label>
                                        <input type="date" class="form-control" id="dueDate" name="due_date">
                                    </div>
                                    <button type="submit" class="btn btn-info text-white">
                                        <i class="fas fa-tasks me-1"></i> Assign Activity
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Teacher Activities</h5>
                                <div class="filter-container">
                                    <select class="form-select form-select-sm" id="activityFilter">
                                        <option value="">All Teachers</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Teacher</th>
                                                <th>Activity</th>
                                                <th>Assigned</th>
                                                <th>Due Date</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="activitiesTableBody">
                                            @if($activities && count($activities) > 0)
                                                @foreach($activities as $activity)
                                                    <tr>
                                                        <td>{{ $activity->teacher->name }}</td>
                                                        <td>
                                                            <div class="fw-bold">{{ ucfirst(str_replace('_', ' ', $activity->activity_type)) }}</div>
                                                            <small class="text-muted">{{ Str::limit($activity->activity_details['details'] ?? 'No details', 30) }}</small>
                                                        </td>
                                                        <td>{{ $activity->assigned_at->format('M d, Y') }}</td>
                                                        <td>
                                                            @if($activity->due_date)
                                                                {{ \Carbon\Carbon::parse($activity->due_date)->format('M d, Y') }}
                                                            @else
                                                                <span class="text-muted">Not set</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @php
                                                                $statusClass = [
                                                                    'assigned' => 'bg-secondary',
                                                                    'in_progress' => 'bg-warning',
                                                                    'completed' => 'bg-success',
                                                                    'cancelled' => 'bg-danger'
                                                                ][$activity->status] ?? 'bg-secondary';
                                                            @endphp
                                                            <span class="badge {{ $statusClass }} activity-status">{{ ucfirst($activity->status) }}</span>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-sm btn-outline-info view-activity" data-activity-id="{{ $activity->id }}" data-bs-toggle="tooltip" title="View Details">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-outline-primary update-activity" data-activity-id="{{ $activity->id }}" data-bs-toggle="tooltip" title="Update Status">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="6" class="text-center py-4">
                                                        <i class="fas fa-info-circle fa-2x text-muted mb-3"></i>
                                                        <p class="text-muted">No activities assigned yet.</p>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Assign Role Modal -->
    <div class="modal fade" id="assignRoleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Assign Role to Teacher</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="assignRoleForm" action="{{ route('admin.assignTeacherRole', ['teacherId' => 'TEACHER_ID_PLACEHOLDER']) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="teacherIdInput" name="teacher_id">
                        <div class="mb-3">
                            <label for="roleSelect" class="form-label">Select Role</label>
                            <select class="form-select" id="roleSelect" name="role_id" required>
                                <option value="">Choose a role...</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Assign Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
 <div>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-sign-out-alt me-1"></i> Logout
        </button>
    </form>
</div>
    <!-- Activity Detail Modal -->
    <div class="modal fade" id="activityDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Activity Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="activityDetailContent">
                    <!-- Content will be loaded via JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Activity Status Modal -->
    <div class="modal fade" id="updateActivityModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Activity Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateActivityForm" action="{{ route('admin.updateActivityStatus', 0) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="activityIdInput" name="activity_id">
                        <div class="mb-3">
                            <label for="statusSelect" class="form-label">Status</label>
                            <select class="form-select" id="statusSelect" name="status" required>
                                <option value="assigned">Assigned</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="activityNotes" class="form-label">Notes (optional)</label>
                            <textarea class="form-control" id="activityNotes" name="notes" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Handle role assignment modal
            const assignRoleButtons = document.querySelectorAll('.assign-role');
            assignRoleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const teacherId = this.getAttribute('data-teacher-id');
                    document.getElementById('teacherIdInput').value = teacherId;
                    
                    // Update form action
                    const form = document.getElementById('assignRoleForm');
                    form.action = form.action.replace('TEACHER_ID_PLACEHOLDER', teacherId);
                    form.action = form.action.replace(/0$/, teacherId);
                });
            });

            // Handle activity filter
            const activityFilter = document.getElementById('activityFilter');
            if (activityFilter) {
                activityFilter.addEventListener('change', function() {
                    const teacherId = this.value;
                    // This would typically make an AJAX request to filter activities
                    console.log('Filter activities by teacher:', teacherId);
                    // For now, we'll just reload the page with the filter parameter
                    window.location.href = window.location.pathname + '?teacher_id=' + teacherId + '&tab=activities';
                });
            }

            // Handle view activity details
            const viewActivityButtons = document.querySelectorAll('.view-activity');
            viewActivityButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const activityId = this.getAttribute('data-activity-id');
                    
                    // In a real application, you would fetch activity details via AJAX
                    // For this example, we'll just show a placeholder
                    document.getElementById('activityDetailContent').innerHTML = `
                        <div class="text-center py-3">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Loading activity details...</p>
                        </div>
                    `;
                    
                    const modal = new bootstrap.Modal(document.getElementById('activityDetailModal'));
                    modal.show();
                    
                    // Simulate AJAX loading
                    setTimeout(() => {
                        document.getElementById('activityDetailContent').innerHTML = `
                            <h6>Activity Information</h6>
                            <p><strong>Teacher:</strong> John Doe</p>
                            <p><strong>Activity Type:</strong> Payment Verification</p>
                            <p><strong>Assigned On:</strong> Aug 15, 2023</p>
                            <p><strong>Due Date:</strong> Aug 20, 2023</p>
                            <p><strong>Status:</strong> <span class="badge bg-warning">In Progress</span></p>
                            <hr>
                            <h6>Details</h6>
                            <p>Verify all pending payments for the month of August and confirm receipts.</p>
                            <hr>
                            <h6>Notes</h6>
                            <p class="text-muted">No notes added yet.</p>
                        `;
                    }, 1000);
                });
            });

            // Handle update activity status
            const updateActivityButtons = document.querySelectorAll('.update-activity');
            updateActivityButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const activityId = this.getAttribute('data-activity-id');
                    document.getElementById('activityIdInput').value = activityId;
                    
                    // Update form action
                    const form = document.getElementById('updateActivityForm');
                    form.action = form.action.replace(/0$/, activityId);
                    
                    const modal = new bootstrap.Modal(document.getElementById('updateActivityModal'));
                    modal.show();
                });
            });

            // Handle form submissions with feedback
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const submitButton = this.querySelector('button[type="submit"]');
                    if (submitButton) {
                        submitButton.disabled = true;
                        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Processing...';
                    }
                });
            });
        });
    </script>
</body>
</html>
