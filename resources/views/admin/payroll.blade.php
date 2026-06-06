@extends('layouts.app')
@section('title', 'Payroll')
@section('content')

<div class="page-header">
    <div>
        <h1>Payroll</h1>
        <div class="breadcrumb"><span>Home</span><span class="sep">›</span><span>Payroll</span></div>
    </div>
    <div class="page-actions">
        <button class="btn btn-primary" onclick="openModal('generateModal')">⚡ Generate Payroll</button>
    </div>
</div>

<div class="stats-row">
    <div class="stat-card"><div class="stat-icon blue">📋</div><div class="stat-value" id="sDraft">—</div><div class="stat-label">Draft</div></div>
    <div class="stat-card" style="--stat-accent:var(--warning)"><div class="stat-icon orange">✅</div><div class="stat-value" id="sApproved">—</div><div class="stat-label">Approved</div></div>
    <div class="stat-card" style="--stat-accent:var(--success)"><div class="stat-icon green">💰</div><div class="stat-value" id="sPaid">—</div><div class="stat-label">Paid</div></div>
    <div class="stat-card" style="--stat-accent:var(--primary)"><div class="stat-icon primary">📊</div><div class="stat-value" id="sTotal">—</div><div class="stat-label">Total Amount</div></div>
</div>

<div class="card">
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr><th>Period</th><th>Employees</th><th>Total Amount</th><th>Status</th><th>Generated</th><th>Actions</th></tr>
            </thead>
            <tbody id="payrollBody">
                <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--muted)">Loading...</td></tr>
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap"><span id="payrollInfo">—</span><div id="payrollPages"></div></div>
</div>

{{-- Generate Modal --}}
<div class="modal-overlay" id="generateModal">
    <div class="modal modal-sm">
        <div class="modal-header">
            <div class="modal-title">Generate Payroll</div>
            <button class="modal-close" onclick="closeModal('generateModal')">✕</button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger" id="genErr" style="display:none"></div>
            <div class="alert alert-success" id="genOk" style="display:none"></div>
            <form id="generateForm">
                <div class="form-row cols-2">
                    <div class="form-group">
                        <label class="form-label">Month <span class="req">*</span></label>
                        <select name="month" class="form-control" required>
                            @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $m == date('n') ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Year <span class="req">*</span></label>
                        <input name="year" type="number" class="form-control" value="{{ date('Y') }}" min="2024" required>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-ghost" onclick="closeModal('generateModal')">Cancel</button>
            <button class="btn btn-primary" onclick="generatePayroll()" id="genBtn">Generate</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function monthName(m) { return new Date(2000,m-1,1).toLocaleString('default',{month:'long'}); }
const statusColor = {draft:'gray',generated:'info',approved:'warning',locked:'primary',paid:'success'};

async function loadPayrolls() {
    const tbody = document.getElementById('payrollBody');
    try {
        const res = await WN.api('GET', '/api/payroll');
        const records = res.data?.data ?? res.data ?? [];
        const approved = records.filter(r=>r.status==='approved').length;
        const paid = records.filter(r=>r.status==='paid').length;
        const draft = records.filter(r=>['generated','draft'].includes(r.status)).length;
        const total = records.reduce((s,r)=>s+parseFloat(r.total_amount||0),0);
        document.getElementById('sDraft').textContent = draft;
        document.getElementById('sApproved').textContent = approved;
        document.getElementById('sPaid').textContent = paid;
        document.getElementById('sTotal').textContent = 'SAR ' + total.toLocaleString(undefined,{maximumFractionDigits:0});

        if (!records.length) {
            tbody.innerHTML = '<tr><td colspan="6"><div class="empty-state"><div class="empty-icon">💰</div><h3>No payrolls yet</h3><p>Generate your first payroll run</p><button class="btn btn-primary" onclick="openModal(\'generateModal\')">⚡ Generate Payroll</button></div></td></tr>';
            return;
        }
        tbody.innerHTML = records.map(p => `<tr>
            <td><strong>${monthName(p.month)} ${p.year}</strong></td>
            <td>${p.total_employees ?? '—'}</td>
            <td style="font-weight:600">SAR ${parseFloat(p.total_amount).toLocaleString(undefined,{minimumFractionDigits:2})}</td>
            <td><span class="badge badge-${statusColor[p.status]||'gray'}">${p.status}</span></td>
            <td style="color:var(--muted)">${p.generated_at ? new Date(p.generated_at).toLocaleDateString() : '—'}</td>
            <td><div class="tbl-actions">
                ${p.status==='generated'?`<button class="btn btn-warning btn-sm" onclick="approvePayroll('${p.id}')">✓ Approve</button>`:''}
                ${p.status==='approved'?`<button class="btn btn-outline btn-sm" onclick="lockPayroll('${p.id}')">🔒 Lock</button>`:''}
                ${p.status==='locked'?`<button class="btn btn-success btn-sm" onclick="markPaid('${p.id}')">✓ Mark Paid</button>`:''}
            </div></td>
        </tr>`).join('');
    } catch(e) {
        tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;color:var(--danger)">Failed to load payrolls</td></tr>';
    }
}

async function generatePayroll() {
    const btn = document.getElementById('genBtn');
    const errEl = document.getElementById('genErr');
    const okEl = document.getElementById('genOk');
    const fd = new FormData(document.getElementById('generateForm'));
    errEl.style.display='none'; okEl.style.display='none';
    btn.disabled=true; btn.innerHTML='<span class="spinner"></span>Generating...';
    try {
        const data = await WN.api('POST', '/api/payroll/generate', {month:parseInt(fd.get('month')),year:parseInt(fd.get('year'))});
        okEl.textContent = `✓ Payroll generated! Total: SAR ${parseFloat(data.data.total_amount).toLocaleString()}`;
        okEl.style.display = 'block';
        loadPayrolls();
        setTimeout(() => closeModal('generateModal'), 1800);
    } catch(err) {
        errEl.textContent = err.data?.message || 'Generation failed.'; errEl.style.display='block';
    } finally { btn.innerHTML='Generate'; btn.disabled=false; }
}

async function approvePayroll(id) {
    if (!confirm('Approve this payroll run?')) return;
    try { await WN.api('POST', `/api/payroll/${id}/approve`); loadPayrolls(); showToast('Payroll approved','success'); }
    catch(e) { showToast(e.data?.message||'Failed','danger'); }
}
async function lockPayroll(id) {
    if (!confirm('Lock this payroll? This prevents further changes.')) return;
    try { await WN.api('POST', `/api/payroll/${id}/lock`); loadPayrolls(); showToast('Payroll locked','success'); }
    catch(e) { showToast(e.data?.message||'Failed','danger'); }
}
async function markPaid(id) {
    if (!confirm('Mark as PAID? This action is final.')) return;
    try { await WN.api('POST', `/api/payroll/${id}/pay`); loadPayrolls(); showToast('Payroll marked as paid ✓','success'); }
    catch(e) { showToast(e.data?.message||'Failed','danger'); }
}
loadPayrolls();
</script>
@endpush
@endsection
