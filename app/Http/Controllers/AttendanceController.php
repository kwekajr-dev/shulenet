<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Notifications\AttendanceRecorded;

class AttendanceController extends Controller
{
    // ... other methods ...
    
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late'
        ]);

        $attendance = Attendance::create($request->all());
        
        // Send notification to parent
        $attendance->student->parent->notify(new AttendanceRecorded($attendance));

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance recorded successfully.');
    }
}