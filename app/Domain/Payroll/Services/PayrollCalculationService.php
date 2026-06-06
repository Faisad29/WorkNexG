<?php

namespace App\Domain\Payroll\Services;

use App\Domain\Employee\Models\Employee;

class PayrollCalculationService
{
    public function calculate(Employee $employee, array $inputs): array
    {
        $baseSalaryCents = $this->toCents((string) $employee->base_salary);
        $overtimeAmountCents = $this->toCents((string) ($inputs['overtime_amount'] ?? '0'));
        $deductionsCents = $this->toCents((string) ($inputs['deductions'] ?? '0'));
        $bonusesCents = $this->toCents((string) ($inputs['bonuses'] ?? '0'));
        $attendanceDays = (int) ($inputs['attendance_days'] ?? 0);
        $absentDays = (int) ($inputs['absent_days'] ?? 0);

        $dailyRateCents = $this->toCents((string) ($inputs['daily_rate'] ?? '0'));
        $hourlyRateCents = $this->toCents((string) ($inputs['hourly_rate'] ?? '0'));
        $totalHours = (float) ($inputs['total_hours'] ?? 0);
        $projectAmountCents = $this->toCents((string) ($inputs['project_amount'] ?? (string) $employee->base_salary));
        $penaltiesCents = $this->toCents((string) ($inputs['penalties'] ?? '0'));

        $netSalaryCents = match ($employee->salary_type) {
            'monthly' => $baseSalaryCents + $overtimeAmountCents + $bonusesCents - $deductionsCents,
            'daily' => ($dailyRateCents * $attendanceDays) + $overtimeAmountCents - $deductionsCents,
            'hourly' => (int) round($hourlyRateCents * $totalHours) + $overtimeAmountCents - $deductionsCents,
            'project_based' => $projectAmountCents - $penaltiesCents + $bonusesCents,
            default => $baseSalaryCents,
        };

        return [
            'base_salary' => $this->fromCents($baseSalaryCents),
            'overtime_amount' => $this->fromCents($overtimeAmountCents),
            'deductions' => $this->fromCents($deductionsCents),
            'bonuses' => $this->fromCents($bonusesCents),
            'net_salary' => $this->fromCents(max($netSalaryCents, 0)),
            'attendance_days' => $attendanceDays,
            'absent_days' => $absentDays,
            'calculation_metadata' => [
                'salary_type' => $employee->salary_type,
                'base_salary_cents' => $baseSalaryCents,
                'overtime_amount_cents' => $overtimeAmountCents,
                'deductions_cents' => $deductionsCents,
                'bonuses_cents' => $bonusesCents,
            ],
        ];
    }

    private function toCents(string $amount): int
    {
        return (int) round(((float) $amount) * 100);
    }

    private function fromCents(int $cents): float
    {
        return round($cents / 100, 2);
    }
}
