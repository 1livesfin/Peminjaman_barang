<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'category_id',
        'description',
        'stock',
        'stock_available',
        'condition',
        'status',
        'location',
        'brand',
        'serial_number',
        'purchase_date',
        'purchase_price',
        'image',
        'images',
        'qr_code',
        'notes',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'purchase_price' => 'decimal:2',
        'images' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function borrowingDetails()
    {
        return $this->hasMany(BorrowingDetail::class);
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/no-image.png');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'tersedia'       => '<span class="badge-success">Tersedia</span>',
            'dipinjam'       => '<span class="badge-warning">Dipinjam</span>',
            'tidak_tersedia' => '<span class="badge-danger">Tidak Tersedia</span>',
            'perbaikan'      => '<span class="badge-info">Perbaikan</span>',
            default          => '<span class="badge-secondary">Unknown</span>',
        };
    }

    public function getConditionLabelAttribute(): string
    {
        return match($this->condition) {
            'baik'        => 'Baik',
            'rusak_ringan' => 'Rusak Ringan',
            'rusak_berat'  => 'Rusak Berat',
            default        => 'Unknown',
        };
    }

    public static function generateCode(): string
    {
        $prefix = 'BRG';
        $latest = static::withTrashed()->latest()->first();
        $number = $latest ? ($latest->id + 1) : 1;
        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
