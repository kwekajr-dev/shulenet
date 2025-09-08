<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'name',
        'academic_year',
        'teacher_id',
        'description'
    ];
    

    
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function subjects()
    {
        return $this->hasMany(ClassSubject::class, 'class_id');
    }

    public function students()
    {
        return $this->hasMany(ClassAssignment::class, 'class_id');
    }
    
}