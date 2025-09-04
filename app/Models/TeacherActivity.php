<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id', 
        'role_id', 
        'activity_type', 
        'activity_details', 
        'status',
        'assigned_at',
        'completed_at'
    ];

    protected $casts = [
        'activity_details' => 'array',
        'assigned_at' => 'datetime',
        'completed_at' => 'datetime'
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function role()
    {
        return $this->belongsTo(TeacherRole::class, 'role_id');
    }
}