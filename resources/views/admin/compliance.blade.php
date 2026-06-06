@extends('layouts.app')
@section('title', 'Compliance')
@section('content')

<div class="page-header" style="display:flex;align-items:center;justify-content:space-between">
    <div><h1>Compliance</h1><p>Document management and expiry tracking</p></div>
    <button class="btn btn-primary" onclick="openAddDocModal()">+ Add Document</button>
</div>

<div class="stats-grid" style="margin-bottom:24px">
    <div class="stat-card"><div class="stat-label">Total Documents</div><div class="stat-value" id="statTotal">—</div></div>
    <div class="stat-card"><div class="stat-label">Expiring ≤ 30 Days</div><div class="stat-value" id="statExpiring" style="color:var(--warning)">—</div></div>
    <div class="stat-card"><div class="stat-label">Already Expired</div><div class="stat-value" id="statExpired" style="color:var(--danger)">—</div></div>
</div>

<div class="card">
    <div style="display:flex;gap:12px;margin-bottom:16px">
        <select id="typeFilter" style="padding:8px 12px;border:1px solid var(--border);border-radius:6px;font-size:.875rem">
            <option value="">All Types</option>
            <option value="iqama">Iqama</option>
            <option value="visa">Visa</option>
            <option value="passport">Passport</option>
            <option value="contract">Contract</option>
            <option value="certificate">Certificate</option>
        </select>
        <button class="btn btn-primary btn-sm" onclick="loadDocuments()">Filter</button>
    </div>
    <div class="table-wrap">
        <table id="docTable">
            <thead><tr><th>Employee</th><th>Type</th><th>Doc Number</th><th>Issue Date</th><th>Expiry Date</th><th>Status</th></tr></thead>
            <tbody><tr><td colspan="6" style="text-align:center;padding:24px;color:var(--muted)">Loading...</td></tr></tbody>
        </table>
    </div>
</div>

<!-- Add Document Modal -->
<div id="addDocModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:200;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:12px;padding:28px;width:100%;max-width:460px">
        <div style="display:flex;justify-content:space-between;margin-bottom:20px">
            <h2 style="font-size:1.1rem;font-weight:700">Add Document</h2>
            <button onclick="document.getElementById('addDocModal').style.display='none'" style="background:none;border:none;font-size:1.4rem;cursor:pointer">×</button>
        </div>
        <div class="alert alert-error" id="docError" style="display:none"></div>
        <form id="addDocForm">
            <div style="display:flex;flex-direction:column;gap:14px">
                <div><label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:5px;color:var(--muted)">Employee ID *</label>
                    <input name="employee_id" required placeholder="Employee UUID" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:6px;font-size:.875rem"></div>
                <div><label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:5px;color:var(--muted)">Document Type *</label>
                    <select name="type" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:6px">
                        <option value="iqama">Iqama</option>
                        <option value="visa">Visa</option>
                        <option value="passport">Passport</option>
                        <option value="contract">Contract</option>
                        <option value="certificate">Certificate</option>
                    </select></div>
                <div><label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:5px;color:var(--muted)">Document Number</label>
                    <input name="document_number" placeholder="2380001234" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:6px;font-size:.875rem"></div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                    <div><label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:5px;color:var(--muted)">Issue Date</label>
                        <input name="issue_date" type="date" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:6px"></div>
                    <div><label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:5px;color:var(--muted)">Expiry Date</label>
                        <input name="expiry_date" type="date" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:6px"></div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;margin-top:18px">Save Document</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
async function loadDocuments() {
    const type = document.getElementById('typeFilter').value;
    try {
        const data = await window.api('GET', `/api/documents?type=${type}`);
        const docs = data.data?.data ?? data.data ?? [];
        document.getElementById('statTotal').textContent = docs.length;
        const now = new Date();
        const in30 = new Date(now.getTime() + 30*24*60*60*1000);
        document.getElementById('statExpiring').textContent = docs.filter(d => d.expiry_date && new Date(d.expiry_date) <= in30 && new Date(d.expiry_date) > now).length;
        document.getElementById('statExpired').textContent = docs.filter(d => d.expiry_date && new Date(d.expiry_date) < now).length;

        const tbody = document.querySelector('#docTable tbody');
        tbody.innerHTML = docs.length ? docs.map(d => {
            const exp = d.expiry_date ? new Date(d.expiry_date) : null;
            let status = '—', badgeClass = 'gray';
            if (exp) {
                if (exp < now) { status = 'Expired'; badgeClass = 'red'; }
                else if (exp <= in30) { status = 'Expiring'; badgeClass = 'yellow'; }
                else { status = 'Valid'; badgeClass = 'green'; }
            }
            return `<tr>
                <td>${d.employee?.full_name ?? d.employee_id ?? '—'}</td>
                <td><span class="badge badge-blue">${d.type}</span></td>
                <td>${d.document_number ?? '—'}</td>
                <td>${d.issue_date ?? '—'}</td>
                <td>${d.expiry_date ?? '—'}</td>
                <td><span class="badge badge-${badgeClass}">${status}</span></td>
            </tr>`;
        }).join('') : '<tr><td colspan="6" style="text-align:center;padding:24px;color:var(--muted)">No documents found</td></tr>';
    } catch(e) { document.querySelector('#docTable tbody').innerHTML = '<tr><td colspan="6" style="text-align:center;color:var(--muted)">Failed to load</td></tr>'; }
}

function openAddDocModal() { document.getElementById('addDocModal').style.display = 'flex'; }

document.getElementById('addDocForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const errAlert = document.getElementById('docError');
    const fd = new FormData(this);
    const body = {};
    fd.forEach((v,k) => { if(v) body[k] = v; });
    errAlert.style.display = 'none';
    try {
        await window.api('POST', '/api/documents', body);
        document.getElementById('addDocModal').style.display = 'none';
        this.reset();
        loadDocuments();
    } catch(err) {
        errAlert.textContent = err.data?.message || 'Failed to save.'; errAlert.style.display = 'block';
    }
});

loadDocuments();
</script>
@endpush
@endsection
