<?php

namespace Tests\Unit;

use App\Domain\Company\Models\Company;
use App\Domain\Employee\Models\Employee;
use App\Domain\Payroll\Services\PayrollCalculationService;
use Tests\TestCase;

class PayrollCalculationServiceTest extends TestCase
{
    public function test_monthly_salary_is_calculated_deterministically(): void
    {
        $employee = Employee::factory()->make(['salary_type' => 'monthly', 'base_salary' => 6000]);

        $result = app(PayrollCalculationService::class)->calculate($employee, [
            'attendance_days' => 22,
            'absent_days' => 0,
            'overtime_amount' => 300,
            'deductions' => 100,
            'bonuses' => 200,
        ]);

        $this->assertSame(6400.0, $result['net_salary']);
    }
}
