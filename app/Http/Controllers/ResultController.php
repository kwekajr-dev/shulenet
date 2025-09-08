<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\ClassSubject;
use Illuminate\Http\Request;
use App\Notifications\ResultNotification;

class ResultController extends Controller
{
    public function index()
    {
        $results = Result::with(['student', 'schoolClass', 'subject'])->latest()->paginate(10);
        return view('results.index', compact('results'));
    }

    public function create()
    {
        $students = Student::with('currentClass')->get();
        $classes = SchoolClass::with('subjects')->get();
        return view('results.create', compact('students', 'classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:class_subjects,id',
            'term' => 'required|string|max:255',
            'score' => 'required|numeric|min:0',
            'teacher_comment' => 'nullable|string'
        ]);

        // Calculate grade based on score
        $score = $request->score;
        $subject = ClassSubject::find($request->subject_id);
        
        $grade = $this->calculateGrade($score, $subject->max_marks);

        $resultData = $request->all();
        $resultData['grade'] = $grade;

        $result = Result::create($resultData);
        
        // Send notification to parent
        $result->student->parent->notify(new ResultNotification($result));

        return redirect()->route('results.index')
            ->with('success', 'Result recorded successfully.');
    }

    public function getClassSubjects($classId)
    {
        $class = SchoolClass::with('subjects')->findOrFail($classId);
        return response()->json($class->subjects);
    }

    public function getStudentResults($studentId, $term = null)
    {
        $query = Result::with(['subject', 'schoolClass'])
            ->where('student_id', $studentId);
            
        if ($term) {
            $query->where('term', $term);
        }
        
        $results = $query->get();
        
        // Calculate average
        $average = $results->avg('score');
        $overallGrade = $this->calculateGrade($average, 100); // Assuming max marks is 100 for average
        
        return response()->json([
            'results' => $results,
            'average' => round($average, 2),
            'overall_grade' => $overallGrade
        ]);
    }

    private function calculateGrade($score, $maxMarks)
    {
        $percentage = ($score / $maxMarks) * 100;
        
        if ($percentage >= 90) return 'A+';
        if ($percentage >= 80) return 'A';
        if ($percentage >= 70) return 'B+';
        if ($percentage >= 60) return 'B';
        if ($percentage >= 50) return 'C';
        if ($percentage >= 40) return 'D';
        return 'F';
    }
}