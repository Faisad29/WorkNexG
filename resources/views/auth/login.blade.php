<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — WorkNexG</title>
    <style>
        :root { --primary: #12754f; --primary-dark: #0c5539; --bg: #f7fbf9; --card: #ffffff; --border: #d7ebdf; --ink: #0f2a20; --muted: #476a5c; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: "Segoe UI", Tahoma, Verdana, sans-serif; background: var(--bg); color: var(--ink);
            min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .container { width: 100%; max-width: 420px; padding: 20px; }
        .brand { text-align: center; margin-bottom: 28px; }
        .brand h1 { font-size: 1.8rem; font-weight: 800; color: var(--primary); }
        .brand p { color: var(--muted); font-size: .9rem; margin-top: 4px; }
        .card { background: var(--card); border: 1px solid var(--border); border-radius: 14px; padding: 32px; box-shadow: 0 8px 24px rgba(16,42,32,.08); }
        .card h2 { font-size: 1.2rem; font-weight: 700; margin-bottom: 24px; }
        .form-group { margin-bottom: 18px; }
        label { display: block; font-size: .82rem; font-weight: 600; margin-bottom: 6px; color: var(--muted); }
        input { width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 7px; font-size: .9rem; outline: none; background: #fff; }
        input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(18,117,79,.12); }
        .btn { width: 100%; padding: 12px; background: var(--primary); color: #fff; border: none; border-radius: 7px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background .15s; }
        .btn:hover { background: var(--primary-dark); }
        .links { text-align: center; margin-top: 18px; font-size: .85rem; color: var(--muted); }
        .links a { color: var(--primary); text-decoration: none; font-weight: 600; }
        .alert { padding: 10px 14px; border-radius: 7px; margin-bottom: 16px; font-size: .85rem; display: none; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .spinner { display: inline-block; width: 16px; height: 16px; border: 2px solid rgba(255,255,255,.4); border-top-color: #fff; border-radius: 50%; animation: spin .7s linear infinite; vertical-align: middle; margin-right: 6px; }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body>
<div class="container">
    <div class="brand">
        <h1>⚡ WorkNexG</h1>
        <p>Workforce Management System</p>
    </div>
    <div class="card">
        <h2>Sign In</h2>
        <div class="alert alert-error" id="errorAlert"></div>
        <div class="alert alert-success" id="successAlert"></div>

        <form id="loginForm">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required placeholder="admin@worknexg.test" autocomplete="email">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="••••••••" autocomplete="current-password">
            </div>
            <button type="submit" class="btn" id="submitBtn">Sign In</button>
        </form>

        <div class="links">
            Don't have an account? <a href="/register">Register your company</a>
        </div>
    </div>
</div>

<script>
const TOKEN_KEY = 'worknexg_token';
const USER_KEY = 'worknexg_user';
const ORG_KEY = 'worknexg_org_id';

function resolveRedirectPath(user) {
    // echo'<pre/>';print_r(user);exit;
    if (!user || typeof user !== 'object') {
        return '/dashboard';
    }

    if (typeof user.redirect_to === 'string' && user.redirect_to.startsWith('/')) {
        return user.redirect_to;
    }

    const role = (user.role_slug || user.role || '').toString().toLowerCase();

    if (role === 'employee') {
        return '/my-attendance';
    }
    if (role === 'supervisor') {
        return '/attendance';
    }
    if (role === 'hr-manager' || role === 'hr') {
        return '/employees';
    }
    if (role === 'accountant') {
        return '/payroll';
    }

    return '/dashboard';
}

if (localStorage.getItem(TOKEN_KEY)) {
    let cachedUser = null;
    try {
        cachedUser = JSON.parse(localStorage.getItem(USER_KEY) || 'null');
    } catch (e) {
        cachedUser = null;
    }

    window.location.href = resolveRedirectPath(cachedUser);
}

document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('submitBtn');
    const errAlert = document.getElementById('errorAlert');
    const successAlert = document.getElementById('successAlert');

    btn.innerHTML = '<span class="spinner"></span>Signing in...';
    btn.disabled = true;
    errAlert.style.display = 'none';

    try {
        const res = await fetch('/api/auth/login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({
                email: document.getElementById('email').value,
                password: document.getElementById('password').value,
            })
        });
        const data = await res.json();

        if (!res.ok) {
            errAlert.textContent = data.message || 'Invalid credentials. Please try again.';
            errAlert.style.display = 'block';
        } else {
            localStorage.setItem(TOKEN_KEY, data.data.token);
            localStorage.setItem(USER_KEY, JSON.stringify(data.data.user));

            if (data?.data?.user?.primary_org_id) {
                localStorage.setItem(ORG_KEY, data.data.user.primary_org_id);
            }

            successAlert.textContent = 'Login successful! Redirecting...';
            successAlert.style.display = 'block';
            console.log('User to:', data.data.user);
            console.log('Redirecting to:', resolveRedirectPath(data.data.user));
            setTimeout(() => window.location.href = resolveRedirectPath(data.data.user), 500);
        }
    } catch (err) {
        errAlert.textContent = 'Network error. Please try again.';
        errAlert.style.display = 'block';
    } finally {
        btn.innerHTML = 'Sign In';
        btn.disabled = false;
    }
});
</script>
</body>
</html>
