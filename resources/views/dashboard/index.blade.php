@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <h1>Dashboard</h1>
    <p>Welcome back, <span id="userName">—</span></p>
</div>

<div class="stats-grid" id="statsGrid">
    <div class="stat-card"><div class="stat-label">Total Employees</div><div class="stat-value" id="statEmployees">—</div><div class="stat-note">Active workforce</div></div>
    <div class="stat-card"><div class="stat-label">Present Today</div><div class="stat-value" id="statPresent">—</div><div class="stat-note">Checked in today</div></div>
    <div class="stat-card"><div class="stat-label">Pending Leave</div><div class="stat-value" id="statLeave">—</div><div class="stat-note">Awaiting approval</div></div>
    <div class="stat-card"><div class="stat-label">Expiring Docs</div><div class="stat-value" id="statDocs">—</div><div class="stat-note">Within 30 days</div></div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
    <div class="card">
        <div class="card-title">Recent Attendance</div>
        <div class="table-wrap">
            <table id="attendanceTable">
                <thead><tr><th>Employee</th><th>Date</th><th>Status</th></tr></thead>
                <tbody><tr><td colspan="3" style="text-align:center;color:var(--muted)">Loading...</td></tr></tbody>
            </table>
        </div>
    </div>
    <div class="card">
        <div class="card-title">Quick Actions</div>
        <div style="display:flex;flex-direction:column;gap:10px">
            <a href="/employees" class="btn btn-primary" id="actionEmployees">👥 Manage Employees</a>
            <a href="/attendance" class="btn btn-outline">🕐 View Attendance</a>
            <a href="/payroll" class="btn btn-outline" id="actionPayroll">💰 Run Payroll</a>
            <a href="/compliance" class="btn btn-outline">🛡️ Compliance Check</a>
        </div>
    </div>
</div>

@push('scripts')
<script>
(async function init() {
    const user = JSON.parse(localStorage.getItem('worknexg_user') || '{}');
    document.getElementById('userName').textContent = user.name || 'User';

    // Hide role-specific actions
    if (!['admin','hr'].includes(user.role)) {
        document.getElementById('actionEmployees')?.remove();
        document.getElementById('actionPayroll')?.remove();
    }

    // Load stats
    try {
        const empRes = await window.api('GET', '/api/employees');
        document.getElementById('statEmployees').textContent = empRes.data?.total ?? empRes.data?.data?.length ?? '—';
    } catch(e) { document.getElementById('statEmployees').textContent = '—'; }

    document.getElementById('statPresent').textContent = '—';
    document.getElementById('statLeave').textContent = '—';
    document.getElementById('statDocs').textContent = '—';

    // Load recent attendance
    try {
        const attRes = await window.api('GET', '/api/attendance/today');
        const tbody = document.querySelector('#attendanceTable tbody');
        const records = attRes.data?.data ?? attRes.data ?? [];
        if (records.length) {
            tbody.innerHTML = records.slice(0,5).map(r => `
                <tr>
                    <td>${r.employee?.full_name ?? r.employee_id ?? '—'}</td>
                    <td>${r.attendance_date ?? '—'}</td>
                    <td><span class="badge badge-${r.status === 'present' ? 'green' : r.status === 'absent' ? 'red' : 'yellow'}">${r.status}</span></td>
                </tr>`).join('');
        } else {
            tbody.innerHTML = '<tr><td colspan="3" style="text-align:center;color:var(--muted)">No records today</td></tr>';
        }
    } catch(e) {
        document.querySelector('#attendanceTable tbody').innerHTML = '<tr><td colspan="3" style="text-align:center;color:var(--muted)">—</td></tr>';
    }
})();
</script>
@endpush
@endsection
