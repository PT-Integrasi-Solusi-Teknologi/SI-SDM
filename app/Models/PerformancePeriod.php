<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PerformancePeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'year',
        'period_type',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(PerformanceReview::class, 'period_id');
    }
}
