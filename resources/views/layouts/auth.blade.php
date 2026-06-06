<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sign In') — WorkNexG</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
    :root{--primary:#0e6b47;--primary-h:#0a5538;--primary-l:#e6f4ee;--border:#e5e9ef;--ink:#1a2332;--muted:#6b7a8d;--muted-l:#9aa5b4;--danger:#e53935;--r-md:10px;--r-lg:14px;--r-xl:20px;--font:'Plus Jakarta Sans',sans-serif}
    *{box-sizing:border-box;margin:0;padding:0}
    body{font-family:var(--font);min-height:100vh;display:grid;grid-template-columns:1fr 1fr;background:#fff}
    @media(max-width:768px){body{grid-template-columns:1fr}}
    .auth-panel{display:flex;flex-direction:column;justify-content:center;align-items:center;padding:40px;min-height:100vh}
    .auth-visual{background:linear-gradient(145deg,var(--primary) 0%,#0a9e6a 50%,#00c97a 100%);display:flex;flex-direction:column;justify-content:center;align-items:center;padding:60px;position:relative;overflow:hidden}
    @media(max-width:768px){.auth-visual{display:none}}
    .auth-visual::before{content:'';position:absolute;width:400px;height:400px;border-radius:50%;border:2px solid rgba(255,255,255,.1);top:-100px;right:-100px}
    .auth-visual::after{content:'';position:absolute;width:300px;height:300px;border-radius:50%;border:2px solid rgba(255,255,255,.08);bottom:-80px;left:-80px}
    .auth-box{width:100%;max-width:420px}
    .auth-logo{display:flex;align-items:center;gap:10px;margin-bottom:32px}
    .auth-logo-icon{width:40px;height:40px;border-radius:10px;background:linear-gradient(135deg,var(--primary),#0a9e6a);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:16px}
    .auth-logo-name{font-size:1.3rem;font-weight:800;color:var(--ink);letter-spacing:-.4px}
    .auth-logo-name span{color:var(--primary)}
    h1{font-size:1.6rem;font-weight:800;color:var(--ink);margin-bottom:6px;letter-spacing:-.5px}
    .auth-sub{font-size:.9rem;color:var(--muted);margin-bottom:32px}
    .form-group{margin-bottom:16px}
    .form-label{display:block;font-size:.8rem;font-weight:600;color:#374151;margin-bottom:6px}
    .form-control{width:100%;padding:11px 14px;border:1px solid var(--border);border-radius:var(--r-md);font-size:.9rem;color:var(--ink);outline:none;transition:border-color .18s,box-shadow .18s;font-family:var(--font)}
    .form-control:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(14,107,71,.12)}
    .form-control::placeholder{color:var(--muted-l)}
    .btn-auth{width:100%;padding:12px;background:var(--primary);color:#fff;border:none;border-radius:var(--r-md);font-size:1rem;font-weight:700;cursor:pointer;transition:background .18s;font-family:var(--font);margin-top:8px}
    .btn-auth:hover{background:var(--primary-h)}
    .btn-auth:disabled{opacity:.6;cursor:not-allowed}
    .auth-link{text-align:center;font-size:.875rem;color:var(--muted);margin-top:24px}
    .auth-link a{color:var(--primary);font-weight:600;text-decoration:none}
    .alert{padding:12px 16px;border-radius:var(--r-md);font-size:.875rem;margin-bottom:16px;display:none}
    .alert-danger{background:#fdecea;color:#991b1b;border:1px solid #fecaca}
    .alert-success{background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0}
    .spinner{width:16px;height:16px;border:2px solid rgba(255,255,255,.4);border-top-color:#fff;border-radius:50%;animation:spin .6s linear infinite;display:inline-block;vertical-align:middle;margin-right:6px}
    @keyframes spin{to{transform:rotate(360deg)}}
    .form-row-2{display:grid;grid-template-columns:1fr 1fr;gap:14px}
    .visual-text{color:#fff;text-align:center;position:relative;z-index:1}
    .visual-text h2{font-size:2rem;font-weight:800;margin-bottom:12px;letter-spacing:-.5px}
    .visual-text p{font-size:1rem;opacity:.85;line-height:1.7;max-width:360px}
    .visual-features{list-style:none;margin-top:32px;display:flex;flex-direction:column;gap:12px}
    .visual-features li{display:flex;align-items:center;gap:10px;color:rgba(255,255,255,.9);font-size:.9rem}
    .visual-features li::before{content:'✓';width:22px;height:22px;background:rgba(255,255,255,.2);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.7rem;flex-shrink:0}
    .section-divider{display:flex;align-items:center;gap:12px;margin:20px 0;color:var(--muted-l);font-size:.8rem}
    .section-divider::before,.section-divider::after{content:'';flex:1;height:1px;background:var(--border)}
    </style>
    @stack('styles')
</head>
<body>
<div class="auth-panel">
    <div class="auth-box">
        @yield('content')
    </div>
</div>
<div class="auth-visual">
    <div class="visual-text">
        <h2>Work with the<br>Next Generation</h2>
        <p>Automated attendance, payroll, and compliance management built for KSA businesses.</p>
        <ul class="visual-features">
            <li>GPS-verified attendance check-in</li>
            <li>One-click payroll generation</li>
            <li>Iqama & visa expiry alerts</li>
            <li>Multi-role access control</li>
            <li>WhatsApp & email notifications</li>
        </ul>
    </div>
</div>
@stack('scripts')
</body>
</html>
