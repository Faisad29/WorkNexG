@extends('layouts.app')
@section('title', 'Leave')
@section('content')

<div class="page-header">
    <div>
        <h1>🌴 Leave</h1>
        <div class="breadcrumb"><span>Home</span><span class="sep">›</span><span>Leave</span></div>
    </div>
</div>

<div id="moduleContent">
    <div style="text-align:center;padding:60px;color:var(--muted)">
        <div style="font-size:3rem;margin-bottom:12px">🌴</div>
        <div style="font-size:1rem;font-weight:600;color:var(--ink);margin-bottom:6px">Leave</div>
        <div style="font-size:.875rem;color:var(--muted)">Leave request management</div>
    </div>
</div>

@push('scripts')
<script>
// Leave module — JS logic here
(async () => {
    try {
        // Load data from API
    } catch(e) {}
})();
</script>
@endpush
@endsection
