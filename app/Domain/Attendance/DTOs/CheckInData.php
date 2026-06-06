<?php

namespace App\Domain\Attendance\DTOs;

final readonly class CheckInData
{
    public function __construct(
        public string $companyId,
        public string $employeeId,
        public string $siteId,
        public string $attendanceDate,
        public float $latitude,
        public float $longitude,
        public ?string $deviceId,
        public ?string $source,
        public bool $manualOverride,
    ) {
    }
}
