
    @if (request()->is('/') || request()->is('/#booking'))
        <div class="absolute inset-0 -z-20">
            <img
                src="https://images.unsplash.com/photo-1563720223185-11003d516935?auto=format&fit=crop&w=2200&q=85"
                alt="Luxury chauffeur vehicle"
                class="h-full w-full object-cover"
            >
        </div>

        <div class="absolute inset-0 -z-10 bg-gradient-to-r from-black via-black/75 to-black/20"></div>
        <div class="absolute inset-0 -z-10 bg-gradient-to-t from-black via-transparent to-black/30"></div>
    @endif

    <nav class="mx-auto flex max-w-7xl items-center justify-between px-6 py-5 lg:px-8">
        <a href="#" class="group flex items-center gap-3">
            <span class="flex h-10 w-10 items-center justify-center rounded-full border border-[#d9b33f]/70 text-sm font-semibold tracking-[0.25em] text-[#d9b33f]">
                RD
            </span>
            <span>
                <span class="block text-sm font-semibold uppercase tracking-[0.35em]">RoyaleDrive</span>
                <span class="block text-[9px] uppercase tracking-[0.45em] text-[#d9b33f]">Chauffeur Services</span>
            </span>
        </a>

        <div class="hidden items-center gap-8 text-[11px] font-medium uppercase tracking-[0.2em] text-white/80 md:flex">
            <a href="{{ url('/') }}" class="transition hover:text-[#d9b33f]">Home</a>
            <a href="{{ url('pages/about') }}" class="transition hover:text-[#d9b33f]">About Us</a>
            <a href="{{ url('/#fleet') }}" class="transition hover:text-[#d9b33f]">Our Fleet</a>
            <a href="{{ url('/#chauffeurs') }}" class="transition hover:text-[#d9b33f]">Our Chauffeurs</a>
            <a href="{{ url('/#journal') }}" class="transition hover:text-[#d9b33f]">Blog</a> 
            <a href="{{ url('contact-us') }}" class="transition hover:text-[#d9b33f]">Contact Us</a>
        </div>

        <a href="#booking"
           class="hidden rounded-full border border-[#d9b33f] bg-[#d9b33f] px-5 py-2.5 text-[10px] font-bold uppercase tracking-[0.2em] text-black transition hover:bg-transparent hover:text-[#d9b33f] md:inline-flex">
            Reserve
        </a>

        <button id="menuButton" type="button" class="rounded-full border border-white/20 p-3 md:hidden" aria-label="Open menu">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </nav>


    <div id="mobileMenu" class="hidden border-y border-white/10 bg-black/95 px-6 py-6 md:hidden">
        <div class="flex flex-col gap-5 text-xs uppercase tracking-[0.2em] text-white/80">
           <a href="{{ url('/') }}">Home</a>
            <a href="{{ url('pages/about') }}">About Us</a>
            <a href="{{ url('/#fleet') }}">Our Fleet</a>
            <a href="{{ url('/#chauffeurs') }}">Our Chauffeurs</a>
            <a href="{{ url('/#journal') }}">Blog</a> 
            <a href="{{ url('contact-us') }}">Contact Us</a>
        </div>
    </div>

    @if (request()->is('/') || request()->is('/#booking'))
        <div class="mx-auto flex min-h-[650px] max-w-7xl items-center px-6 pb-24 pt-16 lg:min-h-[760px] lg:px-8">
            <div class="max-w-3xl">
                <p class="mb-5 text-xs font-semibold uppercase tracking-[0.45em] text-[#d9b33f]">
                    Executive Chauffeur Experience
                </p>

                <h1 class="font-serif text-5xl leading-[0.95] tracking-tight sm:text-7xl lg:text-8xl">
                    Experience<br>
                    <span class="text-[#d9b33f]">Luxury</span><br>
                    Without Limits
                </h1>

                <p class="mt-7 max-w-xl text-sm leading-7 text-white/65 sm:text-base">
                    Refined private travel for discerning passengers. From the moment you book
                    to the moment you arrive, every detail is designed to feel effortless.
                </p>

                <div class="mt-9 flex flex-wrap gap-4">
                    <a href="#booking" class="rounded-full bg-[#d9b33f] px-7 py-3.5 text-[10px] font-bold uppercase tracking-[0.22em] text-black transition hover:bg-white">
                        Book Your Journey
                    </a>
                    <a href="#fleet" class="rounded-full border border-white/25 px-7 py-3.5 text-[10px] font-bold uppercase tracking-[0.22em] text-white transition hover:border-[#d9b33f] hover:text-[#d9b33f]">
                        Explore Fleet
                    </a>
                </div>
            </div>
        </div>

        @include('partials.site-booking-form')
    @endif
