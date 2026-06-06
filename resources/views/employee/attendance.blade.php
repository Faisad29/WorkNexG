@extends('layouts.app')
@section('title', 'My Attendance')
@section('content')
<div class="page-header"><h1>My Attendance</h1><p>Your attendance history</p></div>
<div class="card">
    <div style="display:flex;gap:12px;margin-bottom:16px">
        <input type="month" id="monthFilter" style="padding:8px 12px;border:1px solid var(--border);border-radius:6px;font-size:.875rem">
        <button class="btn btn-primary btn-sm" onclick="loadMyAttendance()">Filter</button>
    </div>
    <div class="table-wrap">
        <table id="myAttTable">
            <thead><tr><th>Date</th><th>Check In</th><th>Check Out</th><th>Work Hours</th><th>Status</th></tr></thead>
            <tbody><tr><td colspan="5" style="text-align:center;padding:24px;color:var(--muted)">Loading...</td></tr></tbody>
        </table>
    </div>
</div>
@push('scripts')
<script>
document.getElementById('monthFilter').value = new Date().toISOString().substring(0,7);
async function loadMyAttendance() {
    const month = document.getElementById('monthFilter').value;
    try {
        const data = await window.api('GET', `/api/attendance?month=${month}`);
        const records = data.data?.data ?? data.data ?? [];
        const tbody = document.querySelector('#myAttTable tbody');
        tbody.innerHTML = records.length ? records.map(r => `
            <tr>
                <td>${r.attendance_date}</td>
                <td>${r.check_in_time ? new Date(r.check_in_time).toLocaleTimeString() : '—'}</td>
                <td>${r.check_out_time ? new Date(r.check_out_time).toLocaleTimeString() : '—'}</td>
                <td>${r.work_hours ?? '—'} hrs</td>
                <td><span class="badge badge-${r.status === 'present' ? 'green' : r.status === 'absent' ? 'red' : 'yellow'}">${r.status}</span></td>
            </tr>`).join('') : '<tr><td colspan="5" style="text-align:center;padding:24px;color:var(--muted)">No records</td></tr>';
    } catch(e) { document.querySelector('#myAttTable tbody').innerHTML = '<tr><td colspan="5" style="text-align:center;color:var(--muted)">Failed to load</td></tr>'; }
}
loadMyAttendance();
</script>
@endpush
@endsection
