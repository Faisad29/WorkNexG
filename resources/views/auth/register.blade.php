@extends('layouts.auth')
@section('title', 'Register Company')
@section('content')

<div class="auth-logo">
    <div class="auth-logo-icon">WN</div>
    <span class="auth-logo-name">Work<span>NexG</span></span>
</div>

<h1>Create account</h1>
<p class="auth-sub">Register your company in 60 seconds</p>

<div class="alert alert-danger" id="errAlert"></div>
<div class="alert alert-success" id="successAlert"></div>

<form id="registerForm">
    <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#94a3b8;margin-bottom:12px">Company Information</div>
    <div class="form-group">
        <label class="form-label">Company Name <span style="color:var(--danger)">*</span></label>
        <input type="text" name="company_name" class="form-control" placeholder="My Company Ltd" required>
    </div>
    <div class="form-row-2">
        <div class="form-group">
            <label class="form-label">Country</label>
            <select name="country" class="form-control">
                <option value="KSA">Saudi Arabia (KSA)</option>
                <option value="UAE">UAE</option>
                <option value="Kuwait">Kuwait</option>
                <option value="Qatar">Qatar</option>
                <option value="Bahrain">Bahrain</option>
                <option value="Oman">Oman</option>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Timezone</label>
            <select name="timezone" class="form-control">
                <option value="Asia/Riyadh">Asia/Riyadh</option>
                <option value="Asia/Dubai">Asia/Dubai</option>
                <option value="Asia/Kuwait">Asia/Kuwait</option>
            </select>
        </div>
    </div>

    <div class="section-divider">Admin Account</div>

    <div class="form-group">
        <label class="form-label">Full Name <span style="color:var(--danger)">*</span></label>
        <input type="text" name="name" class="form-control" placeholder="Your full name" required>
    </div>
    <div class="form-row-2">
        <div class="form-group">
            <label class="form-label">Email <span style="color:var(--danger)">*</span></label>
            <input type="email" name="email" class="form-control" placeholder="admin@company.com" required>
        </div>
        <div class="form-group">
            <label class="form-label">Phone</label>
            <input type="tel" name="phone" class="form-control" placeholder="+966500000000">
        </div>
    </div>
    <div class="form-row-2">
        <div class="form-group">
            <label class="form-label">Password <span style="color:var(--danger)">*</span></label>
            <input type="password" name="password" class="form-control" placeholder="Min 8 characters" required>
        </div>
        <div class="form-group">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
        </div>
    </div>
    <button type="submit" class="btn-auth" id="submitBtn">Create Account</button>
</form>

<div class="auth-link">Already have an account? <a href="{{ route('login') }}">Sign in</a></div>

@push('scripts')
<script>
const errAlert = document.getElementById('errAlert');
const successAlert = document.getElementById('successAlert');

document.getElementById('registerForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = document.getElementById('submitBtn');
    const fd = new FormData(e.target);
    const body = Object.fromEntries(fd.entries());
    errAlert.style.display = 'none';
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner"></span>Creating...';

    try {
        const res = await fetch('/api/auth/register', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(body)
        });
        const data = await res.json();
        if (!res.ok) {
            const msgs = data.errors ? Object.values(data.errors).flat().join(' · ') : (data.message || 'Registration failed.');
            errAlert.textContent = msgs;
            errAlert.style.display = 'block';
        } else {
            localStorage.setItem('worknexg_token', data.data.token);
            localStorage.setItem('worknexg_user', JSON.stringify(data.data.user));
            successAlert.textContent = 'Account created! Redirecting to dashboard...';
            successAlert.style.display = 'block';
            setTimeout(() => window.location.href = '/dashboard', 700);
        }
    } catch (err) {
        errAlert.textContent = 'Network error. Please try again.';
        errAlert.style.display = 'block';
    } finally {
        btn.innerHTML = 'Create Account';
        btn.disabled = false;
    }
});
</script>
@endpush
@endsection
