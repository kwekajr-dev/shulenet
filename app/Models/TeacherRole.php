<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherRole extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    protected $appends = ['permissions_array'];

    public function teachers()
    {
        return $this->hasMany(User::class, 'teacher_role_id');
    }

    public function activities()
    {
        return $this->hasMany(TeacherActivity::class, 'role_id');
    }

    public function permissions()
    {
        return $this->hasMany(TeacherPermission::class, 'role_id');
    }
    
    public function getPermissionsArrayAttribute()
    {
        return $this->permissions->pluck('permission')->toArray();
    }
}