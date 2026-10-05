<!DOCTYPE html>
<html lang="bn" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - আমার বাংলা (Amar Bangla)</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Hind+Siliguri:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Plus Jakarta Sans', 'Hind Siliguri', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            min-height: 100vh;
            background: radial-gradient(circle at top right, #ecfdf5, #f8fafc 60%, #e2e8f0);
            color: #0f172a;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 1.5rem;
        }
        .error-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(226, 232, 240, 0.9);
            box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.08), 0 0 1px rgba(15, 23, 42, 0.1);
            border-radius: 1.5rem;
            max-width: 32rem;
            width: 100%;
            padding: 2.5rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .error-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #059669, #10b981, #f59e0b, #059669);
        }
        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #ecfdf5;
            color: #065f46;
            font-size: 0.8125rem;
            font-weight: 700;
            padding: 0.375rem 0.875rem;
            border-radius: 9999px;
            margin-bottom: 1.5rem;
            border: 1px solid #d1fae5;
        }
        .error-code {
            font-size: 5rem;
            font-weight: 900;
            line-height: 1;
            letter-spacing: -0.05em;
            background: linear-gradient(135deg, #0f172a 30%, #059669 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }
        .error-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 0.75rem;
        }
        .error-message {
            font-size: 0.9375rem;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        .btn-group {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            justify-content: center;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            font-size: 0.875rem;
            font-weight: 700;
            border-radius: 0.75rem;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
        }
        .btn-primary {
            background: #059669;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);
        }
        .btn-primary:hover {
            background: #047857;
            transform: translateY(-1px);
        }
        .btn-secondary {
            background: #f1f5f9;
            color: #334155;
            border: 1px solid #e2e8f0;
        }
        .btn-secondary:hover {
            background: #e2e8f0;
            color: #0f172a;
        }
        .footer-note {
            margin-top: 2rem;
            font-size: 0.75rem;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="brand-badge">
            <span>🇧🇩</span> আমার বাংলা • Amar Bangla
        </div>

        <div class="error-code">@yield('code')</div>
        <h1 class="error-title">@yield('title')</h1>
        <p class="error-message">@yield('message')</p>

        <div class="btn-group">
            <a href="{{ url('/') }}" class="btn btn-primary">
                🏠 হোম পেজে ফিরুন
            </a>
            <a href="{{ url('/shops') }}" class="btn btn-secondary">
                🏪 লোকাল শপ খুঁজুন
            </a>
            <button onclick="history.back()" class="btn btn-secondary">
                ← পূর্ববর্তী পেজ
            </button>
        </div>
    </div>

    <div class="footer-note">
        Amar Bangla Local Commerce Platform &bull; হেল্পলাইন: 01700-000000
    </div>
</body>
</html>
