<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmploymentStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'badge_color',
    ];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
