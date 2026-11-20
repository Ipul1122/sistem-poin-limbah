<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WasteCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'unit_type',
        'unit_divisor',
        'base_point',
    ];

    protected function casts(): array
    {
        return [
            'unit_divisor' => 'integer',
            'base_point' => 'integer',
        ];
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(WasteTransaction::class, 'category_id');
    }
}
