@extends('layouts.app')
@section('title', 'Payroll')
@section('content')

<div class="page-header" style="display:flex;align-items:center;justify-content:space-between">
    <div><h1>Payroll</h1><p>Generate and manage salary payments</p></div>
    <button class="btn btn-primary" onclick="openGenerateModal()">⚡ Generate Payroll</button>
</div>

<div class="card">
    <div class="table-wrap">
        <table id="payrollTable">
            <thead><tr><th>Period</th><th>Employees</th><th>Total Amount</th><th>Status</th><th>Generated</th><th>Actions</th></tr></thead>
            <tbody><tr><td colspan="6" style="text-align:center;padding:24px;color:var(--muted)">Loading...</td></tr></tbody>
        </table>
    </div>
</div>

<!-- Generate Modal -->
<div id="generateModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:200;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:12px;padding:28px;width:100%;max-width:380px">
        <div style="display:flex;justify-content:space-between;margin-bottom:20px">
            <h2 style="font-size:1.1rem;font-weight:700">Generate Payroll</h2>
            <button onclick="closeGenerateModal()" style="background:none;border:none;font-size:1.4rem;cursor:pointer">×</button>
        </div>
        <div class="alert alert-error" id="genError" style="display:none"></div>
        <div class="alert alert-success" id="genSuccess" style="display:none"></div>
        <form id="generateForm">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:18px">
                <div>
                    <label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:5px;color:var(--muted)">Month *</label>
                    <select name="month" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:6px">
                        @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $m == date('n') ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:5px;color:var(--muted)">Year *</label>
                    <input name="year" type="number" value="{{ date('Y') }}" min="2024" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:6px">
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%" id="genBtn">Generate</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
async function loadPayrolls() {
    try {
        const data = await window.api('GET', '/api/payroll');
        const records = data.data?.data ?? data.data ?? [];
        const tbody = document.querySelector('#payrollTable tbody');
        tbody.innerHTML = records.length ? records.map(p => `
            <tr>
                <td><strong>${monthName(p.month)} ${p.year}</strong></td>
                <td>${p.total_employees}</td>
                <td>SAR ${parseFloat(p.total_amount).toLocaleString(undefined, {minimumFractionDigits:2})}</td>
                <td><span class="badge badge-${statusColor(p.status)}">${p.status}</span></td>
                <td>${p.generated_at ? new Date(p.generated_at).toLocaleDateString() : '—'}</td>
                <td style="display:flex;gap:6px;flex-wrap:wrap">
                    ${p.status === 'generated' ? `<button class="btn btn-outline btn-sm" onclick="approvePayroll('${p.id}')">Approve</button>` : ''}
                    ${p.status === 'approved' ? `<button class="btn btn-outline btn-sm" onclick="lockPayroll('${p.id}')">Lock</button>` : ''}
                    ${p.status === 'locked' ? `<button class="btn btn-primary btn-sm" onclick="payPayroll('${p.id}')">Mark Paid</button>` : ''}
                </td>
            </tr>`).join('') : '<tr><td colspan="6" style="text-align:center;padding:24px;color:var(--muted)">No payrolls found</td></tr>';
    } catch(e) { document.querySelector('#payrollTable tbody').innerHTML = '<tr><td colspan="6" style="text-align:center;color:var(--muted)">Failed to load</td></tr>'; }
}

function monthName(m) { return new Date(2000, m-1, 1).toLocaleString('default', {month:'long'}); }
function statusColor(s) { return {generated:'blue',approved:'yellow',locked:'gray',paid:'green',draft:'gray'}[s] ?? 'gray'; }

function openGenerateModal() { document.getElementById('generateModal').style.display = 'flex'; }
function closeGenerateModal() { document.getElementById('generateModal').style.display = 'none'; }

document.getElementById('generateForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const fd = new FormData(this);
    const body = { month: parseInt(fd.get('month')), year: parseInt(fd.get('year')) };
    const btn = document.getElementById('genBtn');
    const errAlert = document.getElementById('genError');
    const successAlert = document.getElementById('genSuccess');
    btn.textContent = 'Generating...'; btn.disabled = true;
    errAlert.style.display = 'none'; successAlert.style.display = 'none';
    try {
        const data = await window.api('POST', '/api/payroll/generate', body);
        successAlert.textContent = `Payroll generated! Total: SAR ${parseFloat(data.data.total_amount).toLocaleString()}`;
        successAlert.style.display = 'block';
        loadPayrolls();
        setTimeout(closeGenerateModal, 1500);
    } catch(err) {
        errAlert.textContent = err.data?.message || 'Generation failed.'; errAlert.style.display = 'block';
    } finally { btn.textContent = 'Generate'; btn.disabled = false; }
});

async function approvePayroll(id) {
    if (!confirm('Approve this payroll?')) return;
    try { await window.api('POST', `/api/payroll/${id}/approve`); loadPayrolls(); } catch(e) { alert(e.data?.message || 'Failed.'); }
}
async function lockPayroll(id) {
    if (!confirm('Lock this payroll? This cannot be undone easily.')) return;
    try { await window.api('POST', `/api/payroll/${id}/lock`); loadPayrolls(); } catch(e) { alert(e.data?.message || 'Failed.'); }
}
async function payPayroll(id) {
    if (!confirm('Mark payroll as PAID?')) return;
    try { await window.api('POST', `/api/payroll/${id}/pay`); loadPayrolls(); } catch(e) { alert(e.data?.message || 'Failed.'); }
}

loadPayrolls();
</script>
@endpush
@endsection
