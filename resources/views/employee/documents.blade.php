@extends('layouts.app')
@section('title', 'My Documents')
@section('content')
<div class="page-header"><h1>My Documents</h1><p>Your compliance documents and expiry dates</p></div>
<div class="card">
    <div class="table-wrap">
        <table id="myDocsTable">
            <thead><tr><th>Type</th><th>Document Number</th><th>Issue Date</th><th>Expiry Date</th><th>Status</th></tr></thead>
            <tbody><tr><td colspan="5" style="text-align:center;padding:24px;color:var(--muted)">Loading...</td></tr></tbody>
        </table>
    </div>
</div>
@push('scripts')
<script>
(async () => {
    try {
        const data = await window.api('GET', '/api/documents');
        const docs = data.data?.data ?? data.data ?? [];
        const now = new Date(), in30 = new Date(now.getTime()+30*24*60*60*1000);
        const tbody = document.querySelector('#myDocsTable tbody');
        tbody.innerHTML = docs.length ? docs.map(d => {
            const exp = d.expiry_date ? new Date(d.expiry_date) : null;
            let status = '—', bc = 'gray';
            if (exp) { if (exp < now) { status='Expired'; bc='red'; } else if (exp<=in30) { status='Expiring Soon'; bc='yellow'; } else { status='Valid'; bc='green'; } }
            return `<tr><td><span class="badge badge-blue">${d.type}</span></td><td>${d.document_number??'—'}</td><td>${d.issue_date??'—'}</td><td>${d.expiry_date??'—'}</td><td><span class="badge badge-${bc}">${status}</span></td></tr>`;
        }).join('') : '<tr><td colspan="5" style="text-align:center;padding:24px;color:var(--muted)">No documents</td></tr>';
    } catch(e) { document.querySelector('#myDocsTable tbody').innerHTML = '<tr><td colspan="5" style="text-align:center;color:var(--muted)">Failed to load</td></tr>'; }
})();
</script>
@endpush
@endsection
