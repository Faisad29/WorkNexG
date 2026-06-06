<?php

namespace App\Domain\Attendance\Services;

use App\Domain\Attendance\Models\AttendanceRecord;
use App\Domain\Attendance\Events\AttendanceMarked;
use App\Domain\Site\Models\Site;
use App\Domain\Shared\TenantContext;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    public function __construct(
        private readonly TenantContext $tenantContext,
        private readonly GpsValidationService $gpsValidationService,
    )
    {
    }

    public function checkIn(array $data): AttendanceRecord
    {
        return DB::transaction(function () use ($data): AttendanceRecord {
            $site = Site::query()->findOrFail($data['site_id']);

            $existing = AttendanceRecord::query()
                ->where('employee_id', $data['employee_id'])
                ->whereDate('attendance_date', $data['attendance_date'])
                ->first();

            if ($existing !== null && ! empty($data['idempotency_key']) && $existing->idempotency_key === $data['idempotency_key']) {
                return $existing;
            }

            $approvedOverride = ! empty($data['override_request_id'])
                && app(AttendanceOverrideService::class)->isApprovedOverride(
                    (string) $data['override_request_id'],
                    (string) $data['employee_id'],
                    (string) $data['attendance_date'],
                );

            if (! $approvedOverride) {
                $allowed = $this->gpsValidationService->isWithinRadius(
                    $site,
                    (float) $data['check_in_latitude'],
                    (float) $data['check_in_longitude'],
                );

                if (! $allowed) {
                    abort(422, 'Employee is outside the allowed site radius.');
                }
            }

            $data['org_id'] = $this->tenantContext->orgId();
            $data['status'] = $data['status'] ?? 'present';
            $data['sync_status'] = $data['sync_status'] ?? 'synced';
            $data['is_manual_override'] = $approvedOverride;
            $data['idempotency_key'] = $data['idempotency_key'] ?? null;
            $data['override_request_id'] = $data['override_request_id'] ?? null;

            $record = AttendanceRecord::create($data);

            AttendanceMarked::dispatch($record->id, $record->org_id);

            return $record;
        });
    }

    public function checkOut(array $data): AttendanceRecord
    {
        return DB::transaction(function () use ($data): AttendanceRecord {
            $record = AttendanceRecord::query()
                ->where('employee_id', $data['employee_id'])
                ->whereDate('attendance_date', $data['attendance_date'])
                ->firstOrFail();

            if (! empty($data['idempotency_key']) && $record->idempotency_key === $data['idempotency_key']) {
                return $record;
            }

            $record->fill([
                'check_out_time' => $data['check_out_time'],
                'check_out_latitude' => $data['check_out_latitude'],
                'check_out_longitude' => $data['check_out_longitude'],
                'work_hours' => $data['work_hours'] ?? $record->work_hours,
                'overtime_hours' => $data['overtime_hours'] ?? $record->overtime_hours,
                'status' => $data['status'] ?? $record->status,
                'idempotency_key' => $data['idempotency_key'] ?? $record->idempotency_key,
            ]);

            $record->save();

            AttendanceMarked::dispatch($record->id, $record->org_id);

            return $record;
        });
    }
}
