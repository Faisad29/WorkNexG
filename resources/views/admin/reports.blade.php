@extends('layouts.app')
@section('title', 'Reports')
@section('content')

<div class="page-header">
    <div>
        <h1>📊 Reports</h1>
        <div class="breadcrumb"><span>Home</span><span class="sep">›</span><span>Reports</span></div>
    </div>
</div>

<div id="moduleContent">
    <div style="text-align:center;padding:60px;color:var(--muted)">
        <div style="font-size:3rem;margin-bottom:12px">📊</div>
        <div style="font-size:1rem;font-weight:600;color:var(--ink);margin-bottom:6px">Reports</div>
        <div style="font-size:.875rem;color:var(--muted)">Analytics and exports</div>
    </div>
</div>

@push('scripts')
<script>
// Reports module — JS logic here
(async () => {
    try {
        // Load data from API
    } catch(e) {}
})();
</script>
@endpush
@endsection
