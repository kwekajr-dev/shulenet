<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Attendance;
use App\Models\Invoice;
use App\Models\Event;
use App\Models\Result;
use App\Models\Announcement;
use App\Models\User;
use App\Models\TeacherRole;
use App\Models\Notification;
use App\Models\TeacherActivity;
use App\Models\TeacherPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class MainController extends Controller
{



public function loginUser(Request $request) 
    {
        try {
            Log::info('=== LOGIN ATTEMPT START ===');
            Log::info('Login attempt for email: ' . $request->email);
            Log::info('Request data:', $request->only(['email', 'remember']));
            Log::info('Session ID: ' . $request->session()->getId());
            
            // Test database connection first
            Log::info('Testing database connection...');
            try {
                $dbTest = DB::connection()->getPdo();
                Log::info('Database connection successful');
            } catch (\Exception $e) {
                Log::error('Database connection failed: ' . $e->getMessage());
                return back()->with('error', 'Database connection error. Please try again.');
            }
            
            // Validation
            Log::info('Starting validation...');
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);
            Log::info('Validation passed');

            // Check if user exists with timeout protection
            Log::info('Checking if user exists...');
            try {
                DB::connection()->getPdo()->setAttribute(\PDO::ATTR_TIMEOUT, 10);
                
                Log::info('About to execute user query...');
                $user = User::where('email', $validated['email'])->first();
                Log::info('User query completed');
                
            } catch (\Exception $e) {
                Log::error('User query failed: ' . $e->getMessage());
                return back()->with('error', 'Database query error. Please try again.');
            }
            
            if (!$user) {
                Log::warning('User not found in database for email: ' . $validated['email']);
                
                try {
                    $totalUsers = User::count();
                    Log::info('Total users in database: ' . $totalUsers);
                } catch (\Exception $e) {
                    Log::error('Could not count users: ' . $e->getMessage());
                }
                
                return back()->withErrors([
                    'email' => 'The provided credentials do not match our records.',
                ])->onlyInput('email');
            }
            
            Log::info('User found:', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_name' => $user->name,
                'user_type' => $user->type
            ]);

            // Test password manually
            Log::info('Testing password verification...');
            $passwordCheck = Hash::check($validated['password'], $user->password);
            Log::info('Password check result: ' . ($passwordCheck ? 'PASS' : 'FAIL'));

            if (!$passwordCheck) {
                Log::warning('Password verification failed');
                return back()->withErrors([
                    'email' => 'The provided credentials do not match our records.',
                ])->onlyInput('email');
            }

            // Prepare credentials
            $credentials = [
                'email' => $validated['email'],
                'password' => $validated['password']
            ];
            
            $remember = $request->has('remember');
            Log::info('About to attempt authentication with remember: ' . ($remember ? 'yes' : 'no'));
            
            // Clear any existing authentication
            Auth::logout();
            
            // Attempt authentication
            Log::info('Calling Auth::attempt...');
            $authResult = Auth::attempt($credentials, $remember);
            Log::info('Auth::attempt result: ' . ($authResult ? 'SUCCESS' : 'FAILED'));
            
            if ($authResult) {
                Log::info('Authentication successful, regenerating session...');
                $request->session()->regenerate();
                
                // Verify authentication status
                Log::info('Post-auth verification:');
                Log::info('Auth::check(): ' . (Auth::check() ? 'true' : 'false'));
                Log::info('Auth::id(): ' . Auth::id());
                Log::info('Auth::user(): ' . (Auth::user() ? Auth::user()->email : 'null'));
                Log::info('Auth::user() type: ' . (Auth::user() ? Auth::user()->type : 'null'));
                
                if (Auth::check()) {
                    Log::info('Authentication confirmed, redirecting based on type');
                    return $this->redirectBasedOntype(Auth::user())->with('success', 'Login successful!');
                } else {
                    Log::error('Auth::check() failed after successful Auth::attempt');
                }
            }

            Log::warning('Authentication failed - invalid credentials');
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Login validation failed:', $e->errors());
            return back()->withErrors($e->errors())->onlyInput('email');
            
        } catch (\Exception $e) {
            Log::error('Login error:', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'An error occurred during login. Please try again.')->onlyInput('email');
        } finally {
            Log::info('=== LOGIN ATTEMPT END ===');
        }
    }

    public function loginForm()
    {
        Log::info('Login page accessed');
        if (Auth::check()) {
            Log::info('User already authenticated, redirecting to dashboard');
            return $this->redirectBasedOntype(Auth::user());
        }
        return view('loginForm');
    }


    public function registerUser(Request $request) 
    {
        try {
            Log::info('Registration attempt started');
            
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'type' => 'sometimes|in:admin,teacher,parent',
            ]);

            Log::info('Registration validation passed');

            $user = User::create([
                'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'type' => $validated['type'],
            ]);

            Log::info('User created successfully:', ['user_id' => $user->id, 'type' => $user->type]);

            return redirect()->route('frontend.login')->with('success', 'Account created successfully!');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Registration validation failed:', $e->errors());
            return back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            Log::error('Registration failed:', ['error' => $e->getMessage()]);
            return back()->with('error', 'An error occurred. Please try again.')->withInput();
        }
    }

private function redirectBasedOnType($user)
{
    // Add a check to prevent redirect loops
    $currentRoute = request()->route()->getName();
    
    switch ($user->type) {
        case 'admin':
            if ($currentRoute === 'admin.dashboard') {
                return false; // Already on the right page
            }
            Log::info('Redirecting admin user to admin dashboard');
            return redirect()->route('admin.dashboard'); // Changed to the GET route
        case 'teacher':
            if ($currentRoute === 'frontend.staff') {
                return false;
            }
            Log::info('Redirecting staff user to staff dashboard');
            return redirect()->route('frontend.staff');
        case 'parent':
        default:
            if ($currentRoute === 'frontend.dashboard') {
                return false;
            }
            Log::info('Redirecting customer user to customer dashboard');
            return redirect()->route('frontend.dashboard');
    }
}

    


    /**
     * Display the main dashboard based on user type
     */
    public function dashboard()
    {
        if (Auth::user()->isTeacher()) {
            return $this->teacherDashboard();
        } else {
            return $this->parentDashboard();
        }
    }

    /**
     * Teacher Dashboard Data
     */
    protected function teacherDashboard()
    {
        $teacher = Auth::user();
        
        // Get teacher's students (assuming class assignments relationship)
        $studentIds = Student::whereHas('classAssignments', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        })->pluck('id');

        $data = [
            'total_students' => $studentIds->count(),
            'today_attendance' => Attendance::whereIn('student_id', $studentIds)
                ->whereDate('date', today())
                ->count(),
            'pending_invoices' => Invoice::whereIn('student_id', $studentIds)
                ->where('status', 'pending')
                ->count(),
            'upcoming_events' => Event::where('date', '>=', today())
                ->orderBy('date')
                ->take(5)
                ->count(),
            'recent_attendances' => Attendance::with('student')
                ->whereIn('student_id', $studentIds)
                ->whereDate('date', today())
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(),
            'upcoming_events_list' => Event::where('date', '>=', today())
                ->orderBy('date')
                ->take(5)
                ->get(),
            'recent_announcements' => Announcement::where('teacher_id', $teacher->id)
                ->orWhereNull('teacher_id')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get()
        ];

        return view('teacher.dashboard', $data);
    }

    /**
     * Parent Dashboard Data
     */
    protected function parentDashboard()
    {
        $parent = Auth::user();
        $children = $parent->students;

        $childrenIds = $children->pluck('id');

        // Calculate overall attendance percentage
        $totalAttendance = Attendance::whereIn('student_id', $childrenIds)
            ->where('status', 'present')
            ->count();
        $totalDays = Attendance::whereIn('student_id', $childrenIds)->count();
        $attendanceRate = $totalDays > 0 ? round(($totalAttendance / $totalDays) * 100) : 0;

        $data = [
            'children' => $children,
            'attendance_rate' => $attendanceRate,
            'pending_payments' => Invoice::whereIn('student_id', $childrenIds)
                ->where('status', 'pending')
                ->count(),
            'average_grade' => $this->calculateAverageGrade($childrenIds),
            'upcoming_events' => Event::where('date', '>=', today())
                ->orderBy('date')
                ->take(5)
                ->count(),
            'recent_grades' => Result::with('student')
                ->whereIn('student_id', $childrenIds)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(),
            'recent_announcements' => Announcement::orderBy('created_at', 'desc')
                ->take(5)
                ->get()
        ];

        return view('parent.dashboard', $data);
    }

    /**
     * Calculate average grade for children
     */
    protected function calculateAverageGrade($studentIds)
    {
        $results = Result::whereIn('student_id', $studentIds)->get();
        
        if ($results->isEmpty()) {
            return 'N/A';
        }

        $gradePoints = [
            'A+' => 4.3, 'A' => 4.0, 'A-' => 3.7,
            'B+' => 3.3, 'B' => 3.0, 'B-' => 2.7,
            'C+' => 2.3, 'C' => 2.0, 'C-' => 1.7,
            'D' => 1.0, 'F' => 0.0
        ];

        $totalPoints = 0;
        $count = 0;

        foreach ($results as $result) {
            if (isset($gradePoints[$result->grade])) {
                $totalPoints += $gradePoints[$result->grade];
                $count++;
            }
        }

        if ($count === 0) {
            return 'N/A';
        }

        $average = $totalPoints / $count;

        // Convert back to letter grade
        if ($average >= 4.0) return 'A';
        if ($average >= 3.7) return 'A-';
        if ($average >= 3.3) return 'B+';
        if ($average >= 3.0) return 'B';
        if ($average >= 2.7) return 'B-';
        if ($average >= 2.3) return 'C+';
        if ($average >= 2.0) return 'C';
        if ($average >= 1.7) return 'C-';
        if ($average >= 1.0) return 'D';
        return 'F';
    }

    /**
     * Display user profile
     */
    public function profile()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('new_password')) {
            if (!\Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect']);
            }
            $user->password = \Hash::make($request->new_password);
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Display notifications
     */
    public function notifications()
    {
        $notifications = Auth::user()->notifications()->paginate(10);
        return view('notifications', compact('notifications'));
    }

    /**
     * Mark notification as read
     */
    public function markNotificationAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllNotificationsAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Search functionality
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $user = Auth::user();
        $results = [];

        if ($user->isTeacher()) {
            $studentIds = Student::whereHas('classAssignments', function($q) use ($user) {
                $q->where('teacher_id', $user->id);
            })->pluck('id');

            // Search students
            $results['students'] = Student::whereIn('id', $studentIds)
                ->where('name', 'like', "%{$query}%")
                ->get();

            // Search attendance
            $results['attendance'] = Attendance::with('student')
                ->whereIn('student_id', $studentIds)
                ->whereDate('date', 'like', "%{$query}%")
                ->get();

        } else {
            // Parent search
            $childrenIds = $user->students->pluck('id');

            $results['children'] = $user->students()
                ->where('name', 'like', "%{$query}%")
                ->get();

            $results['grades'] = Result::with('student')
                ->whereIn('student_id', $childrenIds)
                ->where(function($q) use ($query) {
                    $q->where('subject', 'like', "%{$query}%")
                      ->orWhere('term', 'like', "%{$query}%");
                })
                ->get();
        }

        // Search events and announcements for all users
        $results['events'] = Event::where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->get();

        $results['announcements'] = Announcement::where('title', 'like', "%{$query}%")
            ->orWhere('content', 'like', "%{$query}%")
            ->get();

        return view('search', compact('results', 'query'));
    }

    /**
     * Get calendar events
     */
    public function calendarEvents()
    {
        $user = Auth::user();
        $events = Event::select('title', 'date', 'time', 'location')
            ->where('date', '>=', today())
            ->get()
            ->map(function ($event) {
                return [
                    'title' => $event->title,
                    'start' => $event->date->format('Y-m-d'),
                    'time' => $event->time ? $event->time->format('H:i') : null,
                    'location' => $event->location,
                    'allDay' => true
                ];
            });

        return response()->json($events);
    }

    /**
     * Get statistics for charts
     */
    public function statistics()
    {
        $user = Auth::user();
        $data = [];

        if ($user->isTeacher()) {
            $studentIds = Student::whereHas('classAssignments', function($q) use ($user) {
                $q->where('teacher_id', $user->id);
            })->pluck('id');

            // Attendance statistics
            $data['attendance'] = Attendance::whereIn('student_id', $studentIds)
                ->where('date', '>=', now()->subMonth())
                ->get()
                ->groupBy('status')
                ->map->count();

            // Grade distribution
            $data['grades'] = Result::whereIn('student_id', $studentIds)
                ->get()
                ->groupBy('grade')
                ->map->count();

        } else {
            $childrenIds = $user->students->pluck('id');

            // Child attendance
            $data['attendance'] = [];
            foreach ($user->students as $child) {
                $present = Attendance::where('student_id', $child->id)
                    ->where('status', 'present')
                    ->where('date', '>=', now()->subMonth())
                    ->count();
                $absent = Attendance::where('student_id', $child->id)
                    ->where('status', 'absent')
                    ->where('date', '>=', now()->subMonth())
                    ->count();

                $data['attendance'][$child->name] = [
                    'present' => $present,
                    'absent' => $absent
                ];
            }

            // Child grades
            $data['grades'] = [];
            foreach ($user->students as $child) {
                $data['grades'][$child->name] = Result::where('student_id', $child->id)
                    ->get()
                    ->groupBy('grade')
                    ->map->count();
            }
        }

        return response()->json($data);
    }

    /**
     * Export data (PDF/Excel)
     */
    public function export(Request $request, $type)
    {
        $user = Auth::user();
        $dataType = $request->input('data_type');
        
        // This would be implemented with Laravel Excel or PDF libraries
        // For now, return a message
        return back()->with('info', 'Export functionality will be implemented soon.');
    }






    //user management
    /**
     * Display user management page for admin
     */
    public function userManagement()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect()->route('loginForm')->with('error', 'Unauthorized access.');
        }
        
        $users = User::all();
        $students = Student::with('parent')->get();
        
        return view('admin.user_management', compact('users', 'students'));
    }

    /**
     * Add a new user
     */
    public function addUser(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'type' => 'required|in:admin,teacher,parent',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'type' => $validated['type'],
        ]);

        Log::info('User created by admin', ['admin_id' => Auth::id(), 'user_id' => $user->id]);

        return response()->json(['success' => 'User created successfully.', 'user' => $user]);
    }

    /**
     * Edit an existing user
     */
    public function editUser(Request $request, $id)
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'type' => 'required|in:admin,teacher,parent',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'type' => $validated['type'],
        ]);

        // Update password if provided
        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        Log::info('User updated by admin', ['admin_id' => Auth::id(), 'user_id' => $user->id]);

        return response()->json(['success' => 'User updated successfully.', 'user' => $user]);
    }

    /**
     * Delete a user
     */
    public function deleteUser($id)
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Prevent admin from deleting themselves
        if (Auth::id() == $id) {
            return response()->json(['error' => 'You cannot delete your own account.'], 400);
        }

        $user = User::findOrFail($id);
        $user->delete();

        Log::info('User deleted by admin', ['admin_id' => Auth::id(), 'user_id' => $id]);

        return response()->json(['success' => 'User deleted successfully.']);
    }

    /**
     * Add a new student
     */
    public function addStudent(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'parent_id' => 'required|exists:users,id',
        ]);

        $student = Student::create([
            'name' => $validated['name'],
            'date_of_birth' => $validated['date_of_birth'],
            'parent_id' => $validated['parent_id'],
        ]);

        Log::info('Student created by admin', ['admin_id' => Auth::id(), 'student_id' => $student->id]);

        return response()->json(['success' => 'Student created successfully.', 'student' => $student]);
    }

    /**
     * Edit an existing student
     */
    public function editStudent(Request $request, $id)
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $student = Student::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'parent_id' => 'required|exists:users,id',
        ]);

        $student->update($validated);

        Log::info('Student updated by admin', ['admin_id' => Auth::id(), 'student_id' => $student->id]);

        return response()->json(['success' => 'Student updated successfully.', 'student' => $student]);
    }

    /**
     * Delete a student
     */
    public function deleteStudent($id)
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $student = Student::findOrFail($id);
        $student->delete();

        Log::info('Student deleted by admin', ['admin_id' => Auth::id(), 'student_id' => $id]);

        return response()->json(['success' => 'Student deleted successfully.']);
    }



    //manage teacher roles and permissions
    public function assignTeacherRole(Request $request, $teacherId)
{
    if (!Auth::user()->isAdmin()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $teacher = User::where('id', $teacherId)->where('type', 'teacher')->firstOrFail();

    $validated = $request->validate([
        'role_id' => 'required|exists:teacher_roles,id',
    ]);

    $teacher->update(['teacher_role_id' => $validated['role_id']]);

    Log::info('Teacher role assigned', [
        'admin_id' => Auth::id(), 
        'teacher_id' => $teacher->id, 
        'role_id' => $validated['role_id']
    ]);

    return response()->json(['success' => 'Role assigned successfully.', 'teacher' => $teacher]);
}

/**
 * Assign activity to teacher
 */
/**
 * Assign activity to teacher
 */
public function assignTeacherActivity(Request $request)
{
    if (!Auth::user()->isAdmin()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    Log::info('Activity assignment request:', $request->all());

    try {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'activity_type' => 'required|string|max:255',
            'activity_details' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        Log::info('Activity validation passed');

        $teacher = User::findOrFail($validated['teacher_id']);
        
        // Check if teacher has a role assigned
        if (!$teacher->teacher_role_id) {
            Log::error('Teacher has no role assigned', ['teacher_id' => $teacher->id]);
            return response()->json(['error' => 'Teacher does not have a role assigned'], 400);
        }

        $activityData = [
            'teacher_id' => $validated['teacher_id'],
            'role_id' => $teacher->teacher_role_id,
            'activity_type' => $validated['activity_type'],
            'activity_details' => $validated['activity_details'] ? ['details' => $validated['activity_details']] : [],
            'assigned_at' => now(),
            'status' => 'assigned'
        ];

        // Add due date if provided
        if (!empty($validated['due_date'])) {
            $activityData['due_date'] = $validated['due_date'];
        }

        $activity = TeacherActivity::create($activityData);

        Log::info('Teacher activity assigned successfully', [
            'admin_id' => Auth::id(), 
            'teacher_id' => $validated['teacher_id'], 
            'activity_id' => $activity->id
        ]);

        return response()->json([
            'success' => 'Activity assigned successfully.', 
            'activity' => $activity
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Activity validation failed:', $e->errors());
        return response()->json(['error' => $e->errors()], 422);
        
    } catch (\Exception $e) {
        Log::error('Error assigning activity: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        return response()->json(['error' => 'Failed to assign activity: ' . $e->getMessage()], 500);
    }
}



/**
 * Get all teachers for dropdowns
 */
public function getTeachers()
{
    if (!Auth::user()->isAdmin()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $teachers = User::where('type', 'teacher')->get();
    return response()->json(['teachers' => $teachers]);
}
/**
 * Get all teacher roles
 */
public function teacherRoles()
{
    if (!Auth::user()->isAdmin()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

   $roles = TeacherRole::all();
    return response()->json(['roles' => $roles]);
}

/**
 * Create a new teacher role
 */
public function createTeacherRole(Request $request)
{
    if (!Auth::user()->isAdmin()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    try {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:teacher_roles,name',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|in:payment_confirmation,attendance_confirmation,manage_students,manage_grades,manage_events,view_reports'
        ]);

        DB::beginTransaction();
        
        $role = TeacherRole::create([
            'name' => $validated['name'],
            'description' => $validated['description']
        ]);

        // Store permissions
        if (!empty($validated['permissions'])) {
            foreach ($validated['permissions'] as $permission) {
                TeacherPermission::create([
                    'role_id' => $role->id,
                    'permission' => $permission
                ]);
            }
        }

        DB::commit();
        
        return response()->json([
            'success' => 'Role created successfully.', 
            'role' => $role->load('permissions')
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        return response()->json(['error' => $e->errors()], 422);
        
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => 'Failed to create role: ' . $e->getMessage()], 500);
    }
}
/**
 * Delete a teacher role
 */
public function deleteTeacherRole($id)
{
    if (!Auth::user()->isAdmin()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    try {
        $role = TeacherRole::findOrFail($id);
        
        // Check if role is assigned to any teacher
        if ($role->teachers()->count() > 0) {
            return response()->json(['error' => 'Cannot delete role that is assigned to teachers'], 400);
        }

        // Delete associated permissions
        $role->permissions()->delete();
        $role->delete();

        return response()->json(['success' => 'Role deleted successfully.']);

    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to delete role: ' . $e->getMessage()], 500);
    }
}
/**
 * Get teacher activities with filtering
 */
public function getTeacherActivities(Request $request)
{
    if (!Auth::user()->isAdmin()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    try {
        $query = TeacherActivity::with(['teacher', 'role.permissions']);
        
        if ($request->has('teacher_id') && $request->teacher_id) {
            $query->where('teacher_id', $request->teacher_id);
        }

        $activities = $query->orderBy('created_at', 'desc')->get();

        return response()->json(['activities' => $activities]);
    } catch (\Exception $e) {
        Log::error('Error fetching teacher activities: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to fetch activities'], 500);
    }
}

//teacher management

public function teacherManagement()
{
    if (!Auth::user()->isAdmin()) {
        return redirect()->route('loginForm')->with('error', 'Unauthorized access.');
    }

    // Use the original relationship name 'permissions'
    $roles = TeacherRole::with('permissions')->get();
    $teachers = User::where('type', 'teacher')->get();
    $activities = TeacherActivity::with(['teacher', 'role'])->orderBy('created_at', 'desc')->get();

    return view('admin.teacherManagement', compact('roles', 'teachers', 'activities'));
}

/**
 * Update teacher activity status
 */
public function updateActivityStatus(Request $request, $activityId)
{
    $activity = TeacherActivity::findOrFail($activityId);

    // Teachers can only update their own activities
    if (Auth::user()->isTeacher() && $activity->teacher_id !== Auth::id()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $validated = $request->validate([
        'status' => 'required|in:assigned,in_progress,completed,cancelled',
        'notes' => 'nullable|string',
    ]);

    $updates = ['status' => $validated['status']];
    
    if ($validated['status'] === 'completed') {
        $updates['completed_at'] = now();
    }

    if ($request->has('notes')) {
        $details = $activity->activity_details ?? [];
        $details['notes'] = $validated['notes'];
        $updates['activity_details'] = $details;
    }

    $activity->update($updates);

    Log::info('Teacher activity status updated', [
        'user_id' => Auth::id(), 
        'activity_id' => $activityId, 
        'status' => $validated['status']
    ]);

    return response()->json(['success' => 'Activity status updated successfully.', 'activity' => $activity]);
}



public function teachersList()
{
    if (!Auth::user()->isAdmin()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $teachers = User::where('type', 'teacher')
        ->select('id', 'name', 'email')
        ->get();
        
    return response()->json(['teachers' => $teachers]);
}


public function logout()
{
    Auth::logout();
    return redirect()->route('loginForm');


}

//student-parent management
public function createStudentParent(Request $request)
{
    if (!Auth::user()->isAdmin()) {
        return redirect()->route('loginForm')->with('error', 'Unauthorized access.');
    }

    $validated = $request->validate([
        'parent_name' => 'required|string|max:255',
        'parent_email' => 'required|email|max:255|unique:users,email',
        'parent_password' => 'required|string|min:8|confirmed',
        'student_name' => 'required|string|max:255',
        'date_of_birth' => 'required|date',
        'student_email' => 'nullable|email|max:255|unique:users,email',
        'student_password' => 'nullable|required_with:student_email|min:8',
    ]);

    try {
        DB::beginTransaction();

        // Create parent user
        $parent = User::create([
            'name' => $validated['parent_name'],
            'email' => $validated['parent_email'],
            'password' => Hash::make($validated['parent_password']),
            'type' => 'parent',
        ]);

        // Create student
        $studentData = [
            'name' => $validated['student_name'],
            'date_of_birth' => $validated['date_of_birth'],
            'parent_id' => $parent->id,
        ];

        // If student email provided, create a user account for student too
        if (!empty($validated['student_email'])) {
            $studentUser = User::create([
                'name' => $validated['student_name'],
                'email' => $validated['student_email'],
                'password' => Hash::make($validated['student_password']),
                'type' => 'student',
            ]);
            
       
        }

        $student = Student::create($studentData);

        DB::commit();

        return redirect()->route('admin.create_student_parent')
            ->with('success', 'Student and parent created successfully!');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error creating student and parent: ' . $e->getMessage());
        
        return back()->with('error', 'Failed to create student and parent: ' . $e->getMessage())
            ->withInput();
    }
}

public function showCreateStudentParentForm()
{
    if (!Auth::user()->isAdmin()) {
        return redirect()->route('loginForm')->with('error', 'Unauthorized access.');
    }
    
    return view('admin.create_student_parent');
}

/**
 * Display admin dashboard
 */
public function adminDashboard()
{
    if (!Auth::user()->isAdmin()) {
        return redirect()->route('loginForm')->with('error', 'Unauthorized access.');
    }
    
    $users = User::all();
    $students = Student::with('parent')->get();
    $roles = TeacherRole::with('permissions')->get();
    $teachers = User::where('type', 'teacher')->get();
    $activities = TeacherActivity::with(['teacher', 'role'])->orderBy('created_at', 'desc')->get();
    
    return view('admin.dashboard', compact('users', 'students', 'roles', 'teachers', 'activities'));
}
//notifications handling

protected function createNotification($userId, $type, $title, $message, $relatedType = null, $relatedId = null, $priority = 'normal', $data = [], $expiresAt = null)
    {
        try {
            $notification = Notification::create([
                'user_id' => $userId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'related_type' => $relatedType,
                'related_id' => $relatedId,
                'priority' => $priority,
                'data' => $data,
                'expires_at' => $expiresAt,
            ]);

            Log::info('Notification created', [
                'notification_id' => $notification->id,
                'user_id' => $userId,
                'type' => $type
            ]);

            return $notification;
        } catch (\Exception $e) {
            Log::error('Error creating notification: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create notifications for multiple users
     */
    protected function createBulkNotification($userIds, $type, $title, $message, $relatedType = null, $relatedId = null, $priority = 'normal', $data = [], $expiresAt = null)
    {
        $notifications = [];
        
        foreach ($userIds as $userId) {
            $notification = $this->createNotification(
                $userId, $type, $title, $message, $relatedType, $relatedId, $priority, $data, $expiresAt
            );
            
            if ($notification) {
                $notifications[] = $notification;
            }
        }
        
        return $notifications;
    }

    /**
     * Mark notification as read
     */
  

    /**
     * Mark all notifications as read
     */
  

    /**
     * Delete a notification
     */
    public function deleteNotification($id)
    {
        try {
            $notification = Auth::user()->notifications()->findOrFail($id);
            $notification->delete();
            
            Log::info('Notification deleted', [
                'notification_id' => $id,
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Notification deleted successfully.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error deleting notification: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete notification.'
            ], 500);
        }
    }

    /**
     * Get notifications for API
     */
    public function getNotifications(Request $request)
    {
        try {
            $user = Auth::user();
            $limit = $request->get('limit', 20);
            $type = $request->get('type');
            $unreadOnly = $request->get('unread_only', false);
            
            $query = $user->notifications()->with('related');
            
            if ($type) {
                $query->where('type', $type);
            }
            
            if ($unreadOnly) {
                $query->unread();
            }
            
            $notifications = $query->latest()
                ->take($limit)
                ->get()
                ->map(function ($notification) {
                    return [
                        'id' => $notification->id,
                        'type' => $notification->type,
                        'title' => $notification->title,
                        'message' => $notification->message,
                        'priority' => $notification->priority,
                        'priority_class' => $notification->getPriorityClass(),
                        'icon' => $notification->getIcon(),
                        'is_read' => $notification->isRead(),
                        'is_expired' => $notification->isExpired(),
                        'created_at' => $notification->created_at->diffForHumans(),
                        'related_type' => $notification->related_type,
                        'related_id' => $notification->related_id,
                        'data' => $notification->data
                    ];
                });
            
            $unreadCount = $user->unreadNotifications()->count();
            
            return response()->json([
                'success' => true,
                'notifications' => $notifications,
                'unread_count' => $unreadCount,
                'total_count' => $user->notifications()->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching notifications: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch notifications.'
            ], 500);
        }
    }

    /**
     * Create notification examples for different scenarios
     */
    
    /**
     * Create attendance notification
     */
    protected function createAttendanceNotification($studentId, $status, $date)
    {
        $student = Student::with('parent')->find($studentId);
        
        if (!$student || !$student->parent) {
            return null;
        }
        
        $title = "Attendance Recorded";
        $message = "Your child {$student->name} was marked as {$status} on {$date->format('M j, Y')}";
        
        return $this->createNotification(
            $student->parent->id,
            Notification::TYPE_ATTENDANCE,
            $title,
            $message,
            'attendance',
            $studentId,
            $status === 'absent' ? Notification::PRIORITY_HIGH : Notification::PRIORITY_NORMAL,
            [
                'student_id' => $studentId,
                'student_name' => $student->name,
                'status' => $status,
                'date' => $date->format('Y-m-d')
            ]
        );
    }

    /**
     * Create grade notification
     */
    protected function createGradeNotification($resultId)
    {
        $result = Result::with(['student', 'student.parent'])->find($resultId);
        
        if (!$result || !$result->student || !$result->student->parent) {
            return null;
        }
        
        $title = "New Grade Posted";
        $message = "{$result->student->name} received a grade of {$result->grade} in {$result->subject}";
        
        return $this->createNotification(
            $result->student->parent->id,
            Notification::TYPE_GRADE,
            $title,
            $message,
            'result',
            $resultId,
            Notification::PRIORITY_NORMAL,
            [
                'student_id' => $result->student->id,
                'student_name' => $result->student->name,
                'subject' => $result->subject,
                'grade' => $result->grade,
                'term' => $result->term
            ]
        );
    }

    /**
     * Create invoice notification
     */
    protected function createInvoiceNotification($invoiceId)
    {
        $invoice = Invoice::with(['student', 'student.parent'])->find($invoiceId);
        
        if (!$invoice || !$invoice->student || !$invoice->student->parent) {
            return null;
        }
        
        $title = "New Invoice Generated";
        $message = "New invoice #{$invoice->id} for {$invoice->student->name} amounting to {$invoice->amount}";
        
        return $this->createNotification(
            $invoice->student->parent->id,
            Notification::TYPE_INVOICE,
            $title,
            $message,
            'invoice',
            $invoiceId,
            Notification::PRIORITY_HIGH,
            [
                'student_id' => $invoice->student->id,
                'student_name' => $invoice->student->name,
                'amount' => $invoice->amount,
                'due_date' => $invoice->due_date,
                'status' => $invoice->status
            ],
            $invoice->due_date
        );
    }

    /**
     * Create event notification for multiple users
     */
    protected function createEventNotification($eventId, $userIds = null)
    {
        $event = Event::find($eventId);
        
        if (!$event) {
            return null;
        }
        
        $title = "New Event: {$event->title}";
        $message = "New event scheduled on {$event->date->format('M j, Y')} at {$event->location}";
        
        // If no specific users provided, notify all parents and teachers
        if (is_null($userIds)) {
            $userIds = User::whereIn('type', ['parent', 'teacher'])->pluck('id')->toArray();
        }
        
        return $this->createBulkNotification(
            $userIds,
            Notification::TYPE_EVENT,
            $title,
            $message,
            'event',
            $eventId,
            Notification::PRIORITY_NORMAL,
            [
                'title' => $event->title,
                'date' => $event->date->format('Y-m-d'),
                'time' => $event->time,
                'location' => $event->location,
                'description' => $event->description
            ],
            $event->date
        );
    }

}