<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id', 
        'title', 
        'content', 
        'target_audience'
    ];

    protected $casts = [
        'target_audience' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the teacher who created the announcement.
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Scope a query to only include announcements for a specific class.
     */
    public function scopeForClass($query, $className)
    {
        return $query->whereJsonContains('target_audience', $className)
                    ->orWhereNull('target_audience');
    }

    /**
     * Check if announcement is for all students.
     */
    public function isForAll()
    {
        return is_null($this->target_audience);
    }

    /**
     * Get the target classes as an array.
     */
    public function getTargetClassesAttribute()
    {
        return $this->target_audience ?? [];
    }
}