<?php

namespace App\Domain\Employee\DTOs;

final readonly class EmployeeData
{
    public function __construct(
        public string $companyId,
        public string $employeeCode,
        public string $fullName,
        public ?string $phone,
        public ?string $email,
        public ?string $nationality,
        public ?string $jobTitle,
        public ?string $role,
        public string $salaryType,
        public string|float $baseSalary,
        public ?string $siteId,
        public ?string $joinDate,
        public ?string $contractEndDate,
        public string $status,
    ) {
    }
}
