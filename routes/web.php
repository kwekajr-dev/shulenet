<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\MainController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ClassAssignmentController;
use App\Http\Controllers\StudentClassAssignmentController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/loginForm',[MainController::class,'loginForm'])->name('loginForm');
Route::post('/loginUser', [MainController::class, 'loginUser'])->name('loginUser');

// Admin user management routes
Route::get('/admin/user-management', [MainController::class, 'userManagement'])->name('admin.userManagement');
Route::post('/admin/users', [MainController::class, 'addUser'])->name('admin.addUser');
Route::post('/admin/users/{id}', [MainController::class, 'editUser'])->name('admin.editUser');
Route::delete('/admin/users/{id}', [MainController::class, 'deleteUser'])->name('admin.deleteUser');
Route::post('/admin/students', [MainController::class, 'addStudent'])->name('admin.addStudent');
Route::post('/admin/students/{id}', [MainController::class, 'editStudent'])->name('admin.editStudent');
Route::delete('/admin/students/{id}', [MainController::class, 'deleteStudent'])->name('admin.deleteStudent');

// Teacher role management routes
Route::get('/admin/teacher-management', [MainController::class, 'teacherManagement'])->name('admin.teacherManagement');
Route::get('/admin/teacher-roles', [MainController::class, 'teacherRoles'])->name('admin.teacherRoles');
Route::post('/admin/teacher-roles/create', [MainController::class, 'createTeacherRole'])->name('admin.createTeacherRole');
Route::post('/admin/teachers/{teacherId}/assign-role', [MainController::class, 'assignTeacherRole'])->name('admin.assignTeacherRole');
Route::post('/admin/teacher-activities/assign', [MainController::class, 'assignTeacherActivity'])->name('admin.assignTeacherActivity');
Route::get('/admin/teacher-activities', [MainController::class, 'getTeacherActivities'])->name('admin.getTeacherActivities');
Route::put('/admin/teacher-activities/{activity}/status', [MainController::class, 'updateActivityStatus'])->name('admin.updateActivityStatus');
Route::get('/admin/teachers-list', [MainController::class, 'teachersList'])->name('admin.teachersList');
Route::delete('/admin/teacher-roles/{id}', [MainController::class, 'deleteTeacherRole'])->name('admin.deleteRole');

// Admin dashboard route (GET)
Route::get('/admin/dashboard', [MainController::class, 'adminDashboard'])->name('admin.dashboard');

// parent dashboard route (GET)
Route::get('/admin/parent-dashboard', [MainController::class, 'parentDashboard'])->name('parent.dashboard');
Route::get('/admin/create-student-parent', [MainController::class, 'showCreateStudentParentForm'])->name('admin.create_student_parent.form');
Route::post('/admin/create-student-parent', [MainController::class, 'createStudentParent'])->name('admin.create_student_parent');


// Student-Class Assignment Routes
Route::resource('assignments', StudentClassAssignmentController::class);
Route::get('assignments/bulk/create', [StudentClassAssignmentController::class, 'showBulkAssignForm'])->name('assignments.bulk.create');
Route::post('assignments/bulk', [StudentClassAssignmentController::class, 'bulkAssign'])->name('assignments.bulk.store');
Route::get('api/class/{classId}/students', [StudentClassAssignmentController::class, 'getStudentsByClass'])->name('api.class.students');
Route::get('api/class/{classId}/students/{academicYear}', [StudentClassAssignmentController::class, 'getStudentsByClass'])->name('api.class.students.year');



//logout
Route::post('/logout', [MainController::class, 'logout'])->name('logout');

Route::get('/create-admin', function () {
    $user = new App\Models\User();
    $user->name = 'gasper kweka';
    $user->email = 'gasperkweka@gmail.com';
    $user->password = Hash::make('gasperkweka'); 
    $user->type = 'admin';
    $user->save();
    
    return 'Admin user created successfully!';
});



// Class routes
Route::resource('classes', ClassController::class);
Route::get('classes/{class}/subjects', [ClassController::class, 'subjects'])->name('classes.subjects');
Route::post('classes/{class}/subjects', [ClassController::class, 'storeSubject'])->name('classes.subjects.store');
Route::delete('classes/{class}/subjects/{subject}', [ClassController::class, 'destroySubject'])->name('classes.subjects.destroy');

// Subject routes
Route::resource('subjects', SubjectController::class);
Route::get('subjects/{subject}/analytics', [SubjectController::class, 'analytics'])->name('subjects.analytics');
Route::get('api/class/{classId}/subjects', [SubjectController::class, 'getByClass'])->name('api.class.subjects');
// Result routes
Route::resource('results', ResultController::class);
Route::get('results/class-subjects/{classId}', [ResultController::class, 'getClassSubjects'])->name('results.class-subjects');
Route::get('results/student/{studentId}', [ResultController::class, 'getStudentResults'])->name('results.student');




// FIXED INVOICE AND PAYMENT ROUTES
Route::middleware(['auth'])->group(function () {
    // Main invoice routes
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    
    // Invoice specific actions
    Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');
    
    // FIXED: Payment confirmation route - this should be the ONLY one
    Route::post('/invoices/{invoice}/confirm-payment', [InvoiceController::class, 'confirmPayment'])->name('invoices.confirm-payment');

    Route::post('/invoices/{invoice}/notify-parent', [InvoiceController::class, 'notifyParent'])->name('invoices.notify-parent');
    
    // Payment form and processing (for parent payments)
    Route::get('/invoices/{invoice}/payment', [InvoiceController::class, 'showPaymentForm'])->name('invoices.payment-form');
    Route::get('/invoices/{invoice}/confirm-payment', [InvoiceController::class, 'showPaymentConfirmation'])
    ->name('invoices.confirmation-page');

});

// Notification routes
Route::get('/notifications', [MainController::class, 'notifications'])->name('notifications');
Route::post('/notifications/{id}/read', [MainController::class, 'markNotificationAsRead'])->name('notifications.read');
Route::post('/notifications/read-all', [MainController::class, 'markAllNotificationsAsRead'])->name('notifications.read-all');
Route::delete('/notifications/{id}', [MainController::class, 'deleteNotification'])->name('notifications.delete');

// API routes for notifications
Route::get('/api/notifications', [MainController::class, 'getNotifications'])->name('api.notifications');

// Profile routes
Route::get('/profile', [MainController::class, 'profile'])->name('profile');
Route::post('/profile', [MainController::class, 'updateProfile'])->name('profile.update');

Route::get('/test-email', function () {
    try {
        Mail::raw('Test email from ShuleNet', function ($message) {
            $message->to('kwekamoses47@gmail.com')
                    ->subject('Test Email');
        });
        return 'Email sent successfully!';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

Route::get('/test-admin', function() {
    return response()->json([
        'auth_check' => Auth::check(),
        'is_admin' => Auth::check() ? Auth::user()->isAdmin() : false,
        'user' => Auth::check() ? Auth::user()->only(['id', 'name', 'email', 'type']) : null
    ]);
});