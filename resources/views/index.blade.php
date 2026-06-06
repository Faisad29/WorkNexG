<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>WorkNexG — Workforce Management for the Next Generation</title>
<meta name="description" content="Automate attendance, payroll, compliance and HR operations. Built for KSA businesses.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet">
<style>
:root{--ink:#0a1f15;--ink2:#294436;--muted:#4d7a65;--muted2:#7aaa94;--primary:#0e6b47;--primary2:#12854f;--accent:#00e58a;--accent2:#b3ffdf;--bg:#f2faf6;--bg2:#e3f5ec;--surface:#ffffff;--border:rgba(14,107,71,.14);--border2:rgba(14,107,71,.08);--radius:14px;--radius2:22px}
*{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{font-family:'DM Sans',sans-serif;background:var(--bg);color:var(--ink);overflow-x:hidden;line-height:1.6}
nav{position:fixed;top:0;left:0;right:0;z-index:100;padding:0 5%;height:68px;display:flex;align-items:center;justify-content:space-between;background:rgba(242,250,246,.9);backdrop-filter:blur(18px);-webkit-backdrop-filter:blur(18px);border-bottom:1px solid var(--border2)}
.nav-logo{display:flex;align-items:center;gap:10px;text-decoration:none;font-family:'Sora',sans-serif;font-weight:800;font-size:1.25rem;color:var(--ink);letter-spacing:-.5px}
.nav-logo .dot{width:32px;height:32px;border-radius:9px;background:linear-gradient(135deg,var(--primary2),var(--primary));display:flex;align-items:center;justify-content:center;color:#fff;font-size:.75rem;font-weight:800;font-family:'Sora',sans-serif;flex-shrink:0}
.nav-links{display:flex;align-items:center;gap:4px}
.nav-links a{text-decoration:none;color:var(--ink2);font-size:.875rem;font-weight:400;padding:8px 14px;border-radius:8px;transition:background .15s,color .15s}
.nav-links a:hover{background:var(--bg2);color:var(--primary)}
.nav-links .nav-cta{background:var(--primary);color:#fff;font-weight:500;padding:9px 22px;border-radius:10px;transition:background .15s,transform .12s}
.nav-links .nav-cta:hover{background:var(--primary2);transform:translateY(-1px);color:#fff}
@media(max-width:680px){.nav-links a:not(.nav-cta){display:none}}
.hero{min-height:100vh;padding:120px 5% 80px;display:flex;align-items:center;position:relative;overflow:hidden}
.hero-bg{position:absolute;inset:0;z-index:0;background:radial-gradient(ellipse 70% 55% at 65% 20%,rgba(0,229,138,.12),transparent),radial-gradient(ellipse 50% 40% at 20% 80%,rgba(14,107,71,.1),transparent),linear-gradient(175deg,var(--bg) 0%,var(--bg2) 100%)}
.hero-grid-lines{position:absolute;inset:0;z-index:0;background-image:linear-gradient(rgba(14,107,71,.05) 1px,transparent 1px),linear-gradient(90deg,rgba(14,107,71,.05) 1px,transparent 1px);background-size:48px 48px}
.hero-content{position:relative;z-index:1;max-width:620px}
.hero-badge{display:inline-flex;align-items:center;gap:8px;background:rgba(0,229,138,.12);border:1px solid rgba(0,229,138,.3);border-radius:100px;padding:5px 14px 5px 5px;font-size:.78rem;font-weight:500;color:var(--primary);margin-bottom:28px}
.hero-badge .bdot{width:22px;height:22px;background:var(--accent);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.65rem;color:var(--primary);font-weight:800}
h1{font-family:'Sora',sans-serif;font-size:clamp(2.6rem,5.5vw,4rem);font-weight:800;line-height:1.08;letter-spacing:-1.5px;color:var(--ink);margin-bottom:24px}
.hl{position:relative;display:inline-block;color:var(--primary)}
.hl::after{content:'';position:absolute;left:0;bottom:-4px;right:0;height:3px;background:linear-gradient(90deg,var(--accent),transparent);border-radius:4px}
.hero-sub{font-size:1.1rem;color:var(--muted);font-weight:300;max-width:500px;margin-bottom:40px;line-height:1.7}
.hero-actions{display:flex;flex-wrap:wrap;gap:14px;align-items:center}
.btn-p{display:inline-flex;align-items:center;gap:8px;background:var(--primary);color:#fff;text-decoration:none;font-weight:500;font-size:.95rem;padding:14px 28px;border-radius:12px;border:none;cursor:pointer;transition:background .15s,transform .12s,box-shadow .15s;box-shadow:0 4px 20px rgba(14,107,71,.28)}
.btn-p:hover{background:var(--primary2);transform:translateY(-2px);box-shadow:0 8px 28px rgba(14,107,71,.32)}
.btn-g{display:inline-flex;align-items:center;gap:8px;color:var(--ink2);text-decoration:none;font-weight:400;font-size:.9rem;padding:14px 24px;border-radius:12px;border:1px solid var(--border);background:var(--surface);transition:border-color .15s,background .15s,transform .12s}
.btn-g:hover{border-color:rgba(14,107,71,.35);background:var(--bg2);transform:translateY(-1px)}
.hero-stats{position:relative;z-index:1;margin-top:64px;display:flex;flex-wrap:wrap;gap:0;border:1px solid var(--border);border-radius:var(--radius2);background:var(--surface);overflow:hidden;box-shadow:0 2px 24px rgba(10,31,21,.06)}
.hs{flex:1;min-width:160px;padding:20px 28px;border-right:1px solid var(--border2)}
.hs:last-child{border-right:none}
.hs .v{font-family:'Sora',sans-serif;font-size:1.8rem;font-weight:700;color:var(--primary);line-height:1;margin-bottom:4px}
.hs .l{font-size:.78rem;color:var(--muted);font-weight:400}
.hero-visual{position:absolute;right:5%;top:50%;transform:translateY(-50%);width:min(44vw,480px);z-index:1}
@media(max-width:900px){.hero-visual{display:none}}
.dash-mock{background:var(--surface);border:1px solid var(--border);border-radius:18px;box-shadow:0 24px 64px rgba(10,31,21,.14),0 2px 8px rgba(10,31,21,.06);overflow:hidden;font-size:.72rem}
.dash-tb{background:var(--primary);padding:10px 14px;display:flex;align-items:center;justify-content:space-between}
.dash-tb .brand{font-family:'Sora',sans-serif;font-weight:700;font-size:.8rem;color:#fff}
.dash-tb .usr{width:24px;height:24px;background:rgba(255,255,255,.2);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.62rem;color:#fff;font-weight:600}
.dash-body{display:flex;height:320px}
.dash-sb{width:110px;background:var(--bg);border-right:1px solid var(--border2);padding:12px 0;flex-shrink:0}
.dni{padding:7px 12px;font-size:.68rem;color:var(--muted);display:flex;align-items:center;gap:6px}
.dni.act{background:rgba(14,107,71,.08);color:var(--primary);font-weight:500;border-right:2px solid var(--primary)}
.dnd{width:6px;height:6px;border-radius:50%;background:currentColor;opacity:.5}
.dash-main{flex:1;padding:14px;overflow:hidden}
.dss{display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-bottom:12px}
.dsc{background:var(--bg);border:1px solid var(--border2);border-radius:8px;padding:8px 10px}
.dsc .dv{font-family:'Sora',sans-serif;font-weight:700;font-size:1rem;color:var(--primary);margin-bottom:1px}
.dsc .dl{font-size:.6rem;color:var(--muted)}
.dt{width:100%;border-collapse:collapse}
.dt th{font-size:.62rem;color:var(--muted2);font-weight:500;padding:4px 6px;text-align:left;border-bottom:1px solid var(--border2)}
.dt td{font-size:.65rem;padding:5px 6px;border-bottom:1px solid var(--border2);color:var(--ink2)}
.db{display:inline-block;padding:2px 6px;border-radius:4px;font-size:.58rem;font-weight:600}
.dbg{background:rgba(0,229,138,.15);color:#0a5c36}
.dbo{background:rgba(255,165,0,.15);color:#7a4800}
.dbx{background:rgba(0,0,0,.06);color:#556}
.trust{padding:48px 5%;text-align:center}
.trust p{font-size:.8rem;color:var(--muted2);letter-spacing:.08em;text-transform:uppercase;margin-bottom:24px}
.trust-logos{display:flex;flex-wrap:wrap;justify-content:center;gap:32px;align-items:center}
.tl{font-family:'Sora',sans-serif;font-weight:600;font-size:.9rem;color:var(--muted2);letter-spacing:-.3px;opacity:.7}
.section{padding:96px 5%}
.sl{display:inline-flex;align-items:center;gap:6px;font-size:.75rem;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:var(--primary);margin-bottom:14px}
.sl::before{content:'';width:20px;height:2px;background:var(--accent);border-radius:2px}
h2{font-family:'Sora',sans-serif;font-size:clamp(1.8rem,3.5vw,2.6rem);font-weight:700;letter-spacing:-1px;color:var(--ink);line-height:1.15;margin-bottom:16px}
.ss{font-size:1rem;color:var(--muted);max-width:520px;line-height:1.7;margin-bottom:56px}
.fg{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:20px}
.fc{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius2);padding:28px;transition:border-color .2s,transform .2s,box-shadow .2s;position:relative;overflow:hidden}
.fc::before{content:'';position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,var(--accent),transparent);opacity:0;transition:opacity .25s}
.fc:hover{border-color:rgba(14,107,71,.3);transform:translateY(-4px);box-shadow:0 12px 40px rgba(14,107,71,.1)}
.fc:hover::before{opacity:1}
.fi{width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,rgba(0,229,138,.18),rgba(14,107,71,.12));display:flex;align-items:center;justify-content:center;margin-bottom:18px;font-size:1.3rem}
.fc h3{font-family:'Sora',sans-serif;font-size:1rem;font-weight:600;color:var(--ink);margin-bottom:8px;letter-spacing:-.3px}
.fc p{font-size:.875rem;color:var(--muted);line-height:1.65}
.ft{display:inline-block;margin-top:14px;font-size:.72rem;font-weight:500;background:rgba(0,229,138,.12);color:var(--primary);padding:3px 10px;border-radius:100px;border:1px solid rgba(0,229,138,.25)}
.how{background:linear-gradient(170deg,var(--bg2),var(--bg));padding:96px 5%}
.steps{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:0}
.step{padding:32px 28px;border-right:1px solid var(--border2);position:relative}
.step:last-child{border-right:none}
.sn{font-family:'Sora',sans-serif;font-size:3rem;font-weight:800;color:rgba(14,107,71,.08);line-height:1;margin-bottom:12px}
.step h3{font-family:'Sora',sans-serif;font-size:.95rem;font-weight:600;color:var(--ink);margin-bottom:6px}
.step p{font-size:.83rem;color:var(--muted);line-height:1.6}
.sa{position:absolute;right:-10px;top:50%;transform:translateY(-50%);width:20px;height:20px;background:var(--surface);border:1px solid var(--border);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.65rem;color:var(--primary);z-index:2}
@media(max-width:680px){.step{border-right:none;border-bottom:1px solid var(--border2);padding:24px 0}.sa{display:none}}
.rg{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:16px;margin-top:20px}
.rc{border:1px solid var(--border);border-radius:var(--radius2);padding:24px;background:var(--surface)}
.ra{width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-weight:700;font-size:.85rem;margin-bottom:14px}
.rc h3{font-family:'Sora',sans-serif;font-size:.95rem;font-weight:600;margin-bottom:8px;color:var(--ink)}
.rc ul{list-style:none;padding:0}
.rc ul li{font-size:.82rem;color:var(--muted);padding:3px 0;display:flex;align-items:center;gap:7px}
.rc ul li::before{content:'';width:5px;height:5px;border-radius:50%;background:var(--accent);flex-shrink:0}
.ksa{background:var(--primary);color:#fff;padding:72px 5%;display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:center}
@media(max-width:680px){.ksa{grid-template-columns:1fr}}
.ksa h2{color:#fff;margin-bottom:12px}
.ksa p{color:rgba(255,255,255,.7);font-size:.95rem;line-height:1.7}
.kl{list-style:none;padding:0;display:flex;flex-direction:column;gap:12px}
.kl li{display:flex;align-items:flex-start;gap:12px;font-size:.875rem;color:rgba(255,255,255,.85)}
.kl .ck{width:22px;height:22px;background:rgba(0,229,138,.2);border:1px solid rgba(0,229,138,.4);border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:.7rem;color:var(--accent);margin-top:1px}
.ctas{padding:96px 5%;text-align:center;position:relative;overflow:hidden}
.ctas::before{content:'';position:absolute;width:600px;height:600px;border-radius:50%;background:radial-gradient(circle,rgba(0,229,138,.12),transparent 70%);left:50%;top:50%;transform:translate(-50%,-50%)}
.ctas h2{position:relative;z-index:1;max-width:560px;margin:0 auto 16px}
.ctas p{position:relative;z-index:1;color:var(--muted);margin-bottom:36px;font-size:1rem}
.cta-a{position:relative;z-index:1;display:flex;flex-wrap:wrap;justify-content:center;gap:14px}
footer{border-top:1px solid var(--border);padding:40px 5%;display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:16px;background:var(--surface)}
.fl{font-family:'Sora',sans-serif;font-weight:700;font-size:1rem;color:var(--ink);letter-spacing:-.3px}
.flinks{display:flex;gap:20px;flex-wrap:wrap}
.flinks a{font-size:.82rem;color:var(--muted);text-decoration:none;transition:color .15s}
.flinks a:hover{color:var(--primary)}
.fc2{font-size:.78rem;color:var(--muted2)}
.anim{opacity:0}
.anim.vis{animation:fadeUp .6s ease forwards}
@keyframes fadeUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
@keyframes pdot{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.6;transform:scale(.85)}}
.ld{display:inline-block;width:7px;height:7px;background:var(--accent);border-radius:50%;animation:pdot 1.8s ease infinite;margin-right:4px}
.sh{position:absolute;bottom:36px;left:50%;transform:translateX(-50%);display:flex;flex-direction:column;align-items:center;gap:6px;font-size:.72rem;color:var(--muted);z-index:1;animation:fadeUp .8s 1.2s ease both}
.sh .arr{width:28px;height:28px;border:1px solid var(--border);border-radius:50%;display:flex;align-items:center;justify-content:center;animation:bnc .9s ease infinite alternate}
@keyframes bnc{from{transform:translateY(0)}to{transform:translateY(6px)}}
</style>
</head>
<body>

<nav>
  <a class="nav-logo" href="/"><div class="dot">WN</div>WorkNexG</a>
  <div class="nav-links">
    <a href="#features">Features</a>
    <a href="#how">How it works</a>
    <a href="#roles">Roles</a>
    <a href="/login" class="nav-cta">Sign In →</a>
  </div>
</nav>

<section class="hero">
  <div class="hero-bg"></div>
  <div class="hero-grid-lines"></div>
  <div style="width:100%;max-width:1280px;margin:0 auto">
    <div class="hero-content">
      <div class="hero-badge"><div class="bdot">✦</div>Work with the Next Generation</div>
      <h1>Workforce ops<br>that run <span class="hl">themselves</span></h1>
      <p class="hero-sub">Stop chasing timesheets, manual payslips, and expiry reminders. WorkNexG automates attendance, payroll, and compliance — built specifically for KSA businesses.</p>
      <div class="hero-actions">
        <a href="/register" class="btn-p">Start free <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
        <a href="/login" class="btn-g">Sign in to dashboard</a>
      </div>
      <div class="hero-stats anim">
        <div class="hs"><div class="v">98%</div><div class="l">Attendance accuracy</div></div>
        <div class="hs"><div class="v">5 roles</div><div class="l">Built-in RBAC</div></div>
        <div class="hs"><div class="v">Auto</div><div class="l">Payroll generation</div></div>
        <div class="hs"><div class="v">GPS</div><div class="l">Geofenced check-in</div></div>
      </div>
    </div>
  </div>
  <div class="hero-visual anim">
    <div class="dash-mock">
      <div class="dash-tb"><span class="brand">⚡ WorkNexG</span><div class="usr">A</div></div>
      <div class="dash-body">
        <div class="dash-sb">
          <div class="dni act"><div class="dnd"></div>Dashboard</div>
          <div class="dni"><div class="dnd"></div>Employees</div>
          <div class="dni"><div class="dnd"></div>Attendance</div>
          <div class="dni"><div class="dnd"></div>Payroll</div>
          <div class="dni"><div class="dnd"></div>Compliance</div>
          <div class="dni"><div class="dnd"></div>Reports</div>
        </div>
        <div class="dash-main">
          <div style="font-family:'Sora',sans-serif;font-size:.7rem;font-weight:600;color:var(--ink);margin-bottom:10px"><span class="ld"></span>Live — Today</div>
          <div class="dss">
            <div class="dsc"><div class="dv">47</div><div class="dl">Employees</div></div>
            <div class="dsc"><div class="dv">42</div><div class="dl">Present</div></div>
            <div class="dsc"><div class="dv">3</div><div class="dl">Expiring docs</div></div>
          </div>
          <table class="dt">
            <thead><tr><th>Employee</th><th>Check-in</th><th>Status</th></tr></thead>
            <tbody>
              <tr><td>Ahmed Q.</td><td>08:02</td><td><span class="db dbg">Present</span></td></tr>
              <tr><td>Sara M.</td><td>08:18</td><td><span class="db dbo">Late</span></td></tr>
              <tr><td>Khalid R.</td><td>—</td><td><span class="db dbx">Absent</span></td></tr>
              <tr><td>Fatima N.</td><td>07:55</td><td><span class="db dbg">Present</span></td></tr>
              <tr><td>Omar H.</td><td>08:10</td><td><span class="db dbg">Present</span></td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="sh"><span>Scroll</span><div class="arr"><svg width="10" height="10" viewBox="0 0 10 10" fill="none"><path d="M5 1v8M2 6l3 3 3-3" stroke="var(--muted)" stroke-width="1.2" stroke-linecap="round"/></svg></div></div>
</section>

<div class="trust">
  <p>Built for businesses operating in</p>
  <div class="trust-logos">
    <span class="tl">🇸🇦 Saudi Arabia</span>
    <span class="tl">🇦🇪 UAE</span>
    <span class="tl">🇰🇼 Kuwait</span>
    <span class="tl">🇶🇦 Qatar</span>
    <span class="tl">🇧🇭 Bahrain</span>
    <span class="tl">🇴🇲 Oman</span>
  </div>
</div>

<section class="section" id="features">
  <div style="max-width:1280px;margin:0 auto">
    <div class="sl">Features</div>
    <h2>Everything automated.<br>Nothing manual.</h2>
    <p class="ss">Every workflow that used to require human follow-up now runs on its own — triggered by real events, not reminders.</p>
    <div class="fg">
      <div class="fc anim"><div class="fi">📍</div><h3>GPS Geofenced Attendance</h3><p>Employees check in only when physically inside the work site radius. Haversine-validated coordinates, no spoofing.</p><span class="ft">Auto check-in</span></div>
      <div class="fc anim"><div class="fi">💰</div><h3>One-Click Payroll</h3><p>Generate monthly payroll for all active employees in seconds. Monthly, daily, hourly, and project-based salary types supported.</p><span class="ft">Auto-calculate</span></div>
      <div class="fc anim"><div class="fi">🛡️</div><h3>Compliance & Expiry Alerts</h3><p>Iqama, visa, passport, contracts — tracked automatically. Alerts fire 30, 15, and 7 days before expiry via email or WhatsApp.</p><span class="ft">Auto-alerts</span></div>
      <div class="fc anim"><div class="fi">🌴</div><h3>Leave Management</h3><p>Employees submit requests; supervisors approve or reject with one tap. Leave days integrate into payroll deductions automatically.</p><span class="ft">Approval flow</span></div>
      <div class="fc anim"><div class="fi">🔔</div><h3>Multi-Channel Notifications</h3><p>Email, SMS, and WhatsApp notifications for attendance, payroll, and compliance events. Pluggable provider architecture.</p><span class="ft">Email · SMS · WhatsApp</span></div>
      <div class="fc anim"><div class="fi">📋</div><h3>Full Audit Trail</h3><p>Every create, update, and delete is timestamped and logged. Know exactly who changed what, when, and from which IP.</p><span class="ft">Immutable log</span></div>
      <div class="fc anim"><div class="fi">🏢</div><h3>Multi-Tenant Architecture</h3><p>Each company's data is strictly isolated at the database row level. One platform, unlimited companies, zero data leakage.</p><span class="ft">Row-level isolation</span></div>
      <div class="fc anim"><div class="fi">🔐</div><h3>Role-Based Access Control</h3><p>Admin, HR, Supervisor, Accountant, Employee — each with a precise permission set. Assign via UI or seed automatically.</p><span class="ft">5 built-in roles</span></div>
      <div class="fc anim"><div class="fi">⚡</div><h3>RESTful API</h3><p>Every feature accessible via a fully documented Sanctum-authenticated API. Build mobile apps or third-party integrations with ease.</p><span class="ft">OpenAPI ready</span></div>
    </div>
  </div>
</section>

<section class="how" id="how">
  <div style="max-width:1280px;margin:0 auto">
    <div class="sl">How it works</div>
    <h2>Up and running in minutes</h2>
    <p class="ss">From company registration to your first automated payroll — no spreadsheets, no manual data entry.</p>
    <div style="border:1px solid var(--border);border-radius:var(--radius2);background:var(--surface);overflow:hidden">
      <div class="steps">
        <div class="step anim"><div class="sn">01</div><h3>Register your company</h3><p>Create your tenant account, set your timezone and country. Takes 60 seconds.</p><div class="sa">→</div></div>
        <div class="step anim"><div class="sn">02</div><h3>Add employees & sites</h3><p>Import your team, set GPS boundaries for each work location. Geofencing is live immediately.</p><div class="sa">→</div></div>
        <div class="step anim"><div class="sn">03</div><h3>Attendance runs itself</h3><p>Employees check in via mobile. GPS validates location. Late arrivals and overrides are flagged automatically.</p><div class="sa">→</div></div>
        <div class="step anim"><div class="sn">04</div><h3>Generate & approve payroll</h3><p>One click generates payroll from attendance data. Approve → Lock → Mark paid. Done.</p></div>
      </div>
    </div>
  </div>
</section>

<section class="section" id="roles">
  <div style="max-width:1280px;margin:0 auto">
    <div class="sl">Access Control</div>
    <h2>The right access for every role</h2>
    <p class="ss">Five built-in roles with carefully scoped permissions. No role sees more than it needs to.</p>
    <div class="rg">
      <div class="rc anim"><div class="ra" style="background:rgba(14,107,71,.1);color:var(--primary)">AD</div><h3>Admin</h3><ul><li>Full system access</li><li>Manage employees & sites</li><li>Generate & approve payroll</li><li>Manage roles & settings</li></ul></div>
      <div class="rc anim"><div class="ra" style="background:rgba(0,100,200,.1);color:#0064c8">HR</div><h3>HR Manager</h3><ul><li>Create & edit employees</li><li>Manage compliance docs</li><li>Approve leave requests</li><li>View reports</li></ul></div>
      <div class="rc anim"><div class="ra" style="background:rgba(180,80,0,.1);color:#b45000">SV</div><h3>Supervisor</h3><ul><li>Monitor team attendance</li><li>Approve manual overrides</li><li>Approve leave for team</li><li>View attendance reports</li></ul></div>
      <div class="rc anim"><div class="ra" style="background:rgba(100,0,180,.1);color:#6400b4">AC</div><h3>Accountant</h3><ul><li>Generate payroll runs</li><li>Approve & lock payroll</li><li>View salary reports</li><li>Read-only employee data</li></ul></div>
      <div class="rc anim"><div class="ra" style="background:rgba(0,150,100,.1);color:#009664">EM</div><h3>Employee</h3><ul><li>GPS check-in / check-out</li><li>View own attendance</li><li>View own payslips</li><li>Submit leave requests</li></ul></div>
    </div>
  </div>
</section>

<div class="ksa">
  <div>
    <div class="sl" style="color:rgba(255,255,255,.6)">KSA Ready</div>
    <h2>Built for Saudi Arabia's<br>workforce regulations</h2>
    <p>Every feature is designed around the specific compliance, documentation, and payroll requirements of operating in the Kingdom.</p>
  </div>
  <ul class="kl">
    <li><div class="ck">✓</div><div>Iqama & visa expiry tracking with automated renewal alerts</div></li>
    <li><div class="ck">✓</div><div>Asia/Riyadh timezone default across all date/time operations</div></li>
    <li><div class="ck">✓</div><div>Contract end-date tracking with proactive notifications</div></li>
    <li><div class="ck">✓</div><div>Saudi, Expat, and multi-nationality employee support</div></li>
    <li><div class="ck">✓</div><div>WhatsApp Business API integration (most-used channel in KSA)</div></li>
    <li><div class="ck">✓</div><div>Monthly payroll with penalty, overtime, and deduction support</div></li>
  </ul>
</div>

<section class="ctas">
  <div class="sl" style="justify-content:center">Get started</div>
  <h2>Stop managing manually.<br>Start running automatically.</h2>
  <p>Register your company in 60 seconds. No credit card required.</p>
  <div class="cta-a">
    <a href="/register" class="btn-p">Create your company account <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
    <a href="/api/docs" class="btn-g">View API docs</a>
  </div>
</section>

<footer>
  <div class="fl">⚡ WorkNexG</div>
  <div class="flinks">
    <a href="#features">Features</a>
    <a href="#how">How it works</a>
    <a href="/login">Sign in</a>
    <a href="/register">Register</a>
    <a href="/api/docs">API</a>
    <a href="/health">Health</a>
  </div>
  <div class="fc2">© {{ date('Y') }} WorkNexG. Built for the next generation.</div>
</footer>

<script>
const obs = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      const el = entry.target;
      const siblings = [...el.parentElement.querySelectorAll('.anim')];
      el.style.animationDelay = (siblings.indexOf(el) * 75) + 'ms';
      el.classList.add('vis');
      obs.unobserve(el);
    }
  });
}, { threshold: 0.1 });
document.querySelectorAll('.anim').forEach(el => obs.observe(el));
</script>
</body>
</html>
