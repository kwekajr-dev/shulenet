<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentClassAssignment extends Model
{
    use HasFactory;

    protected $table = 'student_class_assignments';

    protected $fillable = [
        'student_id',
        'class_id',
        'academic_year',
        'assigned_by',
        'status',
        'notes'
    ];

    protected $casts = [
        'academic_year' => 'string',
        'status' => 'string'
    ];

    /**
     * Get the student associated with this assignment.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the class associated with this assignment.
     */
    public function class(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /**
     * Get the teacher/admin who made this assignment.
     */
    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Scope a query to only include active assignments.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to filter by academic year.
     */
    public function scopeForAcademicYear($query, $academicYear)
    {
        return $query->where('academic_year', $academicYear);
    }

    /**
     * Scope a query to filter by class.
     */
    public function scopeForClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }

    /**
     * Check if assignment is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}