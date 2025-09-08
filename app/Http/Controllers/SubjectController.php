<?php

namespace App\Http\Controllers;

use App\Models\ClassSubject;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    /**
     * Display a listing of all subjects across classes.
     */
    public function index()
    {
        $subjects = ClassSubject::with(['schoolClass', 'teacher'])
            ->latest()
            ->paginate(20);

        return view('subjects.index', compact('subjects'));
    }

    /**
     * Show the form for creating a new subject.
     */
    public function create()
    {
        $classes = SchoolClass::all();
        $teachers = User::where('type', 'teacher')->get();

        return view('subjects.create', compact('classes', 'teachers'));
    }

    /**
     * Store a newly created subject in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
        'class_id' => 'required|exists:classes,id', // Changed to 'classes'
        'subject_name' => 'required|string|max:255',
        'subject_code' => 'required|string|max:255|unique:class_subjects,subject_code',
        'max_marks' => 'required|numeric|min:1|max:200',
        'pass_marks' => 'required|numeric|min:0|lt:max_marks',
        'teacher_id' => 'required|exists:users,id'
    ]);
        try {
            ClassSubject::create($request->all());

            return redirect()->route('subjects.index')
                ->with('success', 'Subject created successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating subject: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified subject.
     */
    public function show(ClassSubject $subject)
    {
        $subject->load(['schoolClass', 'teacher', 'results.student']);

        return view('subjects.show', compact('subject'));
    }

    /**
     * Show the form for editing the specified subject.
     */
    public function edit(ClassSubject $subject)
    {
        $classes = SchoolClass::all();
        $teachers = User::where('type', 'teacher')->get();

        return view('subjects.edit', compact('subject', 'classes', 'teachers'));
    }

    /**
     * Update the specified subject in storage.
     */
    public function update(Request $request, ClassSubject $subject)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_name' => 'required|string|max:255',
            'subject_code' => 'required|string|max:255|unique:class_subjects,subject_code,' . $subject->id,
            'max_marks' => 'required|numeric|min:1|max:200',
            'pass_marks' => 'required|numeric|min:0|lt:max_marks',
            'teacher_id' => 'required|exists:users,id'
        ]);

        try {
            $subject->update($request->all());

            return redirect()->route('subjects.index')
                ->with('success', 'Subject updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating subject: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified subject from storage.
     */
    public function destroy(ClassSubject $subject)
    {
        try {
            DB::transaction(function () use ($subject) {
                // Delete related results first
                $subject->results()->delete();
                
                // Then delete the subject
                $subject->delete();
            });

            return redirect()->route('subjects.index')
                ->with('success', 'Subject deleted successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting subject: ' . $e->getMessage());
        }
    }

    /**
     * Get subjects by class ID (for AJAX requests)
     */
    public function getByClass($classId)
    {
        $subjects = ClassSubject::where('class_id', $classId)
            ->with('teacher')
            ->get();

        return response()->json($subjects);
    }

    /**
     * Show subject analytics and statistics
     */
    public function analytics(ClassSubject $subject)
    {
        $subject->load(['results' => function ($query) {
            $query->with('student')
                  ->orderBy('score', 'desc');
        }]);

        $stats = [
            'total_students' => $subject->results->count(),
            'average_score' => $subject->results->avg('score'),
            'highest_score' => $subject->results->max('score'),
            'lowest_score' => $subject->results->min('score'),
            'pass_rate' => $subject->results->where('score', '>=', $subject->pass_marks)->count() / max($subject->results->count(), 1) * 100
        ];

        return view('subjects.analytics', compact('subject', 'stats'));
    }
}