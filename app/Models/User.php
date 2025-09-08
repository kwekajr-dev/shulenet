<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'teacher_role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->type === 'admin';
    }

    /**
     * Check if user is a teacher
     */
    public function isTeacher(): bool
    {
        return $this->type === 'teacher';
    }

    /**
     * Check if user is a parent
     */
    public function isParent(): bool
    {
        return $this->type === 'parent';
    }

    /**
     * Get the teacher role for the user
     */
    public function teacherRole()
    {
        return $this->belongsTo(TeacherRole::class);
    }

    /**
     * Get the students for the parent user
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'parent_id');
    }

    /**
     * Get the teacher activities
     */
    public function teacherActivities()
    {
        return $this->hasMany(TeacherActivity::class, 'teacher_id');
    }

    /**
     * Check if teacher has permission for an activity type
     */
    public function hasPermission($activityType): bool
    {
        if (!$this->isTeacher() || !$this->teacherRole) {
            return false;
        }

        return in_array($activityType, $this->teacherRole->permissions);
    }
    

    public function classAssignments()
{
    return $this->hasMany(StudentClassAssignment::class, 'student_id');
}

public function classes()
{
    return $this->belongsToMany(SchoolClass::class, 'student_class_assignments', 'student_id', 'class_id')
                ->withPivot('academic_year', 'status')
                ->wherePivot('status', 'active')
                ->withTimestamps();
}
    /**
     * Get the user's notifications.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get the user's unread notifications.
     */
    public function unreadNotifications(): HasMany
    {
        return $this->hasMany(Notification::class)->unread();
    }

    /**
     * Get the user's read notifications.
     */
    public function readNotifications(): HasMany
    {
        return $this->hasMany(Notification::class)->read();
    }
}