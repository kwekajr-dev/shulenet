<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'title',
        'amount',
        'due_date',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the student that owns the invoice.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the payments for the invoice.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Check if invoice is overdue.
     */
    public function getIsOverdueAttribute()
    {
        return $this->status !== 'paid' && $this->due_date->isPast();
    }

    /**
     * Get the total paid amount.
     */
    public function getTotalPaidAttribute()
    {
        return $this->payments()->where('status', 'completed')->sum('amount');
    }

    /**
     * Get the remaining amount to pay.
     */
    public function getRemainingAmountAttribute()
    {
        return $this->amount - $this->total_paid;
    }
}