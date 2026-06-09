<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemReturn extends Model
{
    use HasFactory;

    protected $table = 'returns';

    protected $fillable = [
        'return_number',
        'borrowing_id',
        'user_id',
        'return_date',
        'overall_condition',
        'notes',
        'proof_image',
        'late_fine',
        'late_days',
        'is_paid',
        'processed_by',
        'processed_at',
    ];

    protected $casts = [
        'return_date'   => 'date',
        'processed_at'  => 'datetime',
        'late_fine'     => 'decimal:2',
        'is_paid'       => 'boolean',
    ];

    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function getProofImageUrlAttribute(): ?string
    {
        return $this->proof_image ? asset('storage/' . $this->proof_image) : null;
    }

    public static function generateNumber(): string
    {
        $prefix = 'RET';
        $date = now()->format('Ymd');
        $count = static::whereDate('created_at', today())->count() + 1;
        return $prefix . $date . str_pad($count, 3, '0', STR_PAD_LEFT);
    }
}
