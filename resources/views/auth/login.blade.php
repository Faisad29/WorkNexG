@extends('layouts.auth')
@section('title', 'Sign In')
@section('content')

<div class="auth-logo">
    <div class="auth-logo-icon">WN</div>
    <span class="auth-logo-name">Work<span>NexG</span></span>
</div>

<h1>Welcome back</h1>
<p class="auth-sub">Sign in to your workforce dashboard</p>

<div class="alert alert-danger" id="errAlert"></div>
<div class="alert alert-success" id="successAlert"></div>

<form id="loginForm" autocomplete="on">
    <div class="form-group">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" class="form-control" placeholder="admin@company.com"
               required autocomplete="email" id="emailInput">
    </div>
    <div class="form-group">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" placeholder="••••••••"
               required autocomplete="current-password" id="passInput">
    </div>
    <div class="form-group">
        <label class="form-label">Account Type</label>
        <select name="user_type" class="form-control" id="typeInput">
            <option value="tenant">Company User</option>
            <option value="platform">Platform Admin</option>
        </select>
    </div>
    <button type="submit" class="btn-auth" id="submitBtn">Sign In</button>
</form>

<div class="auth-link">
    New to WorkNexG? <a href="{{ route('register') }}">Register your company</a>
</div>

{{-- Demo credentials hint --}}
<div style="margin-top:20px;padding:12px 14px;background:#f8fafc;border:1px dashed #cbd5e1;border-radius:10px;font-size:.78rem;color:#64748b">
    <div style="font-weight:600;margin-bottom:4px">Demo credentials</div>
    admin@worknexg.test / password<br>
    supervisor@worknexg.test / password<br>
    employee@worknexg.test / password
</div>

@push('scripts')
<script>
const errAlert = document.getElementById('errAlert');
const successAlert = document.getElementById('successAlert');

// If already logged in, redirect
if (localStorage.getItem('worknexg_token')) {
    window.location.href = '/dashboard';
}

document.getElementById('loginForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = document.getElementById('submitBtn');
    errAlert.style.display = 'none';
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner"></span>Signing in...';

    try {
        const res = await fetch('/api/auth/login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({
                email: document.getElementById('emailInput').value,
                password: document.getElementById('passInput').value,
                user_type: document.getElementById('typeInput').value,
            })
        });
        const data = await res.json();
        if (!res.ok) {
            errAlert.textContent = data.message || 'Invalid credentials.';
            errAlert.style.display = 'block';
        } else {
            localStorage.setItem('worknexg_token', data.data.token);
            localStorage.setItem('worknexg_user', JSON.stringify(data.data.user));
            successAlert.textContent = 'Login successful! Redirecting...';
            successAlert.style.display = 'block';
            setTimeout(() => window.location.href = '/dashboard', 600);
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
@endpush
@endsection
