<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — WorkNexG</title>
    <style>
        :root { --primary: #12754f; --primary-dark: #0c5539; --bg: #f7fbf9; --card: #ffffff; --border: #d7ebdf; --ink: #0f2a20; --muted: #476a5c; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: "Segoe UI", Tahoma, Verdana, sans-serif; background: var(--bg); color: var(--ink);
            min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px 0; }
        .container { width: 100%; max-width: 480px; padding: 20px; }
        .brand { text-align: center; margin-bottom: 24px; }
        .brand h1 { font-size: 1.8rem; font-weight: 800; color: var(--primary); }
        .brand p { color: var(--muted); font-size: .9rem; }
        .card { background: var(--card); border: 1px solid var(--border); border-radius: 14px; padding: 32px; box-shadow: 0 8px 24px rgba(16,42,32,.08); }
        .card h2 { font-size: 1.2rem; font-weight: 700; margin-bottom: 24px; }
        .row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .form-group { margin-bottom: 16px; }
        label { display: block; font-size: .82rem; font-weight: 600; margin-bottom: 6px; color: var(--muted); }
        input, select { width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 7px; font-size: .9rem; outline: none; }
        input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(18,117,79,.12); }
        .btn { width: 100%; padding: 12px; background: var(--primary); color: #fff; border: none; border-radius: 7px; font-size: 1rem; font-weight: 600; cursor: pointer; }
        .btn:hover { background: var(--primary-dark); }
        .links { text-align: center; margin-top: 16px; font-size: .85rem; color: var(--muted); }
        .links a { color: var(--primary); text-decoration: none; font-weight: 600; }
        .alert { padding: 10px 14px; border-radius: 7px; margin-bottom: 16px; font-size: .85rem; display: none; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .section-title { font-size: .78rem; font-weight: 700; text-transform: uppercase; color: var(--muted); letter-spacing: .06em; margin-bottom: 12px; padding-bottom: 6px; border-bottom: 1px solid var(--border); }
    </style>
</head>
<body>
<div class="container">
    <div class="brand">
        <h1>⚡ WorkNexG</h1>
        <p>Register your company</p>
    </div>
    <div class="card">
        <h2>Create Company Account</h2>
        <div class="alert alert-error" id="errorAlert"></div>
        <div class="alert alert-success" id="successAlert"></div>

        <form id="registerForm">
            <div class="section-title">Company Information</div>
            <div class="form-group">
                <label>Company Name</label>
                <input type="text" name="company_name" required placeholder="My Company Ltd">
            </div>
            <div class="row2">
                <div class="form-group">
                    <label>Country</label>
                    <select name="country">
                        <option value="KSA">Saudi Arabia (KSA)</option>
                        <option value="UAE">UAE</option>
                        <option value="Kuwait">Kuwait</option>
                        <option value="Qatar">Qatar</option>
                        <option value="Bahrain">Bahrain</option>
                        <option value="Oman">Oman</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Timezone</label>
                    <select name="timezone">
                        <option value="Asia/Riyadh">Asia/Riyadh (KSA)</option>
                        <option value="Asia/Dubai">Asia/Dubai (UAE)</option>
                        <option value="Asia/Kuwait">Asia/Kuwait</option>
                        <option value="Asia/Qatar">Asia/Qatar</option>
                    </select>
                </div>
            </div>

            <div class="section-title" style="margin-top:8px">Admin Account</div>
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" required placeholder="Your full name">
            </div>
            <div class="row2">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required placeholder="admin@company.com">
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="tel" name="phone" placeholder="+966500000000">
                </div>
            </div>
            <div class="row2">
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required placeholder="Min 8 characters">
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" required placeholder="Repeat password">
                </div>
            </div>

            <button type="submit" class="btn" id="submitBtn">Create Account</button>
        </form>

        <div class="links">Already have an account? <a href="/login">Sign in</a></div>
    </div>
</div>

<script>
document.getElementById('registerForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('submitBtn');
    const errAlert = document.getElementById('errorAlert');
    const successAlert = document.getElementById('successAlert');
    const fd = new FormData(this);
    const body = Object.fromEntries(fd.entries());

    btn.textContent = 'Creating...'; btn.disabled = true;
    errAlert.style.display = 'none'; successAlert.style.display = 'none';

    try {
        const res = await fetch('/api/auth/register', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(body),
        });
        const data = await res.json();
        if (!res.ok) {
            const msgs = data.errors ? Object.values(data.errors).flat().join(', ') : (data.message || 'Registration failed.');
            errAlert.textContent = msgs; errAlert.style.display = 'block';
        } else {
            localStorage.setItem('worknexg_token', data.data.token);
            localStorage.setItem('worknexg_user', JSON.stringify(data.data.user));
            successAlert.textContent = 'Company registered! Redirecting to dashboard...';
            successAlert.style.display = 'block';
            setTimeout(() => window.location.href = '/dashboard', 700);
        }
    } catch (err) {
        errAlert.textContent = 'Network error. Please try again.'; errAlert.style.display = 'block';
    } finally {
        btn.textContent = 'Create Account'; btn.disabled = false;
    }
});
</script>
</body>
</html>
