<script>
// ── TOKEN & AUTH ─────────────────────────────────────────────
const WN = {
  tokenKey: 'worknexg_token',
  userKey:  'worknexg_user',
  token()   { return localStorage.getItem(this.tokenKey) },
  user()    { try { return JSON.parse(localStorage.getItem(this.userKey)||'{}') } catch{ return {} } },
  role()    { return this.user().role || 'employee' },
  clear()   { localStorage.removeItem(this.tokenKey); localStorage.removeItem(this.userKey) },

  // Authenticated fetch wrapper
  async api(method, url, body = null) {
    const opts = {
      method,
      headers: {
        'Authorization': 'Bearer ' + (this.token() || ''),
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
      }
    };
    if (body) {
      opts.body = JSON.stringify(body);
      opts.headers['Content-Type'] = 'application/json';
    }
    const res = await fetch(url, opts);
    const data = await res.json().catch(() => ({}));
    if (!res.ok) throw { status: res.status, data };
    return data;
  },

  // Redirect to login if no token on protected pages
  guardRoute() {
    const pub = ['/', '/login', '/register'];
    if (!this.token() && !pub.includes(window.location.pathname)) {
      window.location.href = '/login';
    }
  }
};

// Guard on load
WN.guardRoute();

// ── SIDEBAR TOGGLE ───────────────────────────────────────────
function initSidebar() {
  const toggle = document.getElementById('mobileToggle');
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('sidebarOverlay');
  if (!toggle || !sidebar) return;

  toggle.addEventListener('click', () => {
    sidebar.classList.toggle('open');
    overlay?.classList.toggle('show');
  });
  overlay?.addEventListener('click', () => {
    sidebar.classList.remove('open');
    overlay.classList.remove('show');
  });
}

// ── DROPDOWN ─────────────────────────────────────────────────
function initDropdowns() {
  document.querySelectorAll('[data-dropdown]').forEach(trigger => {
    trigger.addEventListener('click', (e) => {
      e.stopPropagation();
      const menu = document.getElementById(trigger.dataset.dropdown);
      document.querySelectorAll('.dropdown-menu.open').forEach(m => {
        if (m !== menu) m.classList.remove('open');
      });
      menu?.classList.toggle('open');
    });
  });
  document.addEventListener('click', () => {
    document.querySelectorAll('.dropdown-menu.open').forEach(m => m.classList.remove('open'));
  });
}

// ── MODAL ────────────────────────────────────────────────────
function openModal(id) {
  const el = document.getElementById(id);
  el?.classList.add('open');
  document.body.style.overflow = 'hidden';
}
function closeModal(id) {
  const el = document.getElementById(id);
  el?.classList.remove('open');
  document.body.style.overflow = '';
}
// Close on overlay click
document.addEventListener('click', (e) => {
  if (e.target.classList.contains('modal-overlay')) {
    e.target.classList.remove('open');
    document.body.style.overflow = '';
  }
});

// ── TABS ─────────────────────────────────────────────────────
function initTabs() {
  document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const group = btn.closest('[data-tabs]');
      if (!group) return;
      group.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
      group.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
      btn.classList.add('active');
      document.getElementById(btn.dataset.tab)?.classList.add('active');
    });
  });
}

// ── TOAST ────────────────────────────────────────────────────
function showToast(msg, type = 'info', duration = 3500) {
  let container = document.getElementById('toastContainer');
  if (!container) {
    container = document.createElement('div');
    container.id = 'toastContainer';
    container.className = 'toast-container';
    document.body.appendChild(container);
  }
  const toast = document.createElement('div');
  toast.className = `toast toast-${type}`;
  const icons = { success: '✓', danger: '✕', info: 'ℹ' };
  toast.innerHTML = `<span>${icons[type] || icons.info}</span> ${msg}`;
  container.appendChild(toast);
  setTimeout(() => { toast.style.opacity='0'; toast.style.transform='translateX(40px)'; toast.style.transition='.3s ease'; setTimeout(()=>toast.remove(), 300); }, duration);
}

// ── LOGOUT ───────────────────────────────────────────────────
async function doLogout() {
  try { await WN.api('POST', '/api/auth/logout'); } catch(e) {}
  WN.clear();
  window.location.href = '/login';
}

// ── FILL USER INFO ───────────────────────────────────────────
function fillUserInfo() {
  const user = WN.user();
  document.querySelectorAll('[data-user-name]').forEach(el => el.textContent = user.name || 'User');
  document.querySelectorAll('[data-user-role]').forEach(el => el.textContent = user.role || '');
  document.querySelectorAll('[data-user-initials]').forEach(el => {
    const parts = (user.name || 'U').split(' ');
    el.textContent = (parts[0][0] + (parts[1]?.[0] || '')).toUpperCase();
  });
  document.querySelectorAll('[data-user-email]').forEach(el => el.textContent = user.email || '');
}

// ── INIT ─────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  initSidebar();
  initDropdowns();
  initTabs();
  fillUserInfo();
});
</script>
