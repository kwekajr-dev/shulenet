<?php

namespace App\Http\Controllers;

use App\Models\StudentClassAssignment;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentClassAssignmentController extends Controller
{
    /**
     * Display a listing of student-class assignments.
     */
    public function index()
    {
        $assignments = StudentClassAssignment::with(['student', 'class', 'assignedBy'])
            ->latest()
            ->paginate(20);

        return view('assignments.index', compact('assignments'));
    }

    /**
     * Show the form for creating a new student-class assignment.
     */
    public function create()
    {
        $students = User::where('type', 'student')->get();
        $classes = SchoolClass::all();
        $academicYears = $this->getAcademicYears();

        return view('assignments.create', compact('students', 'classes', 'academicYears'));
    }

    /**
     * Store a newly created student-class assignment in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'class_id' => 'required|exists:classes,id',
            'academic_year' => 'required|string|max:255',
            'notes' => 'nullable|string|max:500'
        ]);

        try {
            // Check if assignment already exists
            $existingAssignment = StudentClassAssignment::where('student_id', $request->student_id)
                ->where('class_id', $request->class_id)
                ->where('academic_year', $request->academic_year)
                ->first();

            if ($existingAssignment) {
                return redirect()->back()
                    ->with('error', 'This student is already assigned to this class for the selected academic year.')
                    ->withInput();
            }

            StudentClassAssignment::create([
                'student_id' => $request->student_id,
                'class_id' => $request->class_id,
                'academic_year' => $request->academic_year,
                'assigned_by' => Auth::id(),
                'status' => 'active',
                'notes' => $request->notes
            ]);

            return redirect()->route('assignments.index')
                ->with('success', 'Student assigned to class successfully.');

        } catch (\Exception $e) {
            Log::error('Error creating student-class assignment: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error creating assignment: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified student-class assignment.
     */
    public function edit(StudentClassAssignment $assignment)
    {
        $students = User::where('type', 'student')->get();
        $classes = SchoolClass::all();
        $academicYears = $this->getAcademicYears();

        return view('assignments.edit', compact('assignment', 'students', 'classes', 'academicYears'));
    }

    /**
     * Update the specified student-class assignment in storage.
     */
    public function update(Request $request, StudentClassAssignment $assignment)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'class_id' => 'required|exists:classes,id',
            'academic_year' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string|max:500'
        ]);

        try {
            // Check if another assignment already exists with these details
            $existingAssignment = StudentClassAssignment::where('student_id', $request->student_id)
                ->where('class_id', $request->class_id)
                ->where('academic_year', $request->academic_year)
                ->where('id', '!=', $assignment->id)
                ->first();

            if ($existingAssignment) {
                return redirect()->back()
                    ->with('error', 'Another assignment already exists with these details.')
                    ->withInput();
            }

            $assignment->update([
                'student_id' => $request->student_id,
                'class_id' => $request->class_id,
                'academic_year' => $request->academic_year,
                'status' => $request->status,
                'notes' => $request->notes
            ]);

            return redirect()->route('assignments.index')
                ->with('success', 'Assignment updated successfully.');

        } catch (\Exception $e) {
            Log::error('Error updating student-class assignment: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error updating assignment: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified student-class assignment from storage.
     */
    public function destroy(StudentClassAssignment $assignment)
    {
        try {
            $assignment->delete();

            return redirect()->route('assignments.index')
                ->with('success', 'Assignment deleted successfully.');

        } catch (\Exception $e) {
            Log::error('Error deleting student-class assignment: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error deleting assignment: ' . $e->getMessage());
        }
    }

    /**
     * Bulk assign students to a class.
     */
    public function bulkAssign(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:users,id',
            'academic_year' => 'required|string|max:255'
        ]);

        try {
            DB::beginTransaction();

            $successCount = 0;
            $errorMessages = [];

            foreach ($request->student_ids as $studentId) {
                // Check if assignment already exists
                $existingAssignment = StudentClassAssignment::where('student_id', $studentId)
                    ->where('class_id', $request->class_id)
                    ->where('academic_year', $request->academic_year)
                    ->first();

                if ($existingAssignment) {
                    $student = User::find($studentId);
                    $errorMessages[] = "Student {$student->name} is already assigned to this class for the selected academic year.";
                    continue;
                }

                StudentClassAssignment::create([
                    'student_id' => $studentId,
                    'class_id' => $request->class_id,
                    'academic_year' => $request->academic_year,
                    'assigned_by' => Auth::id(),
                    'status' => 'active'
                ]);

                $successCount++;
            }

            DB::commit();

            $message = "Successfully assigned {$successCount} students to the class.";
            if (!empty($errorMessages)) {
                $message .= " " . count($errorMessages) . " assignments failed.";
                
                return redirect()->back()
                    ->with('success', $message)
                    ->with('errors', $errorMessages);
            }

            return redirect()->route('assignments.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in bulk assignment: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Error during bulk assignment: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show bulk assignment form.
     */
    public function showBulkAssignForm()
    {
        $classes = SchoolClass::all();
        $students = User::where('type', 'student')->get();
        $academicYears = $this->getAcademicYears();

        return view('assignments.bulk-assign', compact('classes', 'students', 'academicYears'));
    }

    /**
     * Get students by class for attendance taking.
     */
    public function getStudentsByClass($classId, $academicYear = null)
    {
        try {
            $query = StudentClassAssignment::with('student')
                ->where('class_id', $classId)
                ->where('status', 'active');

            if ($academicYear) {
                $query->where('academic_year', $academicYear);
            } else {
                // Use current academic year if not specified
                $currentYear = date('Y');
                $nextYear = date('Y') + 1;
                $defaultAcademicYear = "{$currentYear}-{$nextYear}";
                $query->where('academic_year', $defaultAcademicYear);
            }

            $assignments = $query->get();

            return response()->json([
                'success' => true,
                'students' => $assignments->map(function($assignment) {
                    return [
                        'id' => $assignment->student->id,
                        'name' => $assignment->student->name,
                        'assignment_id' => $assignment->id
                    ];
                })
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching students by class: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error fetching students: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get academic years for dropdown.
     */
    private function getAcademicYears()
    {
        $currentYear = date('Y');
        $nextYear = date('Y') + 1;
        $years = [];

        // Generate options for current year and 5 previous years
        for ($i = 0; $i < 6; $i++) {
            $year = $currentYear - $i;
            $years["{$year}-" . ($year + 1)] = "{$year}-" . ($year + 1);
        }

        // Also include next year
        $years["{$currentYear}-{$nextYear}"] = "{$currentYear}-{$nextYear}";

        return $years;
    }
}