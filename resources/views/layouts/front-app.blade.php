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
    <!-- <script>
        var onSubmit = function(token) {
          console.log('success!');
          return true;
        };

        var onloadCallback = function() {
          grecaptcha.render('submit', {
            'sitekey' : '6LcqMcUtAAAAAL24EiqWIEqFzjpMNg9JI8-GugHL',
            'callback' : onSubmit
          });
        };
    </script> -->
</head>

<body class="min-h-screen bg-[#050505] text-white antialiased">

    {{-- Main application content --}}
    @yield('content')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const slider = document.querySelector('[data-hero-slider]');
            if (!slider) return;

            const slides = Array.from(slider.querySelectorAll('[data-hero-slide]'));
            const dots = Array.from(document.querySelectorAll('[data-slide-dot]'));

            if (slides.length < 2) return;

            let activeIndex = 0;
            let autoRotate = null;

            const showSlide = (index) => {
                activeIndex = (index + slides.length) % slides.length;

                slides.forEach((slide, slideIndex) => {
                    slide.classList.toggle('hidden', slideIndex !== activeIndex);
                    slide.classList.toggle('block', slideIndex === activeIndex);
                });

                dots.forEach((dot, dotIndex) => {
                    const isActive = dotIndex === activeIndex;
                    dot.classList.toggle('bg-[#d9b33f]', isActive);
                    dot.classList.toggle('bg-white/40', !isActive);
                });
            };

            dots.forEach((dot) => {
                dot.addEventListener('click', () => {
                    showSlide(Number(dot.dataset.slideDot));
                    clearInterval(autoRotate);
                    autoRotate = setInterval(() => showSlide(activeIndex + 1), 5000);
                });
            });

            autoRotate = setInterval(() => showSlide(activeIndex + 1), 5000);
        });
    </script>

    {{-- Global scripts --}}
    @stack('scripts')
     <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
        async defer>
    </script>
</body>
</html>
