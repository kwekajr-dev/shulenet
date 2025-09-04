<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'related_type',
        'related_id',
        'read_at',
        'data',
        'priority',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'read_at' => 'datetime',
        'expires_at' => 'datetime',
        'data' => 'array',
    ];

    /**
     * Notification types constants
     */
    const TYPE_ATTENDANCE = 'attendance';
    const TYPE_GRADE = 'grade';
    const TYPE_INVOICE = 'invoice';
    const TYPE_EVENT = 'event';
    const TYPE_ANNOUNCEMENT = 'announcement';
    const TYPE_SYSTEM = 'system';

    /**
     * Priority levels constants
     */
    const PRIORITY_LOW = 'low';
    const PRIORITY_NORMAL = 'normal';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';

    /**
     * Get the user that owns the notification.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the related model (polymorphic relationship).
     */
    public function related(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope a query to only include unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope a query to only include read notifications.
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Scope a query to only include notifications of a specific type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to only include high priority notifications.
     */
    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', [self::PRIORITY_HIGH, self::PRIORITY_URGENT]);
    }

    /**
     * Scope a query to only include non-expired notifications.
     */
    public function scopeUnexpired($query)
    {
        return $query->where(function($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Mark the notification as read.
     */
    public function markAsRead(): bool
    {
        if (is_null($this->read_at)) {
            $this->update(['read_at' => now()]);
            return true;
        }
        return false;
    }

    /**
     * Mark the notification as unread.
     */
    public function markAsUnread(): bool
    {
        if (!is_null($this->read_at)) {
            $this->update(['read_at' => null]);
            return true;
        }
        return false;
    }

    /**
     * Check if the notification is read.
     */
    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    /**
     * Check if the notification is unread.
     */
    public function isUnread(): bool
    {
        return is_null($this->read_at);
    }

    /**
     * Check if the notification is expired.
     */
    public function isExpired(): bool
    {
        return !is_null($this->expires_at) && $this->expires_at->isPast();
    }

    /**
     * Get the notification priority class for UI.
     */
    public function getPriorityClass(): string
    {
        switch ($this->priority) {
            case self::PRIORITY_URGENT:
                return 'danger';
            case self::PRIORITY_HIGH:
                return 'warning';
            case self::PRIORITY_NORMAL:
                return 'info';
            case self::PRIORITY_LOW:
                return 'secondary';
            default:
                return 'info';
        }
    }

    /**
     * Get the notification icon based on type.
     */
    public function getIcon(): string
    {
        switch ($this->type) {
            case self::TYPE_ATTENDANCE:
                return 'fas fa-calendar-check';
            case self::TYPE_GRADE:
                return 'fas fa-chart-line';
            case self::TYPE_INVOICE:
                return 'fas fa-file-invoice';
            case self::TYPE_EVENT:
                return 'fas fa-calendar-day';
            case self::TYPE_ANNOUNCEMENT:
                return 'fas fa-bullhorn';
            case self::TYPE_SYSTEM:
                return 'fas fa-cog';
            default:
                return 'fas fa-bell';
        }
    }
}