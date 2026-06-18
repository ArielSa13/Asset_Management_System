<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Borrowing extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'asset_id',
        'borrower_name',
        'borrower_email',
        'borrower_phone',
        'purpose',
        'borrow_date',
        'return_date',
        'actual_return_date',
        'status',
        'admin_notes',
        'approved_by',
        'approved_at',
        'returned_at',
    ];

    protected function casts(): array
    {
        return [
            'borrow_date' => 'date',
            'return_date' => 'date',
            'actual_return_date' => 'date',
            'approved_at' => 'datetime',
            'returned_at' => 'datetime',
        ];
    }

    // Relationships
    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeBorrowed($query)
    {
        return $query->where('status', 'borrowed');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    public function scopeReturned($query)
    {
        return $query->where('status', 'returned');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['approved', 'borrowed', 'overdue']);
    }

    // Accessors
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-blue-100 text-blue-800',
            'rejected' => 'bg-red-100 text-red-800',
            'borrowed' => 'bg-indigo-100 text-indigo-800',
            'returned' => 'bg-green-100 text-green-800',
            'overdue' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return ucfirst($this->status);
    }

    public function isOverdue(): bool
    {
        if (in_array($this->status, ['returned', 'rejected'])) {
            return false;
        }

        if (!$this->return_date) {
            return false; // No return date set, can't be overdue
        }

        return $this->return_date->isPast() && $this->status !== 'returned';
    }

    public function getDaysRemainingAttribute(): ?int
    {
        if ($this->status === 'returned') return 0;
        if (!$this->return_date) return null; // No deadline
        return now()->diffInDays($this->return_date, false);
    }
}
