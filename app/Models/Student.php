<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'date_of_birth', 
        'parent_id'
    ];

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function classAssignments()
    {
        return $this->hasMany(ClassAssignment::class);
    }
}