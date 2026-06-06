<?php

namespace App\Domain\Attendance\Listeners;

use App\Domain\Attendance\Events\AttendanceMarked;
use App\Domain\Attendance\Models\AttendanceRecord;
use Carbon\Carbon;

class RecomputeAttendanceMetrics
{
    public function handle(AttendanceMarked $event): void
    {
        $record = AttendanceRecord::query()->find($event->attendanceRecordId);
        if ($record === null || $record->check_out_time === null || $record->check_in_time === null) {
            return;
        }

        $checkIn = Carbon::parse($record->check_in_time);
        $checkOut = Carbon::parse($record->check_out_time);
        $workHours = round($checkOut->diffInMinutes($checkIn) / 60, 2);
        $overtimeHours = max(0, round($workHours - 8, 2));

        // Determine status
        $shiftStart = $checkIn->copy()->setTime(8, 0);
        $status = $record->status;
        if ($status === 'present' && $checkIn->greaterThan($shiftStart->addMinutes(15))) {
            $status = 'late';
        }

        $record->forceFill([
            'work_hours' => $workHours,
            'overtime_hours' => $overtimeHours,
            'status' => $status,
        ])->saveQuietly();
    }
}
