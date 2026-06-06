@extends('layouts.app')
@section('title', 'Reports')
@section('content')
<div class="page-header"><h1>Reports</h1><p>Workforce analytics and summaries</p></div>
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px">
    <div class="card" style="cursor:pointer" onclick="alert('Attendance Report — export via API /api/attendance')">
        <div style="font-size:2rem;margin-bottom:8px">🕐</div>
        <div style="font-weight:700;margin-bottom:4px">Attendance Report</div>
        <div style="font-size:.85rem;color:var(--muted)">Daily check-in summaries, late arrivals, absent days</div>
    </div>
    <div class="card" style="cursor:pointer" onclick="alert('Payroll Report — view via Payroll module')">
        <div style="font-size:2rem;margin-bottom:8px">💰</div>
        <div style="font-weight:700;margin-bottom:4px">Payroll Report</div>
        <div style="font-size:.85rem;color:var(--muted)">Monthly salary breakdown per employee</div>
    </div>
    <div class="card" style="cursor:pointer" onclick="alert('Compliance Report — view via Compliance module')">
        <div style="font-size:2rem;margin-bottom:8px">🛡️</div>
        <div style="font-weight:700;margin-bottom:4px">Compliance Report</div>
        <div style="font-size:.85rem;color:var(--muted)">Document expiry status across all employees</div>
    </div>
    <div class="card" style="cursor:pointer" onclick="alert('Leave Report — view via Leave module')">
        <div style="font-size:2rem;margin-bottom:8px">🌴</div>
        <div style="font-weight:700;margin-bottom:4px">Leave Report</div>
        <div style="font-size:.85rem;color:var(--muted)">Leave requests and approval status</div>
    </div>
    <div class="card" style="cursor:pointer" onclick="alert('Audit Log — all create/update/delete actions')">
        <div style="font-size:2rem;margin-bottom:8px">📋</div>
        <div style="font-weight:700;margin-bottom:4px">Audit Log</div>
        <div style="font-size:.85rem;color:var(--muted)">Full history of system changes</div>
    </div>
</div>
@endsection
