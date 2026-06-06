<?php

namespace App\Domain\Leave\Http\Controllers;

use App\Domain\Leave\Http\Requests\StoreLeaveRequest;
use App\Domain\Leave\Models\LeaveRequest;
use App\Domain\Leave\Services\LeaveService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeaveController
{
    public function __construct(private readonly LeaveService $service)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $query = LeaveRequest::query()->with('employee')->latest();
        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }
        return response()->json(['data' => $query->paginate(25)]);
    }

    public function store(StoreLeaveRequest $request): JsonResponse
    {
        $leave = $this->service->requestLeave($request->validated());
        return response()->json(['data' => $leave], 201);
    }

    public function approve(string $id): JsonResponse
    {
        $leave = LeaveRequest::findOrFail($id);
        $leave->update(['status' => 'approved', 'approved_by' => auth()->id()]);
        return response()->json(['data' => $leave]);
    }

    public function reject(string $id): JsonResponse
    {
        $leave = LeaveRequest::findOrFail($id);
        $leave->update(['status' => 'rejected']);
        return response()->json(['data' => $leave]);
    }
}
