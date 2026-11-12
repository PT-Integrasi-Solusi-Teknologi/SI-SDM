<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_code',
        'name',
        'nik',
        'nip',
        'gender',
        'birth_place',
        'birth_date',
        'email',
        'phone',
        'address',
        'avatar',
        'employment_type_id',
        'employment_status_id',
        'department_id',
        'job_position_id',
        'work_model',
        'join_date',
        'contract_end_date',
        'notes',
        'base_salary',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'join_date' => 'date',
            'contract_end_date' => 'date',
            'is_active' => 'boolean',
            'base_salary' => 'decimal:2',
        ];
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function jobPosition(): BelongsTo
    {
        return $this->belongsTo(JobPosition::class);
    }

    public function employmentType(): BelongsTo
    {
        return $this->belongsTo(EmploymentType::class);
    }

    public function employmentStatus(): BelongsTo
    {
        return $this->belongsTo(EmploymentStatus::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(EmployeeAssignment::class)->orderBy('start_date', 'desc');
    }

    public function competencies(): HasMany
    {
        return $this->hasMany(EmployeeCompetency::class);
    }

    public function certifications(): HasMany
    {
        return $this->hasMany(Certification::class)->orderBy('issue_date', 'desc');
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class)->orderBy('created_at', 'desc');
    }

    public function leaveBalances(): HasMany
    {
        return $this->hasMany(LeaveBalance::class);
    }

    public function attendanceRecords(): HasMany
    {
        return $this->hasMany(AttendanceRecord::class)->orderBy('date', 'desc');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(EmployeeDocument::class)->orderBy('created_at', 'desc');
    }

    public function performanceReviews(): HasMany
    {
        return $this->hasMany(PerformanceReview::class)->orderBy('created_at', 'desc');
    }

    public function getInitialsAttribute(): string
    {
        $words = explode(' ', trim($this->name));
        $initials = '';
        foreach (array_slice($words, 0, 2) as $word) {
            $initials .= mb_substr($word, 0, 1);
        }

        return strtoupper($initials ?: 'SD');
    }

    public function getLatestPerformanceScoreAttribute(): ?float
    {
        $review = $this->performanceReviews()->latest()->first();

        return $review ? (float) $review->score : null;
    }
}
