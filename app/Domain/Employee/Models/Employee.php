<?php

namespace App\Domain\Employee\Models;

use App\Domain\Shared\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;
    use HasUuids;
    use BelongsToCompany;

    protected $fillable = [
        'org_id',
        'site_id',
        'employee_code',
        'full_name',
        'phone',
        'email',
        'nationality',
        'job_title',
        'role',
        'salary_type',
        'base_salary',
        'join_date',
        'contract_end_date',
        'status',
    ];

    protected $casts = [
        'base_salary' => 'decimal:2',
        'join_date' => 'date',
        'contract_end_date' => 'date',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(\App\Domain\Organization\Models\Organization::class, 'org_id');
    }

    public function company(): BelongsTo
    {
        return $this->organization();
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(\App\Domain\Site\Models\Site::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(\App\Domain\Compliance\Models\Document::class);
    }

    public function attendanceRecords(): HasMany
    {
        return $this->hasMany(\App\Domain\Attendance\Models\AttendanceRecord::class);
    }
}
