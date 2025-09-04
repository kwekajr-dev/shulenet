<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illiminate\Support\Facades\log;
use App\Http\Controllers\MainController;
use App\Http\Controllers\InvoiceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



Route::get('/loginForm',[App\Http\Controllers\MainController::class,'loginForm'])->name('loginForm');
Route::post('/loginUser', [App\Http\Controllers\MainController::class, 'loginUser'])->name('loginUser');

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

// Keep your existing POST route for form submission
Route::post('/admin/create-student-parent', [MainController::class, 'createStudentParent'])->name('admin.create_student_parent');
Route::get('/admin/create-student-parent', [MainController::class, 'showCreateStudentParentForm'])->name('admin.create_student_parent');

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



// Invoice routes
Route::middleware(['auth'])->group(function () {
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::post('.invoices/store', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/invoices/{invoice}/payment', [InvoiceController::class, 'showPaymentForm'])->name('invoices.payment-form');
    Route::post('/invoices/{invoice}/process-payment', [InvoiceController::class, 'processPayment'])->name('invoices.process-payment');
    Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');
    Route::get('/invoices/{invoice}/payment-history', [InvoiceController::class, 'paymentHistory'])->name('invoices.payment-history');
    Route::get('/payments', [InvoiceController::class, 'paymentsIndex'])->name('payments.index');
   
    Route::resource('invoices', InvoiceController::class);
    Route::post('invoices/{invoice}/confirm-payment', [InvoiceController::class, 'confirmPayment'])->name('invoices.confirm-payment');
    Route::post('invoices/{invoice}/notify', [InvoiceController::class, 'sendNotification'])->name('invoices.notify');
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

// Add to routes/web.php
Route::get('/test-admin', function() {
    return response()->json([
        'auth_check' => Auth::check(),
        'is_admin' => Auth::check() ? Auth::user()->isAdmin() : false,
        'user' => Auth::check() ? Auth::user()->only(['id', 'name', 'email', 'type']) : null
    ]);
});