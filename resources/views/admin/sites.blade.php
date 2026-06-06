@extends('layouts.app')
@section('title', 'Sites')
@section('content')
<div class="page-header" style="display:flex;align-items:center;justify-content:space-between">
    <div><h1>Sites</h1><p>Manage work locations and geofencing</p></div>
    <button class="btn btn-primary" onclick="openAddSiteModal()">+ Add Site</button>
</div>
<div class="card">
    <div class="table-wrap">
        <table id="siteTable">
            <thead><tr><th>Name</th><th>Latitude</th><th>Longitude</th><th>Radius (m)</th><th>Status</th></tr></thead>
            <tbody><tr><td colspan="5" style="text-align:center;padding:24px;color:var(--muted)">Loading...</td></tr></tbody>
        </table>
    </div>
</div>

<div id="addSiteModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:200;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:12px;padding:28px;width:100%;max-width:440px">
        <div style="display:flex;justify-content:space-between;margin-bottom:20px">
            <h2 style="font-size:1.1rem;font-weight:700">Add Site</h2>
            <button onclick="document.getElementById('addSiteModal').style.display='none'" style="background:none;border:none;font-size:1.4rem;cursor:pointer">×</button>
        </div>
        <div class="alert alert-error" id="siteError" style="display:none"></div>
        <form id="addSiteForm">
            <div style="display:flex;flex-direction:column;gap:14px">
                <div><label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:5px;color:var(--muted)">Site Name *</label>
                    <input name="name" required style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:6px;font-size:.875rem" placeholder="Riyadh Main Site"></div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <div><label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:5px;color:var(--muted)">Latitude *</label>
                        <input name="latitude" type="number" step="any" required style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:6px;font-size:.875rem" placeholder="24.7136"></div>
                    <div><label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:5px;color:var(--muted)">Longitude *</label>
                        <input name="longitude" type="number" step="any" required style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:6px;font-size:.875rem" placeholder="46.6753"></div>
                </div>
                <div><label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:5px;color:var(--muted)">Radius (meters) *</label>
                    <input name="radius_meters" type="number" required value="150" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:6px;font-size:.875rem"></div>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;margin-top:18px">Save Site</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
async function loadSites() {
    try {
        const data = await window.api('GET', '/api/sites');
        const sites = data.data?.data ?? data.data ?? [];
        const tbody = document.querySelector('#siteTable tbody');
        tbody.innerHTML = sites.length ? sites.map(s => `
            <tr>
                <td><strong>${s.name}</strong></td>
                <td>${s.latitude}</td><td>${s.longitude}</td>
                <td>${s.radius_meters}m</td>
                <td><span class="badge badge-${s.is_active ? 'green' : 'red'}">${s.is_active ? 'Active' : 'Inactive'}</span></td>
            </tr>`).join('') : '<tr><td colspan="5" style="text-align:center;padding:24px;color:var(--muted)">No sites found</td></tr>';
    } catch(e) { document.querySelector('#siteTable tbody').innerHTML = '<tr><td colspan="5" style="text-align:center;color:var(--muted)">Failed to load</td></tr>'; }
}

function openAddSiteModal() { document.getElementById('addSiteModal').style.display = 'flex'; }

document.getElementById('addSiteForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const errAlert = document.getElementById('siteError');
    const fd = new FormData(this);
    const body = {};
    fd.forEach((v,k) => { if(v) body[k] = v; });
    body.radius_meters = parseInt(body.radius_meters);
    errAlert.style.display = 'none';
    try {
        await window.api('POST', '/api/sites', body);
        document.getElementById('addSiteModal').style.display = 'none';
        this.reset();
        loadSites();
    } catch(err) {
        errAlert.textContent = err.data?.message || 'Failed.'; errAlert.style.display = 'block';
    }
});

loadSites();
</script>
@endpush
@endsection
