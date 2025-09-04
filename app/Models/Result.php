<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 
        'term', 
        'subject', 
        'score', 
        'grade', 
        'teacher_comment'
    ];

    protected $casts = [
        'score' => 'decimal:2'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}