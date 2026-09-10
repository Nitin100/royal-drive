<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'RoyaleDrive — Luxury Chauffeur Services')</title>

    <meta name="description"
          content="@yield('description', 'RoyaleDrive — refined private chauffeur travel for discerning passengers.')">

    {{-- Tailwind --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Optional Google Fonts used by the luxury reference design --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #050505;
        }

        .font-serif {
            font-family: 'Playfair Display', serif;
        }

        ::selection {
            background: #d9b33f;
            color: #050505;
        }
    </style>

    @stack('styles')
</head>

<body class="min-h-screen bg-[#050505] text-white antialiased">

    {{-- Main application content --}}
    @yield('content')

    {{-- Global scripts --}}
    @stack('scripts')
</body>
</html>
