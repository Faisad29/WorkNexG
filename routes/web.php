<?php

use Illuminate\Support\Facades\Route;

// ── Public ────────────────────────────────────────────────────
Route::get('/', fn () => view('index'))->name('home');
Route::get('/health', fn () => response()->json(['status' => 'ok', 'service' => 'WorkNexG']));

// ── Auth pages ────────────────────────────────────────────────
Route::get('/login',    fn () => view('auth.login'))->name('login');
Route::get('/register', fn () => view('auth.register'))->name('register');

// ── Authenticated pages ───────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Dashboard
    Route::get('/dashboard', fn () => view('dashboard.index'))->name('dashboard');

    // Workforce
    Route::get('/employees', fn () => view('admin.employees'))->name('employees.index');
    Route::get('/sites',     fn () => view('admin.sites'))->name('sites.index');

    // Operations
    Route::get('/attendance', fn () => view('admin.attendance'))->name('attendance.index');
    Route::get('/leave',      fn () => view('admin.leave'))->name('leave.index');

    // Finance
    Route::get('/payroll', fn () => view('admin.payroll'))->name('payroll.index');

    // Compliance
    Route::get('/compliance', fn () => view('admin.compliance'))->name('compliance.index');

    // Admin
    Route::get('/reports',       fn () => view('admin.reports'))->name('reports.index');
    Route::get('/notifications', fn () => view('admin.notifications'))->name('notifications.index');
    Route::get('/settings',      fn () => view('admin.settings'))->name('settings.index');

    // Employee self-service
    Route::get('/my-attendance', fn () => view('employee.attendance'))->name('my.attendance');
    Route::get('/my-payroll',    fn () => view('employee.payroll'))->name('my.payroll');
    Route::get('/my-documents',  fn () => view('employee.documents'))->name('my.documents');
});
