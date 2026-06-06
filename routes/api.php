<?php

use App\Domain\Auth\Http\Controllers\AuthController;
use App\Domain\Attendance\Http\Controllers\AttendanceController;
use App\Domain\Billing\Http\Controllers\SubscriptionController;
use App\Domain\Compliance\Http\Controllers\DocumentController;
use App\Domain\Employee\Http\Controllers\EmployeeController;
use App\Domain\Leave\Http\Controllers\LeaveController;
use App\Domain\Notification\Http\Controllers\NotificationController;
use App\Domain\Payroll\Http\Controllers\PayrollController;
use App\Domain\Site\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/docs', fn () => response()->json([
    'name' => 'WorkNexG API',
    'version' => '1.0',
    'docs' => '/docs/API.md',
    'auth' => ['type' => 'sanctum', 'login_endpoint' => '/api/auth/login'],
]));

// ── Auth ─────────────────────────────────────────────────────────────
Route::prefix('auth')->group(function (): void {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function (): void {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });
});

// ── Protected tenant routes ───────────────────────────────────────────
Route::middleware(['auth:sanctum', 'tenant', 'permission:workforce.access'])->group(function (): void {

    // Sites
    Route::get('sites', [SiteController::class, 'index']);
    Route::post('sites', [SiteController::class, 'store']);

    // Employees
    Route::get('employees', [EmployeeController::class, 'index']);
    Route::post('employees', [EmployeeController::class, 'store']);
    Route::get('employees/{employee}', [EmployeeController::class, 'show']);
    Route::patch('employees/{employee}', [EmployeeController::class, 'update']);
    Route::delete('employees/{employee}', [EmployeeController::class, 'destroy']);

    // Attendance
    Route::get('attendance', [AttendanceController::class, 'index']);
    Route::post('attendance/check-in', [AttendanceController::class, 'checkIn']);
    Route::post('attendance/check-out', [AttendanceController::class, 'checkOut']);
    Route::get('attendance/overrides', [AttendanceController::class, 'listOverrides']);
    Route::post('attendance/overrides', [AttendanceController::class, 'requestOverride']);
    Route::post('attendance/overrides/{id}/approve', [AttendanceController::class, 'approveOverride']);
    Route::post('attendance/overrides/{id}/reject', [AttendanceController::class, 'rejectOverride']);

    // Payroll
    Route::get('payroll', [PayrollController::class, 'index']);
    Route::post('payroll/generate', [PayrollController::class, 'generate']);
    Route::get('payroll/my-items', [PayrollController::class, 'myItems']);
    Route::post('payroll/{payroll}/approve', [PayrollController::class, 'approve']);
    Route::post('payroll/{payroll}/lock', [PayrollController::class, 'lock']);
    Route::post('payroll/{payroll}/pay', [PayrollController::class, 'pay']);

    // Leave
    Route::get('leave', [LeaveController::class, 'index']);
    Route::post('leave', [LeaveController::class, 'store']);
    Route::post('leave/{id}/approve', [LeaveController::class, 'approve']);
    Route::post('leave/{id}/reject', [LeaveController::class, 'reject']);

    // Documents / Compliance
    Route::get('documents', [DocumentController::class, 'index']);
    Route::post('documents', [DocumentController::class, 'store']);

    // Subscriptions
    Route::get('subscriptions', [SubscriptionController::class, 'index']);
    Route::post('subscriptions', [SubscriptionController::class, 'store']);

    // Notifications
    Route::post('notifications/send', [NotificationController::class, 'send']);
});
