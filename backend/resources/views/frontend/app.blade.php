<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <!-- Dynamic SEO Meta Tags -->
    <title>{{ $meta['title'] ?? 'Amar Bangla Bazar - Bangladesh Local Marketplace' }}</title>
    <meta name="description" content="{{ $meta['description'] ?? '' }}">
    <meta name="keywords" content="{{ $meta['keywords'] ?? '' }}">
    <meta name="author" content="{{ $meta['site_name'] ?? 'Amar Bangla' }}">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <link rel="canonical" href="{{ $meta['url'] ?? url()->current() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="{{ $meta['type'] ?? 'website' }}">
    <meta property="og:url" content="{{ $meta['url'] ?? url()->current() }}">
    <meta property="og:title" content="{{ $meta['title'] ?? '' }}">
    <meta property="og:description" content="{{ $meta['description'] ?? '' }}">
    <meta property="og:image" content="{{ $meta['image'] ?? '' }}">
    <meta property="og:site_name" content="{{ $meta['site_name'] ?? 'Amar Bangla' }}">
    <meta property="og:locale" content="en_US">

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $meta['title'] ?? '' }}">
    <meta name="twitter:description" content="{{ $meta['description'] ?? '' }}">
    <meta name="twitter:image" content="{{ $meta['image'] ?? '' }}">

    <!-- CSRF Token for Secure AJAX Requests -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- JSON-LD Structured Data for Search Engines -->
    @if(!empty($meta['json_ld']))
    <script type="application/ld+json">
        {!! json_encode($meta['json_ld'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
    @endif

    <!-- Fonts preconnect -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', 'Hind Siliguri', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
        /* Smooth custom scrollbars */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased selection:bg-emerald-600 selection:text-white min-h-screen flex flex-col">
    <!-- Server-Side Crawlable noscript fallback for crawlers -->
    <noscript>
        <div style="padding: 20px; text-align: center; background: #065f46; color: white;">
            <h2>{{ $meta['title'] }}</h2>
            <p>{{ $meta['description'] }}</p>
            <p>Please enable JavaScript to enjoy full interactive shopping on Amar Bangla Local Marketplace.</p>
        </div>
    </noscript>

    <!-- App Root for Vue 3 -->
    <div id="app" class="flex flex-col flex-1"></div>

    <!-- Global App Context Initialization -->
    <script>
        window.__APP_CONFIG__ = {
            csrfToken: "{{ csrf_token() }}",
            apiUrl: "{{ url('/api/v1') }}",
            baseUrl: "{{ url('/') }}",
            basePath: "{{ request()->getBaseUrl() ?: '/' }}",
            siteName: "{{ $meta['site_name'] ?? 'Amar Bangla Bazar' }}",
            currency: "৳",
            insideDhakaDeliveryCharge: 60,
            outsideDhakaDeliveryCharge: 120
        };
    </script>
</body>
</html>
