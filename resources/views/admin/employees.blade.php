@extends('layouts.app')
@section('title', 'Employees')
@section('content')

<div class="page-header" style="display:flex;align-items:center;justify-content:space-between">
    <div>
        <h1>Employees</h1>
        <p>Manage your workforce</p>
    </div>
    <button class="btn btn-primary" onclick="openCreateModal()">+ Add Employee</button>
</div>

<div class="card">
    <div style="display:flex;gap:12px;margin-bottom:16px">
        <input type="search" id="searchInput" placeholder="Search by name, code, email..." style="flex:1;padding:8px 12px;border:1px solid var(--border);border-radius:6px;font-size:.875rem">
        <select id="statusFilter" style="padding:8px 12px;border:1px solid var(--border);border-radius:6px;font-size:.875rem">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="suspended">Suspended</option>
        </select>
    </div>
    <div class="table-wrap">
        <table id="employeeTable">
            <thead>
                <tr>
                    <th>Code</th><th>Name</th><th>Job Title</th><th>Site</th>
                    <th>Salary Type</th><th>Base Salary</th><th>Status</th><th>Actions</th>
                </tr>
            </thead>
            <tbody><tr><td colspan="8" style="text-align:center;padding:24px;color:var(--muted)">Loading...</td></tr></tbody>
        </table>
    </div>
    <div id="pagination" style="display:flex;justify-content:flex-end;gap:8px;margin-top:14px"></div>
</div>

<!-- Create Modal -->
<div id="createModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:200;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:12px;padding:28px;width:100%;max-width:560px;max-height:90vh;overflow-y:auto">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
            <h2 style="font-size:1.1rem;font-weight:700">Add Employee</h2>
            <button onclick="closeCreateModal()" style="background:none;border:none;font-size:1.4rem;cursor:pointer;color:var(--muted)">×</button>
        </div>
        <div class="alert alert-error" id="modalError" style="display:none"></div>
        <form id="createForm">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                <div><label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:5px;color:var(--muted)">Employee Code *</label>
                    <input name="employee_code" required placeholder="EMP-001" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:6px;font-size:.875rem"></div>
                <div><label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:5px;color:var(--muted)">Full Name *</label>
                    <input name="full_name" required placeholder="Ahmed Al-Qahtani" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:6px;font-size:.875rem"></div>
                <div><label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:5px;color:var(--muted)">Email</label>
                    <input name="email" type="email" placeholder="email@example.com" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:6px;font-size:.875rem"></div>
                <div><label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:5px;color:var(--muted)">Phone</label>
                    <input name="phone" placeholder="+966500000000" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:6px;font-size:.875rem"></div>
                <div><label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:5px;color:var(--muted)">Job Title</label>
                    <input name="job_title" placeholder="Software Engineer" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:6px;font-size:.875rem"></div>
                <div><label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:5px;color:var(--muted)">Nationality</label>
                    <input name="nationality" placeholder="Saudi" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:6px;font-size:.875rem"></div>
                <div><label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:5px;color:var(--muted)">Salary Type *</label>
                    <select name="salary_type" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:6px;font-size:.875rem">
                        <option value="monthly">Monthly</option>
                        <option value="daily">Daily</option>
                        <option value="hourly">Hourly</option>
                        <option value="project_based">Project Based</option>
                    </select></div>
                <div><label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:5px;color:var(--muted)">Base Salary (SAR) *</label>
                    <input name="base_salary" type="number" step="0.01" required placeholder="8000.00" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:6px;font-size:.875rem"></div>
                <div><label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:5px;color:var(--muted)">Join Date</label>
                    <input name="join_date" type="date" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:6px;font-size:.875rem"></div>
                <div><label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:5px;color:var(--muted)">Contract End Date</label>
                    <input name="contract_end_date" type="date" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:6px;font-size:.875rem"></div>
                <div><label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:5px;color:var(--muted)">Status *</label>
                    <select name="status" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:6px;font-size:.875rem">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="suspended">Suspended</option>
                    </select></div>
            </div>
            <div style="display:flex;gap:10px;margin-top:20px">
                <button type="submit" class="btn btn-primary" style="flex:1" id="saveBtn">Save Employee</button>
                <button type="button" onclick="closeCreateModal()" class="btn btn-outline" style="flex:1">Cancel</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let currentPage = 1;

async function loadEmployees(page = 1) {
    const search = document.getElementById('searchInput').value;
    const status = document.getElementById('statusFilter').value;
    try {
        const data = await window.api('GET', `/api/employees?page=${page}&search=${encodeURIComponent(search)}&status=${status}`);
        renderTable(data.data?.data ?? data.data ?? []);
        renderPagination(data.data);
    } catch(e) {
        document.querySelector('#employeeTable tbody').innerHTML = '<tr><td colspan="8" style="text-align:center;color:var(--muted)">Failed to load employees</td></tr>';
    }
}

function renderTable(employees) {
    const tbody = document.querySelector('#employeeTable tbody');
    if (!employees.length) {
        tbody.innerHTML = '<tr><td colspan="8" style="text-align:center;padding:24px;color:var(--muted)">No employees found</td></tr>';
        return;
    }
    tbody.innerHTML = employees.map(e => `
        <tr>
            <td><code style="background:var(--primary-light);padding:2px 6px;border-radius:4px;font-size:.8rem">${e.employee_code}</code></td>
            <td><strong>${e.full_name}</strong>${e.email ? `<br><small style="color:var(--muted)">${e.email}</small>` : ''}</td>
            <td>${e.job_title ?? '—'}</td>
            <td>${e.site_id ?? '—'}</td>
            <td><span class="badge badge-blue">${e.salary_type}</span></td>
            <td>SAR ${parseFloat(e.base_salary).toLocaleString()}</td>
            <td><span class="badge badge-${e.status === 'active' ? 'green' : e.status === 'inactive' ? 'gray' : 'red'}">${e.status}</span></td>
            <td><button class="btn btn-outline btn-sm" onclick="viewEmployee('${e.id}')">View</button></td>
        </tr>`).join('');
}

function renderPagination(meta) {
    if (!meta?.last_page || meta.last_page <= 1) { document.getElementById('pagination').innerHTML = ''; return; }
    let html = '';
    for (let i = 1; i <= meta.last_page; i++) {
        html += `<button onclick="loadEmployees(${i})" class="btn btn-sm ${i === meta.current_page ? 'btn-primary' : 'btn-outline'}">${i}</button>`;
    }
    document.getElementById('pagination').innerHTML = html;
}

function openCreateModal() { document.getElementById('createModal').style.display = 'flex'; }
function closeCreateModal() { document.getElementById('createModal').style.display = 'none'; document.getElementById('createForm').reset(); }

document.getElementById('createForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const saveBtn = document.getElementById('saveBtn');
    const errAlert = document.getElementById('modalError');
    const fd = new FormData(this);
    const body = {};
    fd.forEach((v, k) => { if (v) body[k] = v; });

    saveBtn.textContent = 'Saving...'; saveBtn.disabled = true;
    errAlert.style.display = 'none';

    try {
        await window.api('POST', '/api/employees', body);
        closeCreateModal();
        loadEmployees();
    } catch(err) {
        const msgs = err.data?.errors ? Object.values(err.data.errors).flat().join(', ') : (err.data?.message || 'Failed to save.');
        errAlert.textContent = msgs; errAlert.style.display = 'block';
    } finally {
        saveBtn.textContent = 'Save Employee'; saveBtn.disabled = false;
    }
});

function viewEmployee(id) { alert('Employee ID: ' + id + '\n(Full detail view — extend as needed)'); }

let debounce;
document.getElementById('searchInput').addEventListener('input', () => { clearTimeout(debounce); debounce = setTimeout(() => loadEmployees(1), 350); });
document.getElementById('statusFilter').addEventListener('change', () => loadEmployees(1));

loadEmployees();
</script>
@endpush
@endsection
