@extends('layouts.app')
@section('title', 'Notifications')
@section('content')
<div class="page-header"><h1>Notifications</h1><p>Send alerts via email, SMS, or WhatsApp</p></div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
    <div class="card">
        <div class="card-title">Send Notification</div>
        <div class="alert alert-error" id="notifError" style="display:none"></div>
        <div class="alert alert-success" id="notifSuccess" style="display:none"></div>
        <form id="notifForm">
            <div style="margin-bottom:14px">
                <label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:5px;color:var(--muted)">Channel *</label>
                <select name="channel" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:6px">
                    <option value="email">Email</option>
                    <option value="sms">SMS</option>
                    <option value="whatsapp">WhatsApp</option>
                </select>
            </div>
            <div style="margin-bottom:14px">
                <label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:5px;color:var(--muted)">Recipient *</label>
                <input name="recipient" required placeholder="email or phone number" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:6px;font-size:.875rem">
            </div>
            <div style="margin-bottom:18px">
                <label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:5px;color:var(--muted)">Message *</label>
                <textarea name="message" required rows="4" placeholder="Notification message..." style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:6px;font-size:.875rem;resize:vertical"></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%">Send Notification</button>
        </form>
    </div>
    <div class="card">
        <div class="card-title">Channel Status</div>
        <div style="display:flex;flex-direction:column;gap:12px">
            <div style="display:flex;justify-content:space-between;align-items:center;padding:10px;background:var(--primary-light);border-radius:6px">
                <span>📧 Email (SMTP/Log)</span><span class="badge badge-green">Enabled</span>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;padding:10px;background:#fff3e0;border-radius:6px">
                <span>📱 SMS</span><span class="badge badge-yellow">Stub</span>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;padding:10px;background:#e8f5e9;border-radius:6px">
                <span>💬 WhatsApp</span><span class="badge badge-yellow">Stub</span>
            </div>
        </div>
        <p style="font-size:.8rem;color:var(--muted);margin-top:16px">SMS and WhatsApp require third-party provider configuration (e.g. Twilio, WhatsApp Business API).</p>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('notifForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const errAlert = document.getElementById('notifError');
    const successAlert = document.getElementById('notifSuccess');
    const fd = new FormData(this);
    const body = Object.fromEntries(fd.entries());
    errAlert.style.display = 'none'; successAlert.style.display = 'none';
    try {
        await window.api('POST', '/api/notifications/send', body);
        successAlert.textContent = 'Notification queued successfully.';
        successAlert.style.display = 'block';
        this.reset();
    } catch(err) {
        errAlert.textContent = err.data?.message || 'Failed to send.'; errAlert.style.display = 'block';
    }
});
</script>
@endpush
@endsection
