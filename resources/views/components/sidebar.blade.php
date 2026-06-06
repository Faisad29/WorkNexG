{{-- Reusable Sidebar Component --}}
@php $role = WN::user()['role'] ?? (auth()->user()?->role ?? 'employee'); @endphp
@php
  // Helper to check active state
  function navActive(string ...$patterns): string {
    foreach ($patterns as $pattern) {
      if (request()->is(ltrim($pattern, '/'))) return 'active';
    }
    return '';
  }
@endphp

<div class="sidebar-overlay" id="sidebarOverlay"></div>
<aside class="sidebar" id="sidebar">

    {{-- MAIN --}}
    <div class="sidebar-section">Main</div>
    <a href="{{ route('dashboard') }}" class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
        <span class="nav-icon">🏠</span> Dashboard
    </a>

    {{-- WORKFORCE (Admin, HR) --}}
    @if(in_array($role, ['admin', 'hr']))
    <div class="sidebar-section">Workforce</div>
    <a href="{{ route('employees.index') }}" class="nav-item {{ request()->is('employees*') ? 'active' : '' }}">
        <span class="nav-icon">👥</span> Employees
    </a>
    <a href="{{ route('sites.index') }}" class="nav-item {{ request()->is('sites*') ? 'active' : '' }}">
        <span class="nav-icon">📍</span> Sites
    </a>
    @endif

    {{-- OPERATIONS (Admin, HR, Supervisor) --}}
    @if(in_array($role, ['admin', 'hr', 'supervisor']))
    <div class="sidebar-section">Operations</div>
    <a href="{{ route('attendance.index') }}" class="nav-item {{ request()->is('attendance*') ? 'active' : '' }}">
        <span class="nav-icon">🕐</span> Attendance
    </a>
    <a href="{{ route('leave.index') }}" class="nav-item {{ request()->is('leave*') ? 'active' : '' }}">
        <span class="nav-icon">🌴</span> Leave
    </a>
    @endif

    {{-- FINANCE (Admin, Accountant) --}}
    @if(in_array($role, ['admin', 'accountant']))
    <div class="sidebar-section">Finance</div>
    <a href="{{ route('payroll.index') }}" class="nav-item {{ request()->is('payroll*') ? 'active' : '' }}">
        <span class="nav-icon">💰</span> Payroll
    </a>
    @endif

    {{-- COMPLIANCE (Admin, HR) --}}
    @if(in_array($role, ['admin', 'hr']))
    <div class="sidebar-section">Compliance</div>
    <a href="{{ route('compliance.index') }}" class="nav-item {{ request()->is('compliance*') ? 'active' : '' }}">
        <span class="nav-icon">🛡️</span> Compliance
    </a>
    @endif

    {{-- EMPLOYEE SELF-SERVICE --}}
    @if($role === 'employee')
    <div class="sidebar-section">My Records</div>
    <a href="{{ route('my.attendance') }}" class="nav-item {{ request()->is('my-attendance*') ? 'active' : '' }}">
        <span class="nav-icon">🕐</span> My Attendance
    </a>
    <a href="{{ route('my.payroll') }}" class="nav-item {{ request()->is('my-payroll*') ? 'active' : '' }}">
        <span class="nav-icon">💰</span> My Payroll
    </a>
    <a href="{{ route('my.documents') }}" class="nav-item {{ request()->is('my-documents*') ? 'active' : '' }}">
        <span class="nav-icon">📄</span> My Documents
    </a>
    @endif

    {{-- ADMIN ONLY --}}
    @if($role === 'admin')
    <div class="sidebar-section">Admin</div>
    <a href="{{ route('reports.index') }}" class="nav-item {{ request()->is('reports*') ? 'active' : '' }}">
        <span class="nav-icon">📊</span> Reports
    </a>
    <a href="{{ route('notifications.index') }}" class="nav-item {{ request()->is('notifications*') ? 'active' : '' }}">
        <span class="nav-icon">🔔</span> Notifications
    </a>
    <a href="{{ route('settings.index') }}" class="nav-item {{ request()->is('settings*') ? 'active' : '' }}">
        <span class="nav-icon">⚙️</span> Settings
    </a>
    @endif

    {{-- SIDEBAR FOOTER --}}
    <div class="sidebar-footer">
        <a href="#" class="nav-item" onclick="doLogout(); return false;">
            <span class="nav-icon">🚪</span>
            <span>Logout</span>
        </a>
    </div>
</aside>
