<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WorkNexG</title>
    <style>
        :root {
            --bg-1: #f7fbf9;
            --bg-2: #e5f4ed;
            --ink: #0f2a20;
            --muted: #476a5c;
            --primary: #12754f;
            --primary-dark: #0c5539;
            --card: #ffffff;
            --border: #d7ebdf;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at 20% 20%, rgba(18, 117, 79, 0.14), transparent 35%),
                radial-gradient(circle at 80% 10%, rgba(17, 143, 96, 0.12), transparent 40%),
                linear-gradient(140deg, var(--bg-1), var(--bg-2));
            min-height: 100vh;
        }

        .wrap {
            max-width: 920px;
            margin: 0 auto;
            padding: 48px 20px;
        }

        .hero {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 12px 32px rgba(16, 52, 38, 0.08);
        }

        h1 {
            margin: 0 0 10px;
            font-size: clamp(1.8rem, 3vw, 2.5rem);
        }

        p {
            margin: 0;
            color: var(--muted);
            line-height: 1.6;
        }

        .actions {
            margin-top: 22px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .btn {
            text-decoration: none;
            border-radius: 10px;
            padding: 10px 14px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: transform 0.12s ease, box-shadow 0.2s ease;
        }

        .btn.primary {
            color: #fff;
            background: linear-gradient(180deg, var(--primary), var(--primary-dark));
            box-shadow: 0 8px 18px rgba(18, 117, 79, 0.25);
        }

        .btn.secondary {
            color: var(--primary-dark);
            background: #e8f5ef;
            border: 1px solid #cfe9dd;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .grid {
            margin-top: 18px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 12px;
        }

        .card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 14px;
        }

        .card strong {
            display: block;
            margin-bottom: 8px;
        }

        .card a {
            color: var(--primary-dark);
            text-decoration: none;
            font-size: 0.95rem;
        }

        .card a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="wrap">
    <section class="hero">
        <h1>WorkNexG Backend Is Running</h1>
        <p>
            This environment is API-first. Use the links below to verify service health and key auth endpoints.
        </p>

        <div class="actions">
            <a class="btn primary" href="/health" target="_blank" rel="noopener">Open Health Check</a>
            <a class="btn secondary" href="/api/auth/me" target="_blank" rel="noopener">Check Auth Endpoint</a>
        </div>

        <div class="grid">
            <div class="card">
                <strong>Auth</strong>
                <a href="/api/auth/me" target="_blank" rel="noopener">GET /api/auth/me</a>
            </div>
            <div class="card">
                <strong>Employees</strong>
                <a href="/api/employees" target="_blank" rel="noopener">GET /api/employees</a>
            </div>
            <div class="card">
                <strong>Sites</strong>
                <a href="/api/sites" target="_blank" rel="noopener">GET /api/sites</a>
            </div>
            <div class="card">
                <strong>API Documentation</strong>
                <a href="/api/docs" target="_blank" rel="noopener">GET /api/docs</a>
            </div>
        </div>
    </section>
</div>
</body>
</html>
