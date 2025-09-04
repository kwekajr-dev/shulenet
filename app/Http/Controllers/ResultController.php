<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Notifications\ResultNotification;

class ResultController extends Controller
{
    public function index()
    {
        $results = Result::with('student')->latest()->paginate(10);
        return view('results.index', compact('results'));
    }

    public function create()
    {
        $students = Student::all();
        return view('results.create', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'term' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'score' => 'required|numeric|min:0|max:100',
            'grade' => 'required|string|max:2',
            'teacher_comment' => 'nullable|string'
        ]);

        $result = Result::create($request->all());
        
        // Send notification to parent
        $result->student->parent->notify(new ResultNotification($result));

        return redirect()->route('results.index')
            ->with('success', 'Result recorded successfully.');
    }
}