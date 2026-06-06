@extends('layouts.app')
@section('title', 'Settings')
@section('content')
<div class="page-header"><h1>Settings</h1><p>System and company configuration</p></div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
    <div class="card">
        <div class="card-title">Company Information</div>
        <div id="companyInfo" style="color:var(--muted);font-size:.9rem">Loading...</div>
    </div>
    <div class="card">
        <div class="card-title">API Documentation</div>
        <p style="font-size:.875rem;color:var(--muted);margin-bottom:12px">Access the full API reference for integrations.</p>
        <a href="/api/docs" target="_blank" class="btn btn-outline">View API Docs</a>
    </div>
    <div class="card">
        <div class="card-title">Subscription</div>
        <div id="subInfo" style="color:var(--muted);font-size:.9rem">Loading...</div>
    </div>
    <div class="card">
        <div class="card-title">Health</div>
        <p style="font-size:.875rem;color:var(--muted);margin-bottom:12px">System health status.</p>
        <a href="/up" target="_blank" class="btn btn-outline">Check Health</a>
    </div>
</div>

@push('scripts')
<script>
(async () => {
    const user = JSON.parse(localStorage.getItem('worknexg_user') || '{}');
    document.getElementById('companyInfo').innerHTML = `
        <dl style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
            <dt style="font-weight:600;font-size:.8rem">Company</dt><dd>${user.org_id ?? '—'}</dd>
            <dt style="font-weight:600;font-size:.8rem">Email</dt><dd>${user.email ?? '—'}</dd>
            <dt style="font-weight:600;font-size:.8rem">Role</dt><dd>${user.role ?? '—'}</dd>
        </dl>`;
    document.getElementById('subInfo').innerHTML = '<p style="font-size:.875rem">Manage subscriptions via API: <code>POST /api/subscriptions</code></p>';
})();
</script>
@endpush
@endsection
