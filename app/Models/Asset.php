<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode_asset',
        'nama_asset',
        'category_id',
        'serial_number',
        'merk',
        'model',
        'kondisi',
        'status',
        'lokasi',
        'tanggal_pembelian',
        'harga',
        'supplier',
        'deskripsi',
        'foto_asset',
        'qr_code',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pembelian' => 'date',
            'harga' => 'decimal:2',
        ];
    }

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

    public function activeBorrowing()
    {
        return $this->hasOne(Borrowing::class)
            ->whereIn('status', ['approved', 'borrowed', 'overdue'])
            ->latest();
    }

    public function histories()
    {
        return $this->hasMany(AssetHistory::class)->orderByDesc('created_at');
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeBorrowed($query)
    {
        return $query->where('status', 'borrowed');
    }

    public function scopeMaintenance($query)
    {
        return $query->where('status', 'maintenance');
    }

    public function scopeBroken($query)
    {
        return $query->where('status', 'broken');
    }

    public function scopeLost($query)
    {
        return $query->where('status', 'lost');
    }

    public function scopeSearch($query, ?string $search)
    {
        if (!$search) return $query;

        return $query->where(function ($q) use ($search) {
            $q->where('kode_asset', 'like', "%{$search}%")
              ->orWhere('nama_asset', 'like', "%{$search}%")
              ->orWhere('merk', 'like', "%{$search}%")
              ->orWhere('serial_number', 'like', "%{$search}%")
              ->orWhere('lokasi', 'like', "%{$search}%");
        });
    }

    public function scopeFilterStatus($query, ?string $status)
    {
        if (!$status) return $query;
        return $query->where('status', $status);
    }

    public function scopeFilterCategory($query, ?int $categoryId)
    {
        if (!$categoryId) return $query;
        return $query->where('category_id', $categoryId);
    }

    public function scopeFilterKondisi($query, ?string $kondisi)
    {
        if (!$kondisi) return $query;
        return $query->where('kondisi', $kondisi);
    }

    // Accessors
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'available' => 'bg-green-100 text-green-800',
            'borrowed' => 'bg-blue-100 text-blue-800',
            'maintenance' => 'bg-yellow-100 text-yellow-800',
            'broken' => 'bg-red-100 text-red-800',
            'lost' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'available' => 'Available',
            'borrowed' => 'Borrowed',
            'maintenance' => 'Maintenance',
            'broken' => 'Broken',
            'lost' => 'Lost',
            default => ucfirst($this->status),
        };
    }

    public function getKondisiLabelAttribute(): string
    {
        return match ($this->kondisi) {
            'baik' => 'Baik',
            'cukup' => 'Cukup',
            'rusak_ringan' => 'Rusak Ringan',
            'rusak_berat' => 'Rusak Berat',
            default => ucfirst($this->kondisi),
        };
    }

    public function getQrUrlAttribute(): string
    {
        return url("/scan/{$this->kode_asset}");
    }

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    public function isBorrowed(): bool
    {
        return $this->status === 'borrowed';
    }
}
