@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

<div class="page-header">
    <div>
        <h1>Dashboard</h1>
        <div class="breadcrumb">
            <span>Home</span> <span class="sep">›</span> <span>Dashboard</span>
        </div>
    </div>
    <div class="page-actions">
        <span id="dashDate" style="font-size:.82rem;color:var(--muted)"></span>
        <button class="btn btn-primary btn-sm" onclick="refreshDashboard()">↻ Refresh</button>
    </div>
</div>

{{-- Stats Row --}}
<div class="stats-row" id="statsRow">
    <div class="stat-card">
        <div class="stat-icon green">👥</div>
        <div class="stat-value" id="statEmployees">—</div>
        <div class="stat-label">Total Employees</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">✅</div>
        <div class="stat-value" id="statPresent">—</div>
        <div class="stat-label">Present Today</div>
    </div>
    <div class="stat-card" style="--stat-accent:var(--warning)">
        <div class="stat-icon orange">🌴</div>
        <div class="stat-value" id="statLeave">—</div>
        <div class="stat-label">On Leave</div>
    </div>
    <div class="stat-card" style="--stat-accent:var(--danger)">
        <div class="stat-icon red">🛡️</div>
        <div class="stat-value" id="statExpiring">—</div>
        <div class="stat-label">Expiring Docs</div>
    </div>
</div>

{{-- Main Grid --}}
<div class="grid-2">
    {{-- Recent Attendance --}}
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Today's Attendance</div>
                <div class="card-subtitle" id="attDate">—</div>
            </div>
            <a href="{{ route('attendance.index') }}" class="btn btn-outline btn-sm">View All</a>
        </div>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Check In</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="attTableBody">
                    <tr><td colspan="3" style="text-align:center;padding:24px;color:var(--muted)">Loading...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Quick Actions + Upcoming Expirations --}}
    <div style="display:flex;flex-direction:column;gap:20px">
        {{-- Quick Actions --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">Quick Actions</div>
            </div>
            <div class="card-body" style="display:grid;grid-template-columns:1fr 1fr;gap:10px" id="quickActions">
                <a href="{{ route('employees.index') }}" class="btn btn-outline" id="qaEmployees">👥 Employees</a>
                <a href="{{ route('attendance.index') }}" class="btn btn-outline">🕐 Attendance</a>
                <a href="{{ route('payroll.index') }}" class="btn btn-outline" id="qaPayroll">💰 Payroll</a>
                <a href="{{ route('compliance.index') }}" class="btn btn-outline" id="qaCompliance">🛡️ Compliance</a>
                <a href="{{ route('leave.index') }}" class="btn btn-outline">🌴 Leave</a>
                <a href="{{ route('reports.index') }}" class="btn btn-outline" id="qaReports">📊 Reports</a>
            </div>
        </div>

        {{-- Expiring Documents --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">⚠️ Expiring Documents</div>
                <a href="{{ route('compliance.index') }}" class="btn btn-ghost btn-sm">View All</a>
            </div>
            <div id="expiringDocs" style="padding:0 20px 16px">
                <div style="text-align:center;padding:20px;color:var(--muted);font-size:.85rem">Loading...</div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Set date
document.getElementById('dashDate').textContent = new Date().toLocaleDateString('en-US', {weekday:'long',year:'numeric',month:'long',day:'numeric'});
document.getElementById('attDate').textContent = new Date().toLocaleDateString('en-US', {month:'short',day:'numeric',year:'numeric'});

// Hide role-specific quick actions
const role = WN.role();
if (!['admin','hr'].includes(role)) {
    document.getElementById('qaEmployees')?.remove();
    document.getElementById('qaCompliance')?.remove();
}
if (!['admin','accountant'].includes(role)) {
    document.getElementById('qaPayroll')?.remove();
}
if (role !== 'admin') {
    document.getElementById('qaReports')?.remove();
}

async function refreshDashboard() {
    await Promise.all([loadStats(), loadAttendance(), loadExpiringDocs()]);
    showToast('Dashboard refreshed', 'success');
}

async function loadStats() {
    try {
        const res = await WN.api('GET', '/api/employees?per_page=1');
        document.getElementById('statEmployees').textContent = res.data?.total ?? '—';
    } catch(e) { document.getElementById('statEmployees').textContent = '—'; }

    // Attendance stats
    try {
        const date = new Date().toISOString().split('T')[0];
        const res = await WN.api('GET', `/api/attendance?date=${date}`);
        const records = res.data?.data ?? res.data ?? [];
        const present = records.filter(r => r.status === 'present').length;
        const onLeave = records.filter(r => r.status === 'on_leave').length;
        document.getElementById('statPresent').textContent = present;
        document.getElementById('statLeave').textContent = onLeave;
    } catch(e) {}

    // Expiring docs
    try {
        const res = await WN.api('GET', '/api/documents');
        const docs = res.data?.data ?? res.data ?? [];
        const now = new Date();
        const in30 = new Date(now.getTime() + 30*24*60*60*1000);
        const expiring = docs.filter(d => d.expiry_date && new Date(d.expiry_date) <= in30 && new Date(d.expiry_date) > now).length;
        document.getElementById('statExpiring').textContent = expiring;
    } catch(e) {}
}

async function loadAttendance() {
    const tbody = document.getElementById('attTableBody');
    try {
        const date = new Date().toISOString().split('T')[0];
        const res = await WN.api('GET', `/api/attendance?date=${date}`);
        const records = (res.data?.data ?? res.data ?? []).slice(0, 6);
        if (!records.length) {
            tbody.innerHTML = '<tr><td colspan="3" style="text-align:center;padding:24px;color:var(--muted)">No attendance records today</td></tr>';
            return;
        }
        const statusMap = {present:'success',absent:'danger',late:'warning',half_day:'info'};
        tbody.innerHTML = records.map(r => `
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:8px">
                        <div class="tbl-avatar">${(r.employee?.full_name||'?')[0]}</div>
                        <span>${r.employee?.full_name ?? r.employee_id ?? '—'}</span>
                    </div>
                </td>
                <td style="color:var(--muted)">${r.check_in_time ? new Date(r.check_in_time).toLocaleTimeString('en-US',{hour:'2-digit',minute:'2-digit'}) : '—'}</td>
                <td><span class="badge badge-${statusMap[r.status]||'gray'}">${r.status??'—'}</span></td>
            </tr>`).join('');
    } catch(e) {
        tbody.innerHTML = '<tr><td colspan="3" style="text-align:center;color:var(--muted)">Failed to load</td></tr>';
    }
}

async function loadExpiringDocs() {
    const el = document.getElementById('expiringDocs');
    try {
        const res = await WN.api('GET', '/api/documents');
        const docs = res.data?.data ?? res.data ?? [];
        const now = new Date();
        const in30 = new Date(now.getTime() + 30*24*60*60*1000);
        const expiring = docs.filter(d => d.expiry_date && new Date(d.expiry_date) <= in30);
        if (!expiring.length) {
            el.innerHTML = '<div style="text-align:center;padding:16px;color:var(--muted);font-size:.85rem">✅ No documents expiring soon</div>';
            return;
        }
        el.innerHTML = expiring.slice(0,4).map(d => {
            const daysLeft = Math.ceil((new Date(d.expiry_date) - now) / (1000*60*60*24));
            const isExpired = daysLeft < 0;
            const color = isExpired ? 'danger' : daysLeft <= 7 ? 'danger' : 'warning';
            return `<div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid var(--border-l)">
                <div>
                    <div style="font-size:.83rem;font-weight:600">${d.employee?.full_name ?? '—'}</div>
                    <div style="font-size:.75rem;color:var(--muted)">${d.type?.toUpperCase()} · ${d.document_number??'—'}</div>
                </div>
                <span class="badge badge-${color}">${isExpired ? 'Expired' : daysLeft+'d left'}</span>
            </div>`;
        }).join('');
    } catch(e) {
        el.innerHTML = '<div style="text-align:center;padding:16px;color:var(--muted);font-size:.85rem">Failed to load</div>';
    }
}

loadStats();
loadAttendance();
loadExpiringDocs();
</script>
@endpush
@endsection
