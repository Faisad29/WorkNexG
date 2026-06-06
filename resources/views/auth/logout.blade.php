<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout - WorkNexG</title>
    <style>
        :root { --primary: #12754f; --bg: #f7fbf9; --ink: #0f2a20; --muted: #476a5c; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: "Segoe UI", Tahoma, Verdana, sans-serif;
            background: var(--bg);
            color: var(--ink);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }
        .card {
            width: 100%;
            max-width: 460px;
            background: #fff;
            border: 1px solid #d7ebdf;
            border-radius: 12px;
            padding: 28px;
            text-align: center;
            box-shadow: 0 8px 24px rgba(16, 42, 32, 0.08);
        }
        h1 { font-size: 1.3rem; margin-bottom: 8px; color: var(--primary); }
        p { color: var(--muted); font-size: 0.95rem; }
        .spin {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(18, 117, 79, 0.25);
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            vertical-align: middle;
            margin-right: 6px;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body>
<div class="card">
    <h1>Signing you out</h1>
    <p><span class="spin"></span>Please wait...</p>
</div>

<script>
(async function () {
    const tokenKey = 'worknexg_token';
    const userKey = 'worknexg_user';
    const token = localStorage.getItem(tokenKey);

    try {
        if (token) {
            await fetch('/api/auth/logout', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + token,
                },
            });
        }
    } catch (e) {
        // Ignore network/API failures and continue local logout.
    } finally {
        localStorage.removeItem(tokenKey);
        localStorage.removeItem(userKey);
        window.location.replace('/login');
    }
})();
</script>
</body>
</html>
