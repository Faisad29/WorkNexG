@extends('layouts.app')
@section('title', 'Attendance')
@section('content')

<div class="page-header" style="display:flex;align-items:center;justify-content:space-between">
    <div><h1>Attendance</h1><p>Monitor check-ins and work hours</p></div>
    <div style="display:flex;gap:10px">
        <button class="btn btn-primary" onclick="openCheckInModal()">+ Manual Check-In</button>
    </div>
</div>

<!-- Filters -->
<div class="card" style="margin-bottom:20px">
    <div style="display:flex;gap:12px;flex-wrap:wrap">
        <input type="date" id="filterDate" style="padding:8px 12px;border:1px solid var(--border);border-radius:6px;font-size:.875rem">
        <select id="filterStatus" style="padding:8px 12px;border:1px solid var(--border);border-radius:6px;font-size:.875rem">
            <option value="">All Status</option>
            <option value="present">Present</option>
            <option value="absent">Absent</option>
            <option value="late">Late</option>
            <option value="half_day">Half Day</option>
        </select>
        <button class="btn btn-primary btn-sm" onclick="loadAttendance()">Filter</button>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table id="attendanceTable">
            <thead>
                <tr><th>Employee</th><th>Date</th><th>Check In</th><th>Check Out</th><th>Work Hours</th><th>Overtime</th><th>Status</th><th>Override</th></tr>
            </thead>
            <tbody><tr><td colspan="8" style="text-align:center;padding:24px;color:var(--muted)">Loading...</td></tr></tbody>
        </table>
    </div>
</div>

<!-- Pending Overrides -->
<div style="margin-top:24px">
    <div class="card">
        <div class="card-title">Pending Override Requests</div>
        <div class="table-wrap">
            <table id="overrideTable">
                <thead><tr><th>Employee</th><th>Date</th><th>Reason</th><th>Requested At</th><th>Actions</th></tr></thead>
                <tbody><tr><td colspan="5" style="text-align:center;color:var(--muted)">Loading...</td></tr></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Check-In Modal -->
<div id="checkInModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:200;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:12px;padding:28px;width:100%;max-width:480px">
        <div style="display:flex;justify-content:space-between;margin-bottom:20px">
            <h2 style="font-size:1.1rem;font-weight:700">Manual Check-In</h2>
            <button onclick="document.getElementById('checkInModal').style.display='none'" style="background:none;border:none;font-size:1.4rem;cursor:pointer">×</button>
        </div>
        <div class="alert alert-error" id="checkInError" style="display:none"></div>
        <form id="checkInForm">
            <div class="form-group" style="margin-bottom:14px">
                <label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:5px;color:var(--muted)">Employee ID *</label>
                <input name="employee_id" required placeholder="UUID of employee" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:6px;font-size:.875rem">
            </div>
            <div class="form-group" style="margin-bottom:14px">
                <label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:5px;color:var(--muted)">Site ID *</label>
                <input name="site_id" required placeholder="UUID of site" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:6px;font-size:.875rem">
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px">
                <div>
                    <label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:5px;color:var(--muted)">Date *</label>
                    <input name="attendance_date" type="date" required style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:6px;font-size:.875rem">
                </div>
                <div>
                    <label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:5px;color:var(--muted)">Check-In Time *</label>
                    <input name="check_in_time" type="datetime-local" required style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:6px;font-size:.875rem">
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:18px">
                <div>
                    <label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:5px;color:var(--muted)">Latitude</label>
                    <input name="check_in_latitude" type="number" step="any" placeholder="24.7136" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:6px;font-size:.875rem">
                </div>
                <div>
                    <label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:5px;color:var(--muted)">Longitude</label>
                    <input name="check_in_longitude" type="number" step="any" placeholder="46.6753" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:6px;font-size:.875rem">
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%">Submit Check-In</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('filterDate').value = new Date().toISOString().split('T')[0];

async function loadAttendance() {
    const date = document.getElementById('filterDate').value;
    const status = document.getElementById('filterStatus').value;
    try {
        const data = await window.api('GET', `/api/attendance?date=${date}&status=${status}`);
        const records = data.data?.data ?? data.data ?? [];
        const tbody = document.querySelector('#attendanceTable tbody');
        tbody.innerHTML = records.length ? records.map(r => `
            <tr>
                <td>${r.employee?.full_name ?? r.employee_id ?? '—'}</td>
                <td>${r.attendance_date ?? '—'}</td>
                <td>${r.check_in_time ? new Date(r.check_in_time).toLocaleTimeString() : '—'}</td>
                <td>${r.check_out_time ? new Date(r.check_out_time).toLocaleTimeString() : '<span style="color:var(--muted)">Not yet</span>'}</td>
                <td>${r.work_hours ?? '—'} hrs</td>
                <td>${r.overtime_hours ?? '0'} hrs</td>
                <td><span class="badge badge-${r.status === 'present' ? 'green' : r.status === 'absent' ? 'red' : r.status === 'late' ? 'yellow' : 'blue'}">${r.status}</span></td>
                <td>${r.is_manual_override ? '<span class="badge badge-yellow">Override</span>' : '—'}</td>
            </tr>`).join('') : '<tr><td colspan="8" style="text-align:center;padding:24px;color:var(--muted)">No records found</td></tr>';
    } catch(e) {
        document.querySelector('#attendanceTable tbody').innerHTML = '<tr><td colspan="8" style="text-align:center;color:var(--muted)">Failed to load</td></tr>';
    }
}

async function loadOverrides() {
    // Placeholder — extend with dedicated endpoint
    document.querySelector('#overrideTable tbody').innerHTML = '<tr><td colspan="5" style="text-align:center;color:var(--muted)">No pending overrides</td></tr>';
}

function openCheckInModal() { document.getElementById('checkInModal').style.display = 'flex'; }

document.getElementById('checkInForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const errAlert = document.getElementById('checkInError');
    const fd = new FormData(this);
    const body = {};
    fd.forEach((v,k) => { if(v) body[k] = v; });
    if (!body.check_in_latitude) body.check_in_latitude = 24.7136;
    if (!body.check_in_longitude) body.check_in_longitude = 46.6753;
    errAlert.style.display = 'none';
    try {
        await window.api('POST', '/api/attendance/check-in', body);
        document.getElementById('checkInModal').style.display = 'none';
        this.reset();
        loadAttendance();
    } catch(err) {
        const msgs = err.data?.errors ? Object.values(err.data.errors).flat().join(', ') : (err.data?.message || 'Failed.');
        errAlert.textContent = msgs; errAlert.style.display = 'block';
    }
});

loadAttendance();
loadOverrides();
</script>
@endpush
@endsection
