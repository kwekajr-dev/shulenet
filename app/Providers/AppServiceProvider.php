<?php

namespace App\Providers;
use App\Models\User;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\Invoice;
use App\Models\Event;
use App\Models\Activity;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  public function boot()
{
    // Share dashboard data with the app layout
    View::composer('layouts.app', function ($view) {
        if (Auth::check()) {
            $user = Auth::user();
            
            if ($user->type === 'admin') {
                $view->with([
                    'users' => User::count(),
                    'students' => Student::count(),
                    'teachers' => User::where('type', 'teacher')->count(),
                ]);
            } elseif ($user->type === 'teacher') {
                $view->with([
                    'data' => [
                        'total_students' => Student::count(),
                        'today_attendance' => Attendance::whereDate('created_at', today())->count(),
                        'pending_invoices' => Invoice::where('status', 'pending')->count(),
                        'upcoming_events' => Event::where('date', '>=', today())->count(),
                    ]
                ]);
            } elseif ($user->type === 'parent') {
                $view->with([
                    'data' => [
                        'children' => Student::where('parent_id', $user->id)->get(),
                        'attendance_rate' => 95,
                        'pending_payments' => Invoice::whereHas('student', function($query) use ($user) {
                            $query->where('parent_id', $user->id);
                        })->where('status', 'pending')->count(),
                        'average_grade' => 'B+',
                    ]
                ]);
            }
        }
    });

    // Share data with payment views
    View::composer(['payments.index', 'payments.*'], function ($view) {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Add payment-specific data here if needed
            $view->with([
                'payment_stats' => [
                    'total_paid' => Invoice::where('status', 'paid')->count(),
                    'total_pending' => Invoice::where('status', 'pending')->count(),
                    'total_overdue' => Invoice::where('status', 'overdue')->count(),
                ]
            ]);
        }
    });
}
}