<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Competency extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'code',
        'name',
        'description',
        'target_level',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(CompetencyCategory::class, 'category_id');
    }

    public function employeeCompetencies(): HasMany
    {
        return $this->hasMany(EmployeeCompetency::class);
    }
}
