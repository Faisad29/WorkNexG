<?php

namespace App\Domain\Employee\Http\Controllers;

use App\Domain\Employee\Http\Requests\StoreEmployeeRequest;
use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Services\EmployeeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeeController
{
    public function __construct(private readonly EmployeeService $service)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $query = Employee::query()->with('site')->latest();

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search): void {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('employee_code', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        return response()->json(['data' => $query->paginate(25)]);
    }

    public function store(StoreEmployeeRequest $request): JsonResponse
    {
        $employee = $this->service->create($request->validated());
        return response()->json(['data' => $employee], 201);
    }

    public function show(Employee $employee): JsonResponse
    {
        return response()->json(['data' => $employee->load('site', 'documents', 'attendanceRecords')]);
    }

    public function update(Request $request, Employee $employee): JsonResponse
    {
        $validated = $request->validate([
            'full_name' => ['sometimes', 'string', 'max:255'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:30'],
            'email' => ['sometimes', 'nullable', 'email'],
            'job_title' => ['sometimes', 'nullable', 'string', 'max:100'],
            'nationality' => ['sometimes', 'nullable', 'string', 'max:100'],
            'salary_type' => ['sometimes', 'in:monthly,daily,hourly,project_based'],
            'base_salary' => ['sometimes', 'numeric', 'min:0'],
            'status' => ['sometimes', 'in:active,inactive,suspended'],
            'contract_end_date' => ['sometimes', 'nullable', 'date'],
        ]);
        $employee->update($validated);
        return response()->json(['data' => $employee]);
    }

    public function destroy(Employee $employee): JsonResponse
    {
        $employee->update(['status' => 'inactive']);
        return response()->json(['message' => 'Employee deactivated.']);
    }
}
