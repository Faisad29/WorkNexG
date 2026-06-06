<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'WorkNexG') — Workforce Management</title>
    <style>
        :root {
            --primary: #12754f;
            --primary-dark: #0c5539;
            --primary-light: #e5f4ed;
            --bg: #f7fbf9;
            --card: #ffffff;
            --border: #d7ebdf;
            --ink: #0f2a20;
            --muted: #476a5c;
            --danger: #dc2626;
            --warning: #d97706;
            --success: #059669;
            --sidebar-w: 240px;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: "Segoe UI", Tahoma, Verdana, sans-serif; background: var(--bg); color: var(--ink); }

        /* Topbar */
        .topbar {
            position: fixed; top: 0; left: 0; right: 0; height: 56px;
            background: var(--primary); color: #fff;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 20px; z-index: 100; box-shadow: 0 2px 8px rgba(0,0,0,.15);
        }
        .topbar .brand { font-size: 1.25rem; font-weight: 700; letter-spacing: -.5px; }
        .topbar .topbar-right { display: flex; align-items: center; gap: 16px; }
        .topbar .user-badge { font-size: .85rem; opacity: .9; }
        .topbar a { color: #fff; text-decoration: none; font-size: .85rem; opacity: .85; }
        .topbar a:hover { opacity: 1; }

        /* Sidebar */
        .sidebar {
            position: fixed; left: 0; top: 56px; bottom: 0;
            width: var(--sidebar-w); background: var(--card);
            border-right: 1px solid var(--border);
            overflow-y: auto; z-index: 50; padding: 16px 0;
        }
        .sidebar .nav-section { padding: 4px 16px 8px; font-size: .7rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .08em; color: var(--muted); }
        .sidebar a {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 20px; color: var(--ink); text-decoration: none;
            font-size: .875rem; border-left: 3px solid transparent;
            transition: background .15s, border-color .15s;
        }
        .sidebar a:hover { background: var(--primary-light); }
        .sidebar a.active { background: var(--primary-light); border-left-color: var(--primary); color: var(--primary); font-weight: 600; }
        .sidebar a .icon { width: 18px; text-align: center; }

        /* Main content */
        .main { margin-left: var(--sidebar-w); padding-top: 56px; min-height: 100vh; }
        .page-content { padding: 28px 32px; }
        .page-header { margin-bottom: 24px; }
        .page-header h1 { font-size: 1.5rem; font-weight: 700; color: var(--ink); }
        .page-header p { color: var(--muted); font-size: .9rem; margin-top: 4px; }

        /* Cards */
        .card { background: var(--card); border: 1px solid var(--border); border-radius: 10px; padding: 20px; }
        .card-title { font-size: 1rem; font-weight: 600; margin-bottom: 16px; }

        /* Stats grid */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; }
        .stat-card { background: var(--card); border: 1px solid var(--border); border-radius: 10px; padding: 18px 20px; }
        .stat-card .stat-label { font-size: .78rem; color: var(--muted); text-transform: uppercase; letter-spacing: .05em; }
        .stat-card .stat-value { font-size: 1.8rem; font-weight: 700; color: var(--primary); margin-top: 4px; }
        .stat-card .stat-note { font-size: .78rem; color: var(--muted); margin-top: 2px; }

        /* Tables */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: .875rem; }
        th { text-align: left; padding: 10px 14px; background: var(--primary-light); color: var(--primary); font-weight: 600; font-size: .78rem; text-transform: uppercase; letter-spacing: .04em; }
        td { padding: 10px 14px; border-bottom: 1px solid var(--border); }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: var(--primary-light); }

        /* Badges */
        .badge { display: inline-block; padding: 2px 10px; border-radius: 20px; font-size: .75rem; font-weight: 600; }
        .badge-green { background: #d1fae5; color: #065f46; }
        .badge-yellow { background: #fef3c7; color: #92400e; }
        .badge-red { background: #fee2e2; color: #991b1b; }
        .badge-blue { background: #dbeafe; color: #1e40af; }
        .badge-gray { background: #f3f4f6; color: #374151; }

        /* Buttons */
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 18px;
            border-radius: 6px; font-size: .875rem; font-weight: 500; cursor: pointer;
            border: none; text-decoration: none; transition: opacity .15s; }
        .btn:hover { opacity: .88; }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-outline { background: transparent; border: 1px solid var(--primary); color: var(--primary); }
        .btn-danger { background: var(--danger); color: #fff; }
        .btn-sm { padding: 5px 12px; font-size: .8rem; }

        /* Alert */
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-size: .875rem; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .alert-warning { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
    </style>
    @stack('styles')
</head>
<body>
<nav class="topbar">
    <div class="brand">⚡ WorkNexG</div>
    <div class="topbar-right">
        <span class="user-badge">{{ auth()->user()->name ?? 'Guest' }} · {{ ucfirst(auth()->user()->role ?? '') }}</span>
        <a href="/logout" id="logout-link" onclick="doLogout(event)">Logout</a>
    </div>
</nav>

<aside class="sidebar">
    @php $role = auth()->user()->role ?? 'employee'; @endphp

    <div class="nav-section">Main</div>
    <a href="/dashboard" class="{{ request()->is('dashboard') ? 'active' : '' }}">
        <span class="icon">🏠</span> Dashboard
    </a>

    @if(in_array($role, ['admin', 'hr']))
    <div class="nav-section">Workforce</div>
    <a href="/employees" class="{{ request()->is('employees*') ? 'active' : '' }}">
        <span class="icon">👥</span> Employees
    </a>
    <a href="/sites" class="{{ request()->is('sites*') ? 'active' : '' }}">
        <span class="icon">📍</span> Sites
    </a>
    @endif

    @if(in_array($role, ['admin', 'hr', 'supervisor']))
    <div class="nav-section">Operations</div>
    <a href="/attendance" class="{{ request()->is('attendance*') ? 'active' : '' }}">
        <span class="icon">🕐</span> Attendance
    </a>
    @endif

    @if($role === 'employee')
    <div class="nav-section">My Records</div>
    <a href="/my-attendance" class="{{ request()->is('my-attendance*') ? 'active' : '' }}">
        <span class="icon">🕐</span> My Attendance
    </a>
    <a href="/my-payroll" class="{{ request()->is('my-payroll*') ? 'active' : '' }}">
        <span class="icon">💰</span> My Payroll
    </a>
    <a href="/my-documents" class="{{ request()->is('my-documents*') ? 'active' : '' }}">
        <span class="icon">📄</span> My Documents
    </a>
    @endif

    @if(in_array($role, ['admin', 'accountant']))
    <a href="/payroll" class="{{ request()->is('payroll*') ? 'active' : '' }}">
        <span class="icon">💰</span> Payroll
    </a>
    @endif

    @if(in_array($role, ['admin', 'hr']))
    <div class="nav-section">Compliance</div>
    <a href="/compliance" class="{{ request()->is('compliance*') ? 'active' : '' }}">
        <span class="icon">🛡️</span> Compliance
    </a>
    <a href="/leave" class="{{ request()->is('leave*') ? 'active' : '' }}">
        <span class="icon">🌴</span> Leave
    </a>
    @endif

    @if($role === 'admin')
    <div class="nav-section">Admin</div>
    <a href="/notifications" class="{{ request()->is('notifications*') ? 'active' : '' }}">
        <span class="icon">🔔</span> Notifications
    </a>
    <a href="/reports" class="{{ request()->is('reports*') ? 'active' : '' }}">
        <span class="icon">📊</span> Reports
    </a>
    <a href="/settings" class="{{ request()->is('settings*') ? 'active' : '' }}">
        <span class="icon">⚙️</span> Settings
    </a>
    @endif
</aside>

<main class="main">
    <div class="page-content">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @yield('content')
    </div>
</main>

<script>
const TOKEN_KEY = 'worknexg_token';
const token = localStorage.getItem(TOKEN_KEY);

// If no token, redirect to login
if (!token && !window.location.pathname.startsWith('/login') && !window.location.pathname.startsWith('/register') && window.location.pathname !== '/') {
    window.location.href = '/login';
}

// Attach auth header to all fetch requests
const origFetch = window.fetch;
window.fetch = function(url, opts = {}) {
    opts.headers = opts.headers || {};
    if (token) opts.headers['Authorization'] = 'Bearer ' + token;
    opts.headers['Accept'] = 'application/json';
    opts.headers['Content-Type'] = opts.headers['Content-Type'] || 'application/json';
    return origFetch(url, opts);
};

async function doLogout(e) {
    e.preventDefault();
    window.location.href = '/logout';
}

window.api = async function(method, url, body = null) {
    const opts = { method, headers: { Authorization: 'Bearer ' + (localStorage.getItem(TOKEN_KEY) || '') } };
    if (body) { opts.body = JSON.stringify(body); opts.headers['Content-Type'] = 'application/json'; }
    const res = await fetch(url, opts);
    const data = await res.json().catch(() => ({}));
    if (!res.ok) throw { status: res.status, data };
    return data;
};
</script>
@stack('scripts')
</body>
</html>
