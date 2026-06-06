<?php

namespace App\Domain\Attendance\Models;

use App\Domain\Shared\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceOverrideRequest extends Model
{
    use HasFactory;
    use HasUuids;
    use BelongsToCompany;

    protected $fillable = [
        'org_id',
        'employee_id',
        'attendance_date',
        'reason',
        'status',
        'requested_by',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'reviewed_at' => 'datetime',
    ];
}
