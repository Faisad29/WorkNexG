@extends('layouts.app')
@section('title', 'Employees')
@section('content')

<div class="page-header">
    <div>
        <h1>Employees</h1>
        <div class="breadcrumb"><span>Home</span> <span class="sep">›</span> <span>Employees</span></div>
    </div>
    <div class="page-actions">
        <button class="btn btn-ghost btn-sm">⬇ Export</button>
        <button class="btn btn-primary" onclick="openModal('addEmployeeModal')">+ Add Employee</button>
    </div>
</div>

{{-- Summary Stats --}}
<div class="stats-row" style="margin-bottom:20px">
    <div class="stat-card"><div class="stat-icon green">👥</div><div class="stat-value" id="sTotal">—</div><div class="stat-label">Total</div></div>
    <div class="stat-card"><div class="stat-icon primary">✅</div><div class="stat-value" id="sActive">—</div><div class="stat-label">Active</div></div>
    <div class="stat-card"><div class="stat-icon orange">⏸</div><div class="stat-value" id="sInactive">—</div><div class="stat-label">Inactive</div></div>
    <div class="stat-card"><div class="stat-icon red">⛔</div><div class="stat-value" id="sSuspended">—</div><div class="stat-label">Suspended</div></div>
</div>

{{-- Table Card --}}
<div class="card">
    {{-- Filters --}}
    <div class="filters-bar">
        <div class="search-wrap">
            <span class="s-icon">🔍</span>
            <input type="text" id="searchInput" placeholder="Search name, code, email...">
        </div>
        <select id="statusFilter" class="form-control" style="width:150px">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="suspended">Suspended</option>
        </select>
        <select id="salaryFilter" class="form-control" style="width:160px">
            <option value="">All Salary Types</option>
            <option value="monthly">Monthly</option>
            <option value="daily">Daily</option>
            <option value="hourly">Hourly</option>
        </select>
        <button class="btn btn-ghost btn-sm" onclick="resetFilters()">✕ Clear</button>
    </div>

    {{-- Table --}}
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll" style="cursor:pointer"></th>
                    <th>Employee</th>
                    <th>Code</th>
                    <th>Job Title</th>
                    <th>Site</th>
                    <th>Salary Type</th>
                    <th>Base Salary</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="employeeTableBody">
                <tr><td colspan="9" style="text-align:center;padding:40px;color:var(--muted)">Loading...</td></tr>
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="pagination-wrap">
        <span id="paginationInfo">—</span>
        <div class="pagination-pages" id="paginationPages"></div>
    </div>
</div>

{{-- Add Employee Modal --}}
<div class="modal-overlay" id="addEmployeeModal">
    <div class="modal modal-lg">
        <div class="modal-header">
            <div class="modal-title">Add New Employee</div>
            <button class="modal-close" onclick="closeModal('addEmployeeModal')">✕</button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger" id="empModalErr" style="display:none"></div>
            <form id="addEmployeeForm">
                <div class="form-row cols-2">
                    <div class="form-group">
                        <label class="form-label">Employee Code <span class="req">*</span></label>
                        <input name="employee_code" class="form-control" placeholder="EMP-001" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Full Name <span class="req">*</span></label>
                        <input name="full_name" class="form-control" placeholder="Ahmed Al-Qahtani" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input name="email" type="email" class="form-control" placeholder="email@company.com">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone</label>
                        <input name="phone" class="form-control" placeholder="+966500000000">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Job Title</label>
                        <input name="job_title" class="form-control" placeholder="Software Engineer">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nationality</label>
                        <input name="nationality" class="form-control" placeholder="Saudi">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Salary Type <span class="req">*</span></label>
                        <select name="salary_type" class="form-control" required>
                            <option value="monthly">Monthly</option>
                            <option value="daily">Daily</option>
                            <option value="hourly">Hourly</option>
                            <option value="project_based">Project Based</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Base Salary (SAR) <span class="req">*</span></label>
                        <div class="input-group">
                            <span class="input-prefix">SAR</span>
                            <input name="base_salary" type="number" step="0.01" class="form-control" placeholder="8000.00" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Join Date</label>
                        <input name="join_date" type="date" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Contract End Date</label>
                        <input name="contract_end_date" type="date" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status <span class="req">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="suspended">Suspended</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-ghost" onclick="closeModal('addEmployeeModal')">Cancel</button>
            <button class="btn btn-primary" onclick="saveEmployee()" id="saveEmpBtn">Save Employee</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentPage = 1;
let searchDebounce;

async function loadEmployees(page = 1) {
    currentPage = page;
    const search = document.getElementById('searchInput').value;
    const status = document.getElementById('statusFilter').value;
    const salary = document.getElementById('salaryFilter').value;
    const tbody = document.getElementById('employeeTableBody');
    tbody.innerHTML = '<tr><td colspan="9" style="text-align:center;padding:40px;color:var(--muted)"><span class="spinner" style="border-color:var(--primary);border-top-color:transparent"></span> Loading...</td></tr>';

    try {
        const params = new URLSearchParams({ page, ...(search && {search}), ...(status && {status}), ...(salary && {salary_type:salary}) });
        const res = await WN.api('GET', `/api/employees?${params}`);
        const data = res.data;
        const employees = data?.data ?? (Array.isArray(data) ? data : []);
        const meta = data?.meta ?? data;

        // Update stats
        if (meta?.total !== undefined) {
            document.getElementById('sTotal').textContent = meta.total;
        }

        renderTable(employees);
        renderPagination(meta);
    } catch(e) {
        tbody.innerHTML = '<tr><td colspan="9" style="text-align:center;padding:40px;color:var(--danger)">Failed to load employees</td></tr>';
    }
}

function renderTable(employees) {
    const tbody = document.getElementById('employeeTableBody');
    if (!employees.length) {
        tbody.innerHTML = `<tr><td colspan="9">
            <div class="empty-state">
                <div class="empty-icon">👥</div>
                <h3>No employees found</h3>
                <p>Try adjusting your search or filters</p>
                <button class="btn btn-primary" onclick="openModal('addEmployeeModal')">+ Add First Employee</button>
            </div>
        </td></tr>`;
        return;
    }
    const statusMap = {active:'success',inactive:'gray',suspended:'danger'};
    tbody.innerHTML = employees.map(e => `
        <tr>
            <td><input type="checkbox" value="${e.id}"></td>
            <td>
                <div style="display:flex;align-items:center;gap:10px">
                    <div class="tbl-avatar">${e.full_name[0]}</div>
                    <div>
                        <div style="font-weight:600">${e.full_name}</div>
                        <div style="font-size:.75rem;color:var(--muted)">${e.email ?? ''}</div>
                    </div>
                </div>
            </td>
            <td><code style="background:var(--primary-50);padding:2px 7px;border-radius:4px;font-size:.78rem;color:var(--primary)">${e.employee_code}</code></td>
            <td>${e.job_title ?? '—'}</td>
            <td>${e.site?.name ?? '—'}</td>
            <td><span class="badge badge-info">${e.salary_type}</span></td>
            <td style="font-weight:600">SAR ${parseFloat(e.base_salary).toLocaleString()}</td>
            <td><span class="badge badge-${statusMap[e.status]??'gray'}">${e.status}</span></td>
            <td>
                <div class="tbl-actions">
                    <button class="btn btn-ghost btn-icon btn-sm" title="View" onclick="viewEmployee('${e.id}')">👁</button>
                    <button class="btn btn-outline btn-icon btn-sm" title="Edit" onclick="editEmployee('${e.id}')">✏️</button>
                </div>
            </td>
        </tr>`).join('');
}

function renderPagination(meta) {
    const info = document.getElementById('paginationInfo');
    const pages = document.getElementById('paginationPages');
    if (!meta?.last_page) { info.textContent = ''; pages.innerHTML = ''; return; }
    info.textContent = `Showing ${meta.from??1}–${meta.to??meta.total} of ${meta.total} results`;
    let html = '';
    for (let i = 1; i <= Math.min(meta.last_page, 7); i++) {
        html += `<button class="pg-btn ${i === meta.current_page ? 'active' : ''}" onclick="loadEmployees(${i})">${i}</button>`;
    }
    pages.innerHTML = html;
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('salaryFilter').value = '';
    loadEmployees(1);
}

async function saveEmployee() {
    const btn = document.getElementById('saveEmpBtn');
    const errEl = document.getElementById('empModalErr');
    const form = document.getElementById('addEmployeeForm');
    const fd = new FormData(form);
    const body = {};
    fd.forEach((v,k) => { if(v) body[k] = v; });
    errEl.style.display = 'none';
    btn.disabled = true; btn.innerHTML = '<span class="spinner"></span>Saving...';
    try {
        await WN.api('POST', '/api/employees', body);
        closeModal('addEmployeeModal');
        form.reset();
        loadEmployees(1);
        showToast('Employee added successfully', 'success');
    } catch(err) {
        const msgs = err.data?.errors ? Object.values(err.data.errors).flat().join(' · ') : (err.data?.message || 'Failed to save.');
        errEl.textContent = msgs; errEl.style.display = 'block';
    } finally { btn.innerHTML = 'Save Employee'; btn.disabled = false; }
}

function viewEmployee(id) { showToast('Employee profile — extend this to a detail page', 'info'); }
function editEmployee(id) { showToast('Edit employee — extend this to an edit modal', 'info'); }

document.getElementById('searchInput').addEventListener('input', () => {
    clearTimeout(searchDebounce);
    searchDebounce = setTimeout(() => loadEmployees(1), 350);
});
document.getElementById('statusFilter').addEventListener('change', () => loadEmployees(1));
document.getElementById('salaryFilter').addEventListener('change', () => loadEmployees(1));
document.getElementById('selectAll').addEventListener('change', function() {
    document.querySelectorAll('#employeeTableBody input[type=checkbox]').forEach(cb => cb.checked = this.checked);
});

loadEmployees();
</script>
@endpush
@endsection
