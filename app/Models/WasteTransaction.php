<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WasteTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'verified_by',
        'category_id',
        'grade_id',
        'quantity_or_weight',
        'earned_points',
        'status',
        'notes',
        'image_path',
    ];

    protected function casts(): array
    {
        return [
            'quantity_or_weight' => 'integer',
            'earned_points' => 'integer',
        ];
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : null;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(WasteCategory::class, 'category_id');
    }

    public function grade(): BelongsTo
    {
        return $this->belongsTo(WasteGrade::class, 'grade_id');
    }
}
