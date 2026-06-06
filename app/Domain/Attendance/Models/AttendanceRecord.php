<?php

namespace App\Domain\Attendance\Models;

use App\Domain\Shared\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceRecord extends Model
{
    use HasFactory;
    use HasUuids;
    use BelongsToCompany;

    protected $fillable = [
        'org_id',
        'employee_id',
        'site_id',
        'attendance_date',
        'override_request_id',
        'check_in_time',
        'check_out_time',
        'check_in_latitude',
        'check_in_longitude',
        'check_out_latitude',
        'check_out_longitude',
        'status',
        'work_hours',
        'overtime_hours',
        'is_manual_override',
        'sync_status',
        'idempotency_key',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
        'check_in_latitude' => 'decimal:8',
        'check_in_longitude' => 'decimal:8',
        'check_out_latitude' => 'decimal:8',
        'check_out_longitude' => 'decimal:8',
        'work_hours' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'is_manual_override' => 'boolean',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(\App\Domain\Employee\Models\Employee::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(\App\Domain\Site\Models\Site::class);
    }
}
