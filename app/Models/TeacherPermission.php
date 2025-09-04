<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherPermission extends Model
{
    use HasFactory;

    protected $fillable = ['role_id', 'permission'];

    public function role()
    {
        return $this->belongsTo(TeacherRole::class, 'role_id');
    }
}