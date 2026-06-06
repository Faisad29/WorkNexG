<?php

namespace App\Domain\Payroll\Services;

use App\Domain\Employee\Models\Employee;
use App\Domain\Attendance\Models\AttendanceRecord;
use App\Domain\Payroll\Events\PayrollGenerated;
use App\Domain\Payroll\Models\Payroll;
use App\Domain\Payroll\Models\PayrollItem;
use App\Domain\Shared\TenantContext;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PayrollService
{
    public function __construct(
        private readonly TenantContext $tenantContext,
        private readonly PayrollCalculationService $calculationService,
    ) {
    }

    public function generate(array $data): Payroll
    {
        return DB::transaction(function () use ($data): Payroll {
            $companyId = $this->tenantContext->orgId();
            $payroll = Payroll::query()->firstOrNew([
                'org_id' => $companyId,
                'month' => $data['month'],
                'year' => $data['year'],
            ]);

            if (in_array($payroll->status, ['approved', 'locked', 'paid'], true)) {
                abort(409, 'Payroll is locked and cannot be recalculated.');
            }

            $employees = Employee::query()->where('status', 'active')->get();
            $periodStart = Carbon::createFromDate($data['year'], $data['month'], 1)->startOfMonth();
            $periodEnd = (clone $periodStart)->endOfMonth();

            if ($payroll->exists) {
                PayrollItem::query()->where('payroll_id', $payroll->id)->delete();
            }

            $payroll->fill([
                'org_id' => $companyId,
                'month' => $data['month'],
                'year' => $data['year'],
                'total_employees' => $employees->count(),
                'total_amount' => 0,
                'status' => 'generated',
                'generated_at' => now(),
            ])->save();

            $totalAmountCents = 0;
            $snapshots = [];

            foreach ($employees as $employee) {
                $attendanceQuery = AttendanceRecord::query()
                    ->where('employee_id', $employee->id)
                    ->whereBetween('attendance_date', [$periodStart->toDateString(), $periodEnd->toDateString()]);

                $attendanceDays = (clone $attendanceQuery)
                    ->whereIn('status', ['present', 'late', 'half_day'])
                    ->count();

                $absentDays = (clone $attendanceQuery)
                    ->where('status', 'absent')
                    ->count();

                $overtimeHours = (float) (clone $attendanceQuery)->sum('overtime_hours');
                $missingCheckoutCount = (clone $attendanceQuery)->whereNull('check_out_time')->count();
                $lateCount = (clone $attendanceQuery)->where('status', 'late')->count();
                $presentHours = (float) (clone $attendanceQuery)->sum('work_hours');

                $dailyRate = number_format(((float) $employee->base_salary) / 30, 2, '.', '');
                $hourlyRate = number_format(((float) $employee->base_salary) / 30 / 8, 2, '.', '');

                $calculated = $this->calculationService->calculate($employee, [
                    'attendance_days' => $attendanceDays,
                    'absent_days' => $absentDays,
                    'overtime_amount' => number_format($overtimeHours * (float) $hourlyRate, 2, '.', ''),
                    'deductions' => '0.00',
                    'bonuses' => '0.00',
                    'daily_rate' => $dailyRate,
                    'hourly_rate' => $hourlyRate,
                    'total_hours' => $presentHours,
                    'project_amount' => (float) $employee->base_salary,
                    'penalties' => number_format(($lateCount * 0.01) + ($missingCheckoutCount * 0.01), 2, '.', ''),
                ]);

                $totalAmountCents += (int) round(((float) $calculated['net_salary']) * 100);

                $attendanceSnapshot = $attendanceQuery->get()->map(fn (AttendanceRecord $attendanceRecord): array => [
                    'id' => $attendanceRecord->id,
                    'attendance_date' => $attendanceRecord->attendance_date?->toDateString(),
                    'status' => $attendanceRecord->status,
                    'work_hours' => (string) $attendanceRecord->work_hours,
                    'overtime_hours' => (string) $attendanceRecord->overtime_hours,
                    'check_in_time' => optional($attendanceRecord->check_in_time)->toIso8601String(),
                    'check_out_time' => optional($attendanceRecord->check_out_time)->toIso8601String(),
                ])->all();

                PayrollItem::create([
                    'org_id' => $companyId,
                    'payroll_id' => $payroll->id,
                    'employee_id' => $employee->id,
                    ...$calculated,
                    'status' => 'draft',
                    'attendance_snapshot' => $attendanceSnapshot,
                    'calculation_metadata' => $calculated['calculation_metadata'],
                ]);

                $snapshots[] = [
                    'employee_id' => $employee->id,
                    'attendance' => $attendanceSnapshot,
                    'calculation' => $calculated['calculation_metadata'],
                ];
            }

            $payroll->update([
                'total_amount' => number_format($totalAmountCents / 100, 2, '.', ''),
                'attendance_snapshot' => $snapshots,
                'calculation_metadata' => [
                    'period_start' => $periodStart->toDateString(),
                    'period_end' => $periodEnd->toDateString(),
                    'generated_employee_count' => $employees->count(),
                ],
            ]);

            PayrollGenerated::dispatch($payroll->id, $companyId);

            return $payroll;
        });
    }

    public function approve(Payroll $payroll): Payroll
    {
        if (in_array($payroll->status, ['locked', 'paid'], true)) {
            abort(409, 'Payroll is already locked.');
        }

        $payroll->forceFill([
            'status' => 'approved',
            'approved_at' => now(),
        ])->save();

        return $payroll;
    }

    public function lock(Payroll $payroll): Payroll
    {
        if ($payroll->status !== 'approved') {
            abort(409, 'Payroll must be approved before locking.');
        }

        $payroll->forceFill([
            'status' => 'locked',
            'locked_at' => now(),
        ])->save();

        return $payroll;
    }

    public function markPaid(Payroll $payroll): Payroll
    {
        if ($payroll->status !== 'locked') {
            abort(409, 'Payroll must be locked before payment.');
        }

        $payroll->forceFill([
            'status' => 'paid',
            'paid_at' => now(),
        ])->save();

        return $payroll;
    }
}
