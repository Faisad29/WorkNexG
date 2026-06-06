<?php

namespace App\Domain\Attendance\Http\Controllers;

use App\Domain\Attendance\Http\Requests\CheckInRequest;
use App\Domain\Attendance\Http\Requests\CheckOutRequest;
use App\Domain\Attendance\Http\Requests\StoreOverrideRequest;
use App\Domain\Attendance\Models\AttendanceOverrideRequest;
use App\Domain\Attendance\Models\AttendanceRecord;
use App\Domain\Attendance\Services\AttendanceService;
use App\Domain\Attendance\Services\AttendanceOverrideService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttendanceController
{
    public function __construct(
        private readonly AttendanceService $service,
        private readonly AttendanceOverrideService $overrideService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $query = AttendanceRecord::query()->with('employee', 'site')->latest('attendance_date');

        if ($request->has('date')) {
            $query->whereDate('attendance_date', $request->get('date'));
        }
        if ($request->has('month')) {
            [$year, $month] = explode('-', $request->get('month'));
            $query->whereYear('attendance_date', $year)->whereMonth('attendance_date', $month);
        }
        if ($request->has('employee_id')) {
            $query->where('employee_id', $request->get('employee_id'));
        }
        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        return response()->json(['data' => $query->paginate(25)]);
    }

    public function checkIn(CheckInRequest $request): JsonResponse
    {
        $record = $this->service->checkIn($request->validated());
        return response()->json(['data' => $record], 201);
    }

    public function checkOut(CheckOutRequest $request): JsonResponse
    {
        $record = $this->service->checkOut($request->validated());
        return response()->json(['data' => $record]);
    }

    public function requestOverride(StoreOverrideRequest $request): JsonResponse
    {
        $override = $this->overrideService->request($request->validated());
        return response()->json(['data' => $override], 201);
    }

    public function listOverrides(Request $request): JsonResponse
    {
        $overrides = AttendanceOverrideRequest::query()
            ->with('employee')
            ->when($request->has('status'), fn ($q) => $q->where('status', $request->get('status')))
            ->latest()
            ->paginate(25);
        return response()->json(['data' => $overrides]);
    }

    public function approveOverride(string $id): JsonResponse
    {
        $override = $this->overrideService->approve($id);
        return response()->json(['data' => $override]);
    }

    public function rejectOverride(string $id): JsonResponse
    {
        $override = $this->overrideService->reject($id);
        return response()->json(['data' => $override]);
    }
}
