<?php

namespace App\Domain\Payroll\Http\Controllers;

use App\Domain\Payroll\Http\Requests\GeneratePayrollRequest;
use App\Domain\Payroll\Models\Payroll;
use App\Domain\Payroll\Models\PayrollItem;
use App\Domain\Payroll\Services\PayrollService;
use App\Domain\Shared\TenantContext;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PayrollController
{
    public function __construct(
        private readonly PayrollService $service,
        private readonly TenantContext $tenantContext,
    ) {
    }

    public function index(): JsonResponse
    {
        $payrolls = Payroll::query()->latest('year')->latest('month')->paginate(24);
        return response()->json(['data' => $payrolls]);
    }

    public function generate(GeneratePayrollRequest $request): JsonResponse
    {
        $payroll = $this->service->generate($request->validated());
        return response()->json(['data' => $payroll], 201);
    }

    public function myItems(Request $request): JsonResponse
    {
        // Returns payroll items for the authenticated user's linked employee (by email match)
        $user = $request->user();
        $items = PayrollItem::query()
            ->with('payroll')
            ->whereHas('employee', fn ($q) => $q->where('email', $user->email))
            ->latest('created_at')
            ->paginate(24);
        return response()->json(['data' => $items]);
    }

    public function approve(Payroll $payroll): JsonResponse
    {
        return response()->json(['data' => $this->service->approve($payroll)]);
    }

    public function lock(Payroll $payroll): JsonResponse
    {
        return response()->json(['data' => $this->service->lock($payroll)]);
    }

    public function pay(Payroll $payroll): JsonResponse
    {
        return response()->json(['data' => $this->service->markPaid($payroll)]);
    }
}
