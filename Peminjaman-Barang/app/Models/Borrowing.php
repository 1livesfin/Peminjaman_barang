<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Borrowing extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'borrowing_number',
        'user_id',
        'borrower_name',
        'borrower_phone',
        'borrower_department',
        'borrow_date',
        'return_date',
        'actual_return_date',
        'purpose',
        'status',
        'admin_notes',
        'rejection_reason',
        'approved_by',
        'approved_at',
        'late_fine',
    ];

    protected $casts = [
        'borrow_date'         => 'date',
        'return_date'         => 'date',
        'actual_return_date'  => 'date',
        'approved_at'         => 'datetime',
        'late_fine'           => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function details()
    {
        return $this->hasMany(BorrowingDetail::class);
    }

    public function return()
    {
        return $this->hasOne(ItemReturn::class);
    }

    public function getIsLateAttribute(): bool
    {
        if (in_array($this->status, ['dipinjam', 'terlambat'])) {
            return Carbon::now()->gt($this->return_date);
        }
        return false;
    }

    public function getLateDaysAttribute(): int
    {
        if ($this->is_late) {
            return Carbon::now()->diffInDays($this->return_date);
        }
        return 0;
    }

    public function getStatusBadgeAttribute(): array
    {
        return match($this->status) {
            'menunggu'    => ['label' => 'Menunggu', 'class' => 'bg-yellow-100 text-yellow-800'],
            'disetujui'   => ['label' => 'Disetujui', 'class' => 'bg-blue-100 text-blue-800'],
            'ditolak'     => ['label' => 'Ditolak', 'class' => 'bg-red-100 text-red-800'],
            'dipinjam'    => ['label' => 'Dipinjam', 'class' => 'bg-indigo-100 text-indigo-800'],
            'dikembalikan' => ['label' => 'Dikembalikan', 'class' => 'bg-green-100 text-green-800'],
            'terlambat'   => ['label' => 'Terlambat', 'class' => 'bg-red-100 text-red-800'],
            default       => ['label' => 'Unknown', 'class' => 'bg-gray-100 text-gray-800'],
        };
    }

    public static function generateNumber(): string
    {
        $prefix = 'BRW';
        $date = now()->format('Ymd');
        $count = static::whereDate('created_at', today())->count() + 1;
        return $prefix . $date . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    public function calculateLateFine(): float
    {
        if ($this->actual_return_date && $this->actual_return_date->gt($this->return_date)) {
            $lateDays = $this->actual_return_date->diffInDays($this->return_date);
            return $lateDays * 10000; // Rp 10.000 per day
        }
        return 0;
    }
}
