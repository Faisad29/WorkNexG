<?php

namespace App\Domain\Payroll\Models;

use App\Domain\Shared\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollItem extends Model
{
    use HasFactory;
    use HasUuids;
    use BelongsToCompany;

    protected $fillable = [
        'org_id',
        'payroll_id',
        'employee_id',
        'base_salary',
        'overtime_amount',
        'deductions',
        'bonuses',
        'net_salary',
        'attendance_days',
        'absent_days',
        'status',
        'attendance_snapshot',
        'calculation_metadata',
    ];

    protected $casts = [
        'base_salary' => 'decimal:2',
        'overtime_amount' => 'decimal:2',
        'deductions' => 'decimal:2',
        'bonuses' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'attendance_snapshot' => 'array',
        'calculation_metadata' => 'array',
    ];

    public function payroll(): BelongsTo
    {
        return $this->belongsTo(Payroll::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(\App\Domain\Employee\Models\Employee::class);
    }
}
