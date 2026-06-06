<?php

namespace App\Domain\Payroll\Models;

use App\Domain\Shared\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payroll extends Model
{
    use HasFactory;
    use HasUuids;
    use BelongsToCompany;

    protected $fillable = [
        'org_id',
        'month',
        'year',
        'total_employees',
        'total_amount',
        'status',
        'generated_at',
        'approved_at',
        'locked_at',
        'paid_at',
        'attendance_snapshot',
        'calculation_metadata',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'generated_at' => 'datetime',
        'approved_at' => 'datetime',
        'locked_at' => 'datetime',
        'paid_at' => 'datetime',
        'attendance_snapshot' => 'array',
        'calculation_metadata' => 'array',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(PayrollItem::class);
    }

    protected static function booted(): void
    {
        static::updating(function (Payroll $payroll): void {
            if (in_array($payroll->getOriginal('status'), ['locked', 'paid'], true)) {
                abort(409, 'Locked payroll cannot be modified.');
            }
        });
    }
}
