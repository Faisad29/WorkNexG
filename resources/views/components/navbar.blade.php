{{-- Reusable Top Navigation Bar --}}
<header class="topbar">
    {{-- Mobile Toggle + Brand --}}
    <button class="mobile-toggle" id="mobileToggle" aria-label="Toggle sidebar">☰</button>
    <a href="{{ route('dashboard') }}" class="topbar-brand">
        <div class="brand-icon">WN</div>
        <span class="brand-name">Work<span>NexG</span></span>
    </a>

    {{-- Search --}}
    <div class="topbar-search">
        <span style="color:var(--muted-l);font-size:14px">🔍</span>
        <input type="text" placeholder="Search employees, payroll..." id="globalSearch" autocomplete="off">
    </div>

    {{-- Right Actions --}}
    <div class="topbar-right">

        {{-- Notifications Bell --}}
        <div class="dropdown">
            <button class="topbar-btn" data-dropdown="notifDropdown" aria-label="Notifications">
                🔔
                <span class="badge-dot"></span>
            </button>
            <div class="dropdown-menu" id="notifDropdown" style="min-width:320px;right:0">
                <div style="padding:12px 16px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center">
                    <span style="font-weight:700;font-size:.9rem">Notifications</span>
                    <button onclick="markAllRead()" style="background:none;border:none;font-size:.75rem;color:var(--primary);cursor:pointer">Mark all read</button>
                </div>
                <div id="notifList" style="max-height:280px;overflow-y:auto;padding:8px">
                    <div style="text-align:center;padding:20px;color:var(--muted);font-size:.85rem">Loading...</div>
                </div>
                <div style="padding:10px 16px;border-top:1px solid var(--border);text-align:center">
                    <a href="{{ route('notifications.index') }}" style="font-size:.8rem;color:var(--primary);font-weight:600">View all notifications</a>
                </div>
            </div>
        </div>

        <div class="topbar-divider"></div>

        {{-- User Menu --}}
        <div class="dropdown">
            <div class="topbar-user" data-dropdown="userDropdown">
                <div class="user-avatar" data-user-initials>U</div>
                <div class="user-info" style="display:none" id="userInfoDesktop">
                    <div class="user-name" data-user-name>User</div>
                    <div class="user-role" data-user-role>role</div>
                </div>
                <span style="font-size:10px;color:var(--muted-l);margin-left:2px">▼</span>
            </div>
            <div class="dropdown-menu" id="userDropdown">
                <div style="padding:12px 16px;border-bottom:1px solid var(--border)">
                    <div style="font-weight:600;font-size:.875rem" data-user-name>User</div>
                    <div style="font-size:.75rem;color:var(--muted)" data-user-email>email</div>
                </div>
                <div style="padding:6px">
                    <div class="dropdown-item">👤 My Profile</div>
                    <a href="{{ route('settings.index') }}" class="dropdown-item">⚙️ Settings</a>
                    <div class="dropdown-divider"></div>
                    <div class="dropdown-item danger" onclick="doLogout()">🚪 Logout</div>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
// Show user info on desktop
document.getElementById('userInfoDesktop')?.style.setProperty('display', window.innerWidth > 900 ? 'block' : 'none');

// Load notifications
async function loadNotifications() {
    const list = document.getElementById('notifList');
    if (!list) return;
    list.innerHTML = '<div style="text-align:center;padding:20px;color:var(--muted);font-size:.85rem">No new notifications</div>';
}

function markAllRead() {
    showToast('All notifications marked as read', 'success');
}

loadNotifications();
</script>
