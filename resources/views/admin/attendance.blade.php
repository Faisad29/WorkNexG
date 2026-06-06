@extends('layouts.app')
@section('title', 'Attendance')
@section('content')

<div class="page-header">
    <div>
        <h1>Attendance</h1>
        <div class="breadcrumb"><span>Home</span><span class="sep">›</span><span>Attendance</span></div>
    </div>
    <div class="page-actions">
        <button class="btn btn-outline btn-sm" onclick="openModal('checkInModal')">+ Manual Check-In</button>
        <button class="btn btn-ghost btn-sm">⬇ Export</button>
    </div>
</div>

{{-- Stats --}}
<div class="stats-row">
    <div class="stat-card"><div class="stat-icon green">✅</div><div class="stat-value" id="sPresent">—</div><div class="stat-label">Present</div></div>
    <div class="stat-card" style="--stat-accent:var(--danger)"><div class="stat-icon red">❌</div><div class="stat-value" id="sAbsent">—</div><div class="stat-label">Absent</div></div>
    <div class="stat-card" style="--stat-accent:var(--warning)"><div class="stat-icon orange">⏰</div><div class="stat-value" id="sLate">—</div><div class="stat-label">Late</div></div>
    <div class="stat-card" style="--stat-accent:var(--info)"><div class="stat-icon blue">📋</div><div class="stat-value" id="sOverrides">—</div><div class="stat-label">Pending Overrides</div></div>
</div>

{{-- Tabs --}}
<div data-tabs>
    <div class="tab-nav">
        <button class="tab-btn active" data-tab="tabRecords">Attendance Records</button>
        <button class="tab-btn" data-tab="tabOverrides">Override Requests</button>
    </div>

    <div class="tab-pane active" id="tabRecords">
        <div class="card">
            <div class="filters-bar">
                <input type="date" id="filterDate" class="form-control" style="width:170px">
                <select id="filterStatus" class="form-control" style="width:150px">
                    <option value="">All Status</option>
                    <option value="present">Present</option>
                    <option value="absent">Absent</option>
                    <option value="late">Late</option>
                    <option value="half_day">Half Day</option>
                </select>
                <button class="btn btn-primary btn-sm" onclick="loadAttendance()">Filter</button>
                <button class="btn btn-ghost btn-sm" onclick="resetAttFilter()">Clear</button>
            </div>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Employee</th><th>Date</th><th>Check In</th>
                            <th>Check Out</th><th>Hours</th><th>Overtime</th>
                            <th>Status</th><th>Override</th>
                        </tr>
                    </thead>
                    <tbody id="attBody">
                        <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--muted)">Loading...</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="pagination-wrap">
                <span id="attInfo">—</span>
                <div class="pagination-pages" id="attPages"></div>
            </div>
        </div>
    </div>

    <div class="tab-pane" id="tabOverrides">
        <div class="card">
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr><th>Employee</th><th>Date</th><th>Reason</th><th>Requested</th><th>Actions</th></tr>
                    </thead>
                    <tbody id="overrideBody">
                        <tr><td colspan="5" style="text-align:center;padding:40px;color:var(--muted)">Loading...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Check-In Modal --}}
<div class="modal-overlay" id="checkInModal">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Manual Check-In</div>
            <button class="modal-close" onclick="closeModal('checkInModal')">✕</button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger" id="ciErr" style="display:none"></div>
            <form id="checkInForm">
                <div class="form-row cols-2">
                    <div class="form-group">
                        <label class="form-label">Employee ID <span class="req">*</span></label>
                        <input name="employee_id" class="form-control" placeholder="Employee UUID" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Site ID <span class="req">*</span></label>
                        <input name="site_id" class="form-control" placeholder="Site UUID" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date <span class="req">*</span></label>
                        <input name="attendance_date" type="date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Check-In Time <span class="req">*</span></label>
                        <input name="check_in_time" type="datetime-local" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Latitude</label>
                        <input name="check_in_latitude" type="number" step="any" class="form-control" value="24.7136">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Longitude</label>
                        <input name="check_in_longitude" type="number" step="any" class="form-control" value="46.6753">
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-ghost" onclick="closeModal('checkInModal')">Cancel</button>
            <button class="btn btn-primary" onclick="submitCheckIn()" id="ciBtn">Submit</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('filterDate').value = new Date().toISOString().split('T')[0];
const dtNow = new Date(); dtNow.setSeconds(0,0);
const checkInTimeEl = document.querySelector('[name="check_in_time"]');
if (checkInTimeEl) checkInTimeEl.value = dtNow.toISOString().slice(0,16);

async function loadAttendance(page = 1) {
    const date = document.getElementById('filterDate').value;
    const status = document.getElementById('filterStatus').value;
    const tbody = document.getElementById('attBody');
    tbody.innerHTML = '<tr><td colspan="8" style="text-align:center;padding:30px;color:var(--muted)">Loading...</td></tr>';
    try {
        const params = new URLSearchParams({ page, ...(date && {date}), ...(status && {status}) });
        const res = await WN.api('GET', `/api/attendance?${params}`);
        const records = res.data?.data ?? res.data ?? [];
        const meta = res.data;
        document.getElementById('sPresent').textContent = records.filter(r=>r.status==='present').length;
        document.getElementById('sAbsent').textContent = records.filter(r=>r.status==='absent').length;
        document.getElementById('sLate').textContent = records.filter(r=>r.status==='late').length;
        if (!records.length) { tbody.innerHTML = '<tr><td colspan="8"><div class="empty-state"><div class="empty-icon">🕐</div><h3>No records</h3></div></td></tr>'; return; }
        const sm = {present:'success',absent:'danger',late:'warning',half_day:'info'};
        tbody.innerHTML = records.map(r => `<tr>
            <td><div style="display:flex;align-items:center;gap:8px"><div class="tbl-avatar">${(r.employee?.full_name||'?')[0]}</div>${r.employee?.full_name??r.employee_id??'—'}</div></td>
            <td>${r.attendance_date??'—'}</td>
            <td>${r.check_in_time?new Date(r.check_in_time).toLocaleTimeString('en-US',{hour:'2-digit',minute:'2-digit'}):'—'}</td>
            <td>${r.check_out_time?new Date(r.check_out_time).toLocaleTimeString('en-US',{hour:'2-digit',minute:'2-digit'}):'<span style="color:var(--muted-l)">Not yet</span>'}</td>
            <td>${r.work_hours??'—'} hrs</td>
            <td>${r.overtime_hours??'0'} hrs</td>
            <td><span class="badge badge-${sm[r.status]||'gray'}">${r.status??'—'}</span></td>
            <td>${r.is_manual_override?'<span class="badge badge-warning">Override</span>':'—'}</td>
        </tr>`).join('');
    } catch(e) {
        tbody.innerHTML = '<tr><td colspan="8" style="text-align:center;color:var(--danger)">Failed to load</td></tr>';
    }
}

async function loadOverrides() {
    const tbody = document.getElementById('overrideBody');
    document.getElementById('sOverrides').textContent = '0';
    try {
        const res = await WN.api('GET', '/api/attendance/overrides?status=override_request');
        const records = res.data?.data ?? res.data ?? [];
        document.getElementById('sOverrides').textContent = records.length;
        if (!records.length) { tbody.innerHTML = '<tr><td colspan="5"><div class="empty-state"><div class="empty-icon">✅</div><h3>No pending overrides</h3></div></td></tr>'; return; }
        tbody.innerHTML = records.map(r => `<tr>
            <td>${r.employee?.full_name??r.employee_id??'—'}</td>
            <td>${r.attendance_date??'—'}</td>
            <td>${r.reason??'—'}</td>
            <td>${r.created_at?new Date(r.created_at).toLocaleDateString():'—'}</td>
            <td><div class="tbl-actions">
                <button class="btn btn-success btn-sm" onclick="approveOverride('${r.id}')">✓ Approve</button>
                <button class="btn btn-danger btn-sm" onclick="rejectOverride('${r.id}')">✕ Reject</button>
            </div></td>
        </tr>`).join('');
    } catch(e) {
        tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;color:var(--muted)">—</td></tr>';
    }
}

async function approveOverride(id) {
    try { await WN.api('POST', `/api/attendance/overrides/${id}/approve`); loadOverrides(); showToast('Override approved', 'success'); }
    catch(e) { showToast('Failed to approve', 'danger'); }
}
async function rejectOverride(id) {
    try { await WN.api('POST', `/api/attendance/overrides/${id}/reject`); loadOverrides(); showToast('Override rejected', 'info'); }
    catch(e) { showToast('Failed to reject', 'danger'); }
}

async function submitCheckIn() {
    const btn = document.getElementById('ciBtn');
    const errEl = document.getElementById('ciErr');
    const fd = new FormData(document.getElementById('checkInForm'));
    const body = {};
    fd.forEach((v,k) => { if(v) body[k] = v; });
    errEl.style.display = 'none';
    btn.disabled = true; btn.innerHTML = '<span class="spinner"></span>';
    try {
        await WN.api('POST', '/api/attendance/check-in', body);
        closeModal('checkInModal');
        document.getElementById('checkInForm').reset();
        loadAttendance();
        showToast('Check-in recorded', 'success');
    } catch(err) {
        const msg = err.data?.errors ? Object.values(err.data.errors).flat().join(' · ') : (err.data?.message || 'Failed.');
        errEl.textContent = msg; errEl.style.display = 'block';
    } finally { btn.innerHTML = 'Submit'; btn.disabled = false; }
}

function resetAttFilter() {
    document.getElementById('filterDate').value = new Date().toISOString().split('T')[0];
    document.getElementById('filterStatus').value = '';
    loadAttendance();
}

loadAttendance();
loadOverrides();
</script>
@endpush
@endsection
