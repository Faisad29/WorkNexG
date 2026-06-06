<?php

namespace App\Domain\Attendance\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AttendanceMarked
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly string $attendanceRecordId,
        public readonly string $companyId,
    ) {
    }
}
