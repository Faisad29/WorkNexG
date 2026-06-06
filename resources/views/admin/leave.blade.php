@extends('layouts.app')
@section('title', 'Leave Management')
@section('content')
<div class="page-header" style="display:flex;align-items:center;justify-content:space-between">
    <div><h1>Leave Management</h1><p>Review and approve leave requests</p></div>
    <button class="btn btn-primary" onclick="openAddLeaveModal()">+ New Request</button>
</div>
<div class="card">
    <div class="table-wrap">
        <table id="leaveTable">
            <thead><tr><th>Employee</th><th>Type</th><th>Start</th><th>End</th><th>Reason</th><th>Status</th></tr></thead>
            <tbody><tr><td colspan="6" style="text-align:center;padding:24px;color:var(--muted)">Loading...</td></tr></tbody>
        </table>
    </div>
</div>

<div id="addLeaveModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:200;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:12px;padding:28px;width:100%;max-width:440px">
        <div style="display:flex;justify-content:space-between;margin-bottom:20px">
            <h2 style="font-size:1.1rem;font-weight:700">Submit Leave Request</h2>
            <button onclick="document.getElementById('addLeaveModal').style.display='none'" style="background:none;border:none;font-size:1.4rem;cursor:pointer">×</button>
        </div>
        <div class="alert alert-error" id="leaveError" style="display:none"></div>
        <form id="addLeaveForm">
            <div style="display:flex;flex-direction:column;gap:14px">
                <div><label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:5px;color:var(--muted)">Employee ID *</label>
                    <input name="employee_id" required placeholder="Employee UUID" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:6px;font-size:.875rem"></div>
                <div><label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:5px;color:var(--muted)">Leave Type *</label>
                    <select name="leave_type" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:6px">
                        <option value="annual">Annual</option>
                        <option value="sick">Sick</option>
                        <option value="emergency">Emergency</option>
                        <option value="maternity">Maternity</option>
                        <option value="paternity">Paternity</option>
                        <option value="unpaid">Unpaid</option>
                    </select></div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <div><label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:5px;color:var(--muted)">Start Date *</label>
                        <input name="start_date" type="date" required style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:6px"></div>
                    <div><label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:5px;color:var(--muted)">End Date *</label>
                        <input name="end_date" type="date" required style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:6px"></div>
                </div>
                <div><label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:5px;color:var(--muted)">Reason</label>
                    <textarea name="reason" rows="3" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:6px;resize:vertical;font-size:.875rem"></textarea></div>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;margin-top:18px">Submit Request</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
async function loadLeave() {
    try {
        const data = await window.api('GET', '/api/leave');
        const records = data.data?.data ?? data.data ?? [];
        const tbody = document.querySelector('#leaveTable tbody');
        tbody.innerHTML = records.length ? records.map(l => `
            <tr>
                <td>${l.employee?.full_name ?? l.employee_id ?? '—'}</td>
                <td><span class="badge badge-blue">${l.leave_type}</span></td>
                <td>${l.start_date}</td><td>${l.end_date}</td>
                <td>${l.reason ?? '—'}</td>
                <td><span class="badge badge-${l.status==='approved'?'green':l.status==='rejected'?'red':'yellow'}">${l.status}</span></td>
            </tr>`).join('') : '<tr><td colspan="6" style="text-align:center;padding:24px;color:var(--muted)">No leave requests</td></tr>';
    } catch(e) { document.querySelector('#leaveTable tbody').innerHTML = '<tr><td colspan="6" style="text-align:center;color:var(--muted)">Failed to load</td></tr>'; }
}

function openAddLeaveModal() { document.getElementById('addLeaveModal').style.display = 'flex'; }

document.getElementById('addLeaveForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const errAlert = document.getElementById('leaveError');
    const fd = new FormData(this); const body = {};
    fd.forEach((v,k) => { if(v) body[k]=v; });
    errAlert.style.display = 'none';
    try {
        await window.api('POST', '/api/leave', body);
        document.getElementById('addLeaveModal').style.display = 'none';
        this.reset(); loadLeave();
    } catch(err) { errAlert.textContent = err.data?.message||'Failed.'; errAlert.style.display='block'; }
});

loadLeave();
</script>
@endpush
@endsection
