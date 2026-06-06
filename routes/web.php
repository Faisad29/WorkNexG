<?php

use Illuminate\Support\Facades\Route;

// ── Public landing page ──────────────────────────────────────────────
Route::get('/', function () {
    return view('index');
});

Route::get('/health', function () {
    return response()->json(['status' => 'ok', 'service' => 'WorkNexG']);
});

// ── Auth pages ───────────────────────────────────────────────────────
Route::get('/login', fn () => view('auth.login'))->name('login');
Route::match(['get', 'post'], '/logout', fn () => view('auth.logout'))->name('logout');
Route::get('/register', fn () => view('auth.register'))->name('register');

// ── Authenticated SPA shell pages ─────────────────────────────────────
// These just serve Blade shells; all data is loaded via JS → API
Route::middleware('auth:sanctum')->group(function () {
    // Dashboard
    Route::get('/dashboard', fn () => view('dashboard.index'))->name('dashboard');

    // Admin / HR routes
    Route::get('/employees', fn () => view('admin.employees'))->name('employees.index');
    Route::get('/sites', fn () => view('admin.sites'))->name('sites.index');
    Route::get('/payroll', fn () => view('admin.payroll'))->name('payroll.index');
    Route::get('/compliance', fn () => view('admin.compliance'))->name('compliance.index');
    Route::get('/notifications', fn () => view('admin.notifications'))->name('notifications.index');
    Route::get('/reports', fn () => view('admin.reports'))->name('reports.index');
    Route::get('/settings', fn () => view('admin.settings'))->name('settings.index');
    Route::get('/leave', fn () => view('admin.leave'))->name('leave.index');
    Route::get('/attendance', fn () => view('admin.attendance'))->name('attendance.index');

    // Employee self-service routes
    Route::get('/my-attendance', fn () => view('employee.attendance'))->name('my.attendance');
    Route::get('/my-payroll', fn () => view('employee.payroll'))->name('my.payroll');
    Route::get('/my-documents', fn () => view('employee.documents'))->name('my.documents');
});
