<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeCompetency extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'competency_id',
        'level',
        'assessed_date',
        'assessor_name',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'assessed_date' => 'date',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function competency(): BelongsTo
    {
        return $this->belongsTo(Competency::class);
    }
}
