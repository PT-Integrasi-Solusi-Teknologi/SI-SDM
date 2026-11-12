<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerformanceReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'period_id',
        'score',
        'rating',
        'evaluator_name',
        'achievements',
        'feedback',
        'review_date',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'decimal:2',
            'review_date' => 'date',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(PerformancePeriod::class, 'period_id');
    }
}
