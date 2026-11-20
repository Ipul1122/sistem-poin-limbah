<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WasteGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'grade_code',
        'label',
        'bonus_point',
    ];

    protected function casts(): array
    {
        return [
            'bonus_point' => 'integer',
        ];
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(WasteTransaction::class, 'grade_id');
    }
}
