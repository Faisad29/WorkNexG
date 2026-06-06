<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root{
--primary:#0e6b47;--primary-h:#0a5538;--primary-l:#e6f4ee;--primary-50:#f0faf5;
--accent:#00c97a;--sidebar-w:260px;--topbar-h:64px;
--body-bg:#f4f6f8;--card-bg:#ffffff;
--border:#e5e9ef;--border-l:#f0f3f6;
--ink:#1a2332;--ink2:#3d4f63;--muted:#6b7a8d;--muted-l:#9aa5b4;
--danger:#e53935;--danger-l:#fdecea;
--warning:#f59e0b;--warning-l:#fffbeb;
--success:#10b981;--success-l:#ecfdf5;
--info:#3b82f6;--info-l:#eff6ff;
--purple:#8b5cf6;--purple-l:#f5f3ff;
--r-sm:6px;--r-md:10px;--r-lg:14px;--r-xl:20px;
--shadow-sm:0 1px 3px rgba(0,0,0,.08),0 1px 2px rgba(0,0,0,.04);
--shadow-md:0 4px 12px rgba(0,0,0,.08),0 2px 6px rgba(0,0,0,.04);
--shadow-lg:0 12px 32px rgba(0,0,0,.10),0 4px 12px rgba(0,0,0,.05);
--t:.18s ease;
--font:'Plus Jakarta Sans',-apple-system,sans-serif;
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{font-size:14px}
body{font-family:var(--font);background:var(--body-bg);color:var(--ink);line-height:1.6;-webkit-font-smoothing:antialiased}
a{color:var(--primary);text-decoration:none}a:hover{color:var(--primary-h)}
img{max-width:100%}button{cursor:pointer;font-family:var(--font)}
input,select,textarea{font-family:var(--font)}

/* TOPBAR */
.topbar{position:fixed;top:0;left:0;right:0;height:var(--topbar-h);background:var(--card-bg);border-bottom:1px solid var(--border);display:flex;align-items:center;padding:0 24px 0 0;z-index:200;box-shadow:var(--shadow-sm)}
.topbar-brand{width:var(--sidebar-w);display:flex;align-items:center;gap:10px;padding:0 20px;flex-shrink:0;border-right:1px solid var(--border);height:100%;text-decoration:none}
.brand-icon{width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,var(--primary),#0a9e6a);display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:800;color:#fff;flex-shrink:0}
.brand-name{font-size:1.15rem;font-weight:800;color:var(--ink);letter-spacing:-.4px}
.brand-name span{color:var(--primary)}
.topbar-right{margin-left:auto;display:flex;align-items:center;gap:8px}
.topbar-btn{width:38px;height:38px;border-radius:var(--r-md);display:flex;align-items:center;justify-content:center;border:1px solid var(--border);background:transparent;color:var(--ink2);font-size:16px;transition:var(--t);position:relative;cursor:pointer}
.topbar-btn:hover{background:var(--primary-l);border-color:var(--primary);color:var(--primary)}
.badge-dot{position:absolute;top:6px;right:6px;width:7px;height:7px;border-radius:50%;background:var(--danger);border:2px solid #fff}
.topbar-search{display:flex;align-items:center;gap:8px;background:var(--body-bg);border:1px solid var(--border);border-radius:var(--r-md);padding:7px 14px;margin-left:16px}
.topbar-search input{border:none;background:transparent;outline:none;font-size:.875rem;color:var(--ink);width:200px}
.topbar-search input::placeholder{color:var(--muted-l)}
.topbar-user{display:flex;align-items:center;gap:10px;padding:6px 10px;border-radius:var(--r-md);cursor:pointer;border:1px solid transparent;transition:var(--t);margin-left:8px}
.topbar-user:hover{background:var(--primary-l);border-color:var(--border)}
.user-avatar{width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--primary),#0a9e6a);display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700;color:#fff;flex-shrink:0}
.user-info .user-name{font-size:.82rem;font-weight:600;color:var(--ink);line-height:1.3}
.user-info .user-role{font-size:.72rem;color:var(--muted);text-transform:capitalize}
.mobile-toggle{display:none;padding:0 16px;background:none;border:none;font-size:20px;color:var(--ink2)}
.topbar-divider{width:1px;height:24px;background:var(--border);margin:0 4px}

/* SIDEBAR */
.sidebar{position:fixed;left:0;top:var(--topbar-h);bottom:0;width:var(--sidebar-w);background:var(--card-bg);border-right:1px solid var(--border);overflow-y:auto;z-index:150;display:flex;flex-direction:column;transition:transform var(--t)}
.sidebar::-webkit-scrollbar{width:4px}
.sidebar::-webkit-scrollbar-thumb{background:var(--border);border-radius:4px}
.sidebar-section{padding:20px 16px 6px;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--muted-l)}
.sidebar-section:first-child{padding-top:16px}
.nav-item{display:flex;align-items:center;gap:10px;padding:9px 16px;margin:1px 8px;border-radius:var(--r-md);color:var(--ink2);text-decoration:none;font-size:.875rem;font-weight:500;transition:var(--t);position:relative}
.nav-item:hover{background:var(--primary-l);color:var(--primary)}
.nav-item.active{background:var(--primary-l);color:var(--primary);font-weight:600}
.nav-item.active::before{content:'';position:absolute;left:-8px;top:25%;bottom:25%;width:3px;border-radius:0 3px 3px 0;background:var(--primary)}
.nav-icon{width:32px;height:32px;border-radius:var(--r-sm);display:flex;align-items:center;justify-content:center;font-size:15px;flex-shrink:0;background:transparent;transition:var(--t)}
.nav-item:hover .nav-icon,.nav-item.active .nav-icon{background:rgba(14,107,71,.12)}
.nav-badge{margin-left:auto;font-size:.68rem;font-weight:700;background:var(--danger);color:#fff;padding:1px 6px;border-radius:20px;min-width:18px;text-align:center}
.nav-badge.green{background:var(--success)}
.nav-badge.orange{background:var(--warning)}
.sidebar-footer{margin-top:auto;padding:16px;border-top:1px solid var(--border)}

/* MAIN */
.main-wrapper{margin-left:var(--sidebar-w);padding-top:var(--topbar-h);min-height:100vh}
.page-container{padding:24px 28px}

/* PAGE HEADER */
.page-header{display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:24px}
.page-header h1{font-size:1.5rem;font-weight:700;color:var(--ink);letter-spacing:-.4px;line-height:1.2}
.breadcrumb{display:flex;align-items:center;gap:6px;font-size:.78rem;color:var(--muted);margin-top:4px}
.breadcrumb .sep{color:var(--muted-l)}
.page-actions{display:flex;align-items:center;gap:10px;flex-wrap:wrap}

/* STAT CARDS */
.stats-row{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:16px;margin-bottom:24px}
.stat-card{background:var(--card-bg);border:1px solid var(--border);border-radius:var(--r-lg);padding:20px;transition:var(--t);position:relative;overflow:hidden}
.stat-card:hover{box-shadow:var(--shadow-md);transform:translateY(-1px)}
.stat-card::after{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:var(--stat-accent,var(--primary));opacity:0;transition:var(--t)}
.stat-card:hover::after{opacity:1}
.stat-icon{width:44px;height:44px;border-radius:var(--r-md);display:flex;align-items:center;justify-content:center;font-size:20px;margin-bottom:14px}
.stat-icon.green{background:var(--success-l)}
.stat-icon.blue{background:var(--info-l)}
.stat-icon.orange{background:var(--warning-l)}
.stat-icon.red{background:var(--danger-l)}
.stat-icon.purple{background:var(--purple-l)}
.stat-icon.primary{background:var(--primary-l)}
.stat-value{font-size:1.85rem;font-weight:800;color:var(--ink);letter-spacing:-.5px;line-height:1;margin-bottom:4px}
.stat-label{font-size:.78rem;font-weight:500;color:var(--muted);text-transform:uppercase;letter-spacing:.06em}
.stat-change{display:inline-flex;align-items:center;gap:3px;font-size:.75rem;font-weight:600;margin-top:8px;padding:2px 8px;border-radius:20px}
.stat-change.up{background:var(--success-l);color:#065f46}
.stat-change.down{background:var(--danger-l);color:#991b1b}

/* CARDS */
.card{background:var(--card-bg);border:1px solid var(--border);border-radius:var(--r-lg);overflow:hidden}
.card-header{padding:16px 20px;border-bottom:1px solid var(--border-l);display:flex;align-items:center;justify-content:space-between;gap:12px}
.card-title{font-size:1rem;font-weight:700;color:var(--ink);letter-spacing:-.2px}
.card-subtitle{font-size:.8rem;color:var(--muted);margin-top:2px}
.card-body{padding:20px}
.card-footer{padding:14px 20px;border-top:1px solid var(--border-l);background:var(--body-bg)}

/* TABLE */
.table-wrapper{overflow-x:auto}
.data-table{width:100%;border-collapse:collapse;font-size:.875rem}
.data-table thead th{padding:11px 16px;text-align:left;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--muted);background:var(--body-bg);border-bottom:2px solid var(--border);white-space:nowrap}
.data-table tbody tr{border-bottom:1px solid var(--border-l);transition:background var(--t)}
.data-table tbody tr:last-child{border-bottom:none}
.data-table tbody tr:hover{background:var(--primary-50)}
.data-table tbody td{padding:13px 16px;color:var(--ink2);vertical-align:middle}
.data-table tbody td:first-child{font-weight:500;color:var(--ink)}
.tbl-actions{display:flex;align-items:center;gap:6px}
.tbl-avatar{width:34px;height:34px;border-radius:50%;background:var(--primary-l);display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:700;color:var(--primary);flex-shrink:0}

/* BUTTONS */
.btn{display:inline-flex;align-items:center;gap:7px;padding:9px 18px;border-radius:var(--r-md);font-size:.875rem;font-weight:600;border:1px solid transparent;cursor:pointer;transition:var(--t);text-decoration:none;white-space:nowrap;font-family:var(--font);line-height:1}
.btn:disabled{opacity:.55;cursor:not-allowed}
.btn-primary{background:var(--primary);color:#fff;border-color:var(--primary)}
.btn-primary:hover{background:var(--primary-h);color:#fff}
.btn-outline{background:transparent;color:var(--primary);border-color:var(--primary)}
.btn-outline:hover{background:var(--primary-l)}
.btn-ghost{background:transparent;color:var(--ink2);border-color:var(--border)}
.btn-ghost:hover{background:var(--body-bg);border-color:var(--muted-l)}
.btn-danger{background:var(--danger);color:#fff;border-color:var(--danger)}
.btn-danger:hover{background:#c62828}
.btn-success{background:var(--success);color:#fff;border-color:var(--success)}
.btn-warning{background:var(--warning);color:#fff;border-color:var(--warning)}
.btn-info{background:var(--info);color:#fff;border-color:var(--info)}
.btn-sm{padding:6px 12px;font-size:.8rem;border-radius:var(--r-sm)}
.btn-lg{padding:12px 28px;font-size:1rem}
.btn-icon{width:36px;height:36px;padding:0;justify-content:center}
.btn-icon.btn-sm{width:30px;height:30px}

/* BADGES */
.badge{display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700;white-space:nowrap}
.badge-success{background:var(--success-l);color:#065f46}
.badge-danger{background:var(--danger-l);color:#991b1b}
.badge-warning{background:var(--warning-l);color:#92400e}
.badge-info{background:var(--info-l);color:#1e40af}
.badge-purple{background:var(--purple-l);color:#5b21b6}
.badge-primary{background:var(--primary-l);color:var(--primary)}
.badge-gray{background:#f3f4f6;color:#374151}
.badge-dot::before{content:'';width:6px;height:6px;border-radius:50%;background:currentColor;display:inline-block}

/* FORMS */
.form-group{margin-bottom:18px}
.form-label{display:block;font-size:.8rem;font-weight:600;color:var(--ink2);margin-bottom:6px}
.form-label .req{color:var(--danger);margin-left:2px}
.form-control{width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:var(--r-md);font-size:.875rem;color:var(--ink);background:#fff;outline:none;transition:border-color var(--t),box-shadow var(--t);font-family:var(--font)}
.form-control:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(14,107,71,.12)}
.form-control::placeholder{color:var(--muted-l)}
.form-control.invalid{border-color:var(--danger)}
.form-hint{font-size:.75rem;color:var(--muted);margin-top:4px}
.form-error{font-size:.75rem;color:var(--danger);margin-top:4px}
.form-row{display:grid;gap:16px}
.form-row.cols-2{grid-template-columns:1fr 1fr}
.form-row.cols-3{grid-template-columns:1fr 1fr 1fr}
.input-group{display:flex;align-items:stretch}
.input-group .input-prefix,.input-group .input-suffix{display:flex;align-items:center;padding:9px 12px;background:var(--body-bg);border:1px solid var(--border);font-size:.875rem;color:var(--muted);white-space:nowrap}
.input-group .input-prefix{border-right:none;border-radius:var(--r-md) 0 0 var(--r-md)}
.input-group .input-suffix{border-left:none;border-radius:0 var(--r-md) var(--r-md) 0}
.input-group .form-control{border-radius:0;flex:1}
.input-group .form-control:first-child{border-radius:var(--r-md) 0 0 var(--r-md)}
.input-group .form-control:last-child{border-radius:0 var(--r-md) var(--r-md) 0}

/* ALERTS */
.alert{display:flex;align-items:flex-start;gap:10px;padding:13px 16px;border-radius:var(--r-md);font-size:.875rem;margin-bottom:16px}
.alert-success{background:var(--success-l);color:#065f46;border:1px solid #a7f3d0}
.alert-danger{background:var(--danger-l);color:#991b1b;border:1px solid #fecaca}
.alert-warning{background:var(--warning-l);color:#92400e;border:1px solid #fde68a}
.alert-info{background:var(--info-l);color:#1e40af;border:1px solid #bfdbfe}

/* MODAL */
.modal-overlay{display:none;position:fixed;inset:0;background:rgba(15,25,40,.45);backdrop-filter:blur(2px);z-index:500;align-items:center;justify-content:center;padding:20px}
.modal-overlay.open{display:flex}
.modal{background:var(--card-bg);border-radius:var(--r-xl);width:100%;max-width:520px;max-height:90vh;overflow-y:auto;box-shadow:var(--shadow-lg);animation:mIn .2s ease}
.modal.modal-lg{max-width:720px}
.modal.modal-sm{max-width:380px}
@keyframes mIn{from{opacity:0;transform:translateY(-16px) scale(.97)}to{opacity:1;transform:translateY(0) scale(1)}}
.modal-header{display:flex;align-items:center;justify-content:space-between;padding:20px 24px 16px;border-bottom:1px solid var(--border)}
.modal-title{font-size:1.05rem;font-weight:700;color:var(--ink)}
.modal-close{width:30px;height:30px;border-radius:var(--r-sm);border:none;background:var(--body-bg);display:flex;align-items:center;justify-content:center;font-size:16px;color:var(--muted);cursor:pointer;transition:var(--t)}
.modal-close:hover{background:var(--danger-l);color:var(--danger)}
.modal-body{padding:20px 24px}
.modal-footer{padding:16px 24px;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:10px}

/* FILTERS */
.filters-bar{display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:16px;padding:16px 20px;background:var(--card-bg);border:1px solid var(--border);border-radius:var(--r-lg) var(--r-lg) 0 0;border-bottom:none}
.search-wrap{position:relative;flex:1;min-width:200px;max-width:300px}
.search-wrap .s-icon{position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--muted-l);font-size:14px}
.search-wrap input{width:100%;padding:8px 12px 8px 32px;border:1px solid var(--border);border-radius:var(--r-md);font-size:.875rem;outline:none;background:#fff;transition:border-color var(--t)}
.search-wrap input:focus{border-color:var(--primary)}

/* PAGINATION */
.pagination-wrap{display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-top:1px solid var(--border-l);font-size:.8rem;color:var(--muted)}
.pagination-pages{display:flex;align-items:center;gap:4px}
.pg-btn{width:32px;height:32px;border-radius:var(--r-sm);border:1px solid var(--border);background:#fff;color:var(--ink2);font-size:.8rem;cursor:pointer;transition:var(--t);font-family:var(--font)}
.pg-btn:hover{background:var(--primary-l);border-color:var(--primary);color:var(--primary)}
.pg-btn.active{background:var(--primary);border-color:var(--primary);color:#fff}

/* EMPTY STATE */
.empty-state{text-align:center;padding:56px 24px}
.empty-icon{font-size:3rem;margin-bottom:12px;opacity:.6}
.empty-state h3{font-size:1rem;font-weight:600;color:var(--ink);margin-bottom:6px}
.empty-state p{font-size:.875rem;color:var(--muted);max-width:300px;margin:0 auto 20px}

/* SPINNER */
.spinner{width:18px;height:18px;border:2px solid rgba(255,255,255,.35);border-top-color:currentColor;border-radius:50%;animation:spin .6s linear infinite;display:inline-block}
@keyframes spin{to{transform:rotate(360deg)}}

/* TOAST */
.toast-container{position:fixed;bottom:24px;right:24px;z-index:999;display:flex;flex-direction:column;gap:10px}
.toast{display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:var(--r-md);font-size:.875rem;box-shadow:var(--shadow-lg);min-width:280px;max-width:400px;animation:tIn .3s ease}
@keyframes tIn{from{opacity:0;transform:translateX(40px)}to{opacity:1;transform:translateX(0)}}
.toast-success{background:#065f46;color:#fff}
.toast-danger{background:#991b1b;color:#fff}
.toast-info{background:#1e40af;color:#fff}

/* DROPDOWN */
.dropdown{position:relative}
.dropdown-menu{display:none;position:absolute;top:calc(100% + 6px);right:0;min-width:180px;background:var(--card-bg);border:1px solid var(--border);border-radius:var(--r-lg);box-shadow:var(--shadow-lg);z-index:300;padding:6px;animation:mIn .15s ease}
.dropdown-menu.open{display:block}
.dropdown-item{display:flex;align-items:center;gap:8px;padding:8px 12px;border-radius:var(--r-sm);color:var(--ink2);font-size:.875rem;cursor:pointer;transition:var(--t)}
.dropdown-item:hover{background:var(--primary-l);color:var(--primary)}
.dropdown-item.danger{color:var(--danger)}
.dropdown-item.danger:hover{background:var(--danger-l)}
.dropdown-divider{height:1px;background:var(--border);margin:4px 0}

/* PROGRESS */
.progress{height:8px;background:var(--body-bg);border-radius:20px;overflow:hidden}
.progress-bar{height:100%;border-radius:20px;transition:width .4s ease}
.progress-bar.primary{background:var(--primary)}
.progress-bar.success{background:var(--success)}
.progress-bar.warning{background:var(--warning)}
.progress-bar.danger{background:var(--danger)}

/* TABS */
.tab-nav{display:flex;gap:4px;border-bottom:2px solid var(--border);margin-bottom:20px}
.tab-btn{padding:10px 18px;font-size:.875rem;font-weight:500;color:var(--muted);background:none;border:none;cursor:pointer;border-bottom:2px solid transparent;margin-bottom:-2px;transition:var(--t);font-family:var(--font)}
.tab-btn:hover{color:var(--primary)}
.tab-btn.active{color:var(--primary);border-bottom-color:var(--primary);font-weight:600}
.tab-pane{display:none}.tab-pane.active{display:block}

/* SIDEBAR OVERLAY (mobile) */
.sidebar-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:140}
.sidebar-overlay.show{display:block}

/* RESPONSIVE */
@media(max-width:900px){
.sidebar{transform:translateX(-100%)}
.sidebar.open{transform:translateX(0);box-shadow:var(--shadow-lg)}
.main-wrapper{margin-left:0}
.topbar-brand{width:auto;border-right:none}
.mobile-toggle{display:flex}
.topbar-search{display:none}
.page-container{padding:16px}
.stats-row{grid-template-columns:1fr 1fr}
.form-row.cols-2,.form-row.cols-3{grid-template-columns:1fr}
}
@media(max-width:480px){
.stats-row{grid-template-columns:1fr}
.page-header{flex-direction:column}
.filters-bar{flex-direction:column;align-items:stretch}
}

/* UTILITIES */
.d-flex{display:flex}.items-center{align-items:center}.justify-between{justify-content:space-between}
.gap-2{gap:8px}.gap-3{gap:12px}.gap-4{gap:16px}
.mt-4{margin-top:16px}.mb-4{margin-bottom:16px}.mb-6{margin-bottom:24px}
.text-muted{color:var(--muted)}.text-sm{font-size:.8rem}.text-xs{font-size:.72rem}
.font-bold{font-weight:700}.font-semibold{font-weight:600}
.w-full{width:100%}
.grid-2{display:grid;grid-template-columns:1fr 1fr;gap:20px}
.grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:20px}
.grid-4{display:grid;grid-template-columns:repeat(4,1fr);gap:20px}
@media(max-width:768px){.grid-2,.grid-3,.grid-4{grid-template-columns:1fr}}
.truncate{overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.rounded-full{border-radius:9999px}
.opacity-60{opacity:.6}
</style>
