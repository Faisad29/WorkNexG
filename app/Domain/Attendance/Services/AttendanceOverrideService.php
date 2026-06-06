<?php

namespace App\Domain\Attendance\Services;

use App\Domain\Attendance\Models\AttendanceOverrideRequest;
use App\Domain\Shared\TenantContext;
use Illuminate\Support\Facades\DB;

class AttendanceOverrideService
{
    public function __construct(private readonly TenantContext $tenantContext)
    {
    }

    public function request(array $data): AttendanceOverrideRequest
    {
        return DB::transaction(function () use ($data): AttendanceOverrideRequest {
            return AttendanceOverrideRequest::create([
                'org_id' => $this->tenantContext->orgId(),
                'employee_id' => $data['employee_id'],
                'attendance_date' => $data['attendance_date'],
                'reason' => $data['reason'],
                'status' => 'override_request',
                'requested_by' => auth()->id(),
            ]);
        });
    }

    public function approve(string $id): AttendanceOverrideRequest
    {
        $override = AttendanceOverrideRequest::query()->findOrFail($id);
        $override->update([
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return $override;
    }

    public function reject(string $id): AttendanceOverrideRequest
    {
        $override = AttendanceOverrideRequest::query()->findOrFail($id);
        $override->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return $override;
    }

    public function isApprovedOverride(string $overrideId, string $employeeId, string $attendanceDate): bool
    {
        return AttendanceOverrideRequest::query()
            ->where('id', $overrideId)
            ->where('employee_id', $employeeId)
            ->whereDate('attendance_date', $attendanceDate)
            ->where('status', 'approved')
            ->exists();
    }
}
