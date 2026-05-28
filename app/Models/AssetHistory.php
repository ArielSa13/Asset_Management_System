<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetHistory extends Model
{
    protected $fillable = [
        'asset_id',
        'action',
        'description',
        'old_values',
        'new_values',
        'user_id',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
        ];
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'created' => 'Asset Created',
            'updated' => 'Asset Updated',
            'deleted' => 'Asset Deleted',
            'status_changed' => 'Status Changed',
            'borrowed' => 'Asset Borrowed',
            'returned' => 'Asset Returned',
            'maintenance' => 'Sent to Maintenance',
            default => ucfirst(str_replace('_', ' ', $this->action)),
        };
    }
}
