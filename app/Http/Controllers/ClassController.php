<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\ClassSubject;
use App\Models\User;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::with(['teacher', 'subjects', 'students'])->get();
        return view('classes.index', compact('classes'));
    }

    public function create()
    {
        $teachers = User::where('type', 'teacher')->get();
        return view('classes.create', compact('teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'academic_year' => 'required|string|max:255',
            'teacher_id' => 'required|exists:users,id',
            'description' => 'nullable|string'
        ]);

        SchoolClass::create($request->all());

        return redirect()->route('classes.index')
            ->with('success', 'Class created successfully.');
    }

    public function edit(SchoolClass $class)
    {
        $teachers = User::where('type', 'teacher')->get();
        return view('classes.edit', compact('class', 'teachers'));
    }

    public function update(Request $request, SchoolClass $class)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'academic_year' => 'required|string|max:255',
            'teacher_id' => 'required|exists:users,id',
            'description' => 'nullable|string'
        ]);

        $class->update($request->all());

        return redirect()->route('classes.index')
            ->with('success', 'Class updated successfully.');
    }

    public function destroy(SchoolClass $class)
    {
        $class->delete();
        return redirect()->route('classes.index')
            ->with('success', 'Class deleted successfully.');
    }

    public function subjects(SchoolClass $class)
    {
        $class->load('subjects.teacher');
        $teachers = User::where('type', 'teacher')->get();
        return view('classes.subjects', compact('class', 'teachers'));
    }

   public function storeSubject(Request $request, SchoolClass $class)
{
    $validated = $request->validate([
        'subject_name' => 'required|string|max:255',
        'subject_code' => 'required|string|max:255|unique:class_subjects,subject_code',
        'max_marks' => 'required|numeric|min:1|max:200',
        'pass_marks' => 'required|numeric|min:0|lt:max_marks',
        'teacher_id' => 'required|exists:users,id'
    ]);

    $class->subjects()->create($validated);

    return redirect()->back()->with('success', 'Subject added successfully.');
}

    public function destroySubject(SchoolClass $class, ClassSubject $subject)
    {
        $subject->delete();
        return redirect()->back()
            ->with('success', 'Subject deleted successfully.');
    }
}