<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PointRedemption extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'points_used',
        'rupiah_amount',
        'status',
        'payment_info',
        'notes',
        'receipt_image',
    ];

    protected function casts(): array
    {
        return [
            'points_used' => 'integer',
            'rupiah_amount' => 'integer',
        ];
    }

    public function getReceiptUrlAttribute(): ?string
    {
        return $this->receipt_image ? asset('storage/' . $this->receipt_image) : null;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
