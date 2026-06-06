@extends('layouts.app')
@section('title', 'My Payroll')
@section('content')
<div class="page-header"><h1>My Payroll</h1><p>Your salary records</p></div>
<div class="card">
    <div class="table-wrap">
        <table id="myPayrollTable">
            <thead><tr><th>Period</th><th>Base Salary</th><th>Overtime</th><th>Deductions</th><th>Net Salary</th><th>Status</th></tr></thead>
            <tbody><tr><td colspan="6" style="text-align:center;padding:24px;color:var(--muted)">Loading...</td></tr></tbody>
        </table>
    </div>
</div>
@push('scripts')
<script>
(async () => {
    try {
        const data = await window.api('GET', '/api/payroll/my-items');
        const items = data.data?.data ?? data.data ?? [];
        const tbody = document.querySelector('#myPayrollTable tbody');
        tbody.innerHTML = items.length ? items.map(i => `
            <tr>
                <td>${i.payroll?.month ? new Date(2000, i.payroll.month-1).toLocaleString('default',{month:'long'}) : '—'} ${i.payroll?.year ?? ''}</td>
                <td>SAR ${parseFloat(i.base_salary).toLocaleString(undefined,{minimumFractionDigits:2})}</td>
                <td>SAR ${parseFloat(i.overtime_amount).toLocaleString(undefined,{minimumFractionDigits:2})}</td>
                <td>SAR ${parseFloat(i.deductions).toLocaleString(undefined,{minimumFractionDigits:2})}</td>
                <td><strong>SAR ${parseFloat(i.net_salary).toLocaleString(undefined,{minimumFractionDigits:2})}</strong></td>
                <td><span class="badge badge-${i.status === 'paid' ? 'green' : 'gray'}">${i.status}</span></td>
            </tr>`).join('') : '<tr><td colspan="6" style="text-align:center;padding:24px;color:var(--muted)">No payroll records</td></tr>';
    } catch(e) { document.querySelector('#myPayrollTable tbody').innerHTML = '<tr><td colspan="6" style="text-align:center;color:var(--muted)">Not available</td></tr>'; }
})();
</script>
@endpush
@endsection
