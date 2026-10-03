
@php
    $heroSlides = \App\Models\Slider::query()->active()->latest()->get();
@endphp


  @if (request()->is('/') || request()->is('/#booking'))
        <!-- <div class="absolute inset-0 -z-20">
            <img
                src="https://images.unsplash.com/photo-1563720223185-11003d516935?auto=format&fit=crop&w=2200&q=85"
                alt="Luxury chauffeur vehicle"
                class="h-full w-full object-cover"
            >
        </div> -->
        <div class="absolute inset-0 -z-10 bg-gradient-to-r from-black via-black/75 to-black/20"></div>
        <div class="absolute inset-0 -z-10 bg-gradient-to-t from-black via-transparent to-black/30"></div>
    @endif

    <div class="bg-[#d9b33f] text-[10px] font-semibold uppercase tracking-[0.22em] text-black">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-2 px-6 py-2 lg:px-8">
            <div class="flex flex-col items-center gap-1 sm:flex-row sm:gap-4">
                <a href="tel:+4402035550147" class="transition hover:opacity-80">Call: +44 20 3555 0147</a>
                <a href="mailto:bookings@royaledrive.com" class="transition hover:opacity-80">Email: bookings@royaledrive.com</a>
            </div>

            <div class="hidden items-center gap-3 sm:flex">
                <a href="https://www.facebook.com" target="_blank" rel="noopener noreferrer" aria-label="Facebook" class="flex h-6 w-6 items-center justify-center rounded-full border border-black/20 transition hover:bg-black hover:text-[#d9b33f]">
                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M13.5 22v-8h2.7l.4-3h-3.1V7.5c0-.9.3-1.5 1.6-1.5H17V3.1c-.3-.1-1.3-.1-2.4-.1-2.4 0-4.1 1.5-4.1 4.3V11H8v3h2.5v8h3z"/></svg>
                </a>
                <a href="https://www.instagram.com" target="_blank" rel="noopener noreferrer" aria-label="Instagram" class="flex h-6 w-6 items-center justify-center rounded-full border border-black/20 transition hover:bg-black hover:text-[#d9b33f]">
                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3.5" y="3.5" width="17" height="17" rx="5"/><circle cx="12" cy="12" r="4.2"/><circle cx="17.3" cy="6.7" r="1" fill="currentColor" stroke="none"/></svg>
                </a>
                <a href="https://www.linkedin.com" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn" class="flex h-6 w-6 items-center justify-center rounded-full border border-black/20 transition hover:bg-black hover:text-[#d9b33f]">
                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M6.9 8.3A1.8 1.8 0 1 1 6.9 4.7a1.8 1.8 0 0 1 0 3.6zm-1.5 1.3h3V19h-3V9.6zm5.1 0h2.8v1.3h.1c.4-.7 1.4-1.5 2.9-1.5 3.1 0 3.7 2 3.7 4.7V19h-3v-17.5c0-.9-.1-2-1.2-2-1.2 0-1.4 1-1.4 2V19h-3V9.6z"/></svg>
                </a>
            </div>
        </div>
    </div>

    <nav class="mx-auto flex max-w-7xl items-center justify-between px-6 py-5 lg:px-8">
        <a href="{{ url('/') }}" class="group flex items-center gap-3">
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
        @if ($heroSlides->isNotEmpty())
            <section class="relative isolate min-h-[650px] overflow-hidden lg:min-h-[560px]" data-hero-slider>
                @foreach ($heroSlides as $index => $slide)
                    <div class="{{ $index === 0 ? 'block' : 'hidden' }} absolute inset-0" data-hero-slide>
                        @if ($slide->image_path)
                            <img
                                src="{{ asset('storage/' . $slide->image_path) }}"
                                alt="{{ $slide->title }}"
                                class="absolute inset-0 h-full w-full object-cover"
                            >
                        @else
                            <img
                                src="https://images.unsplash.com/photo-1563720223185-11003d516935?auto=format&fit=crop&w=2200&q=85"
                                alt="Luxury chauffeur vehicle"
                                class="absolute inset-0 h-full w-full object-cover"
                            >
                        @endif

                        <div class="absolute inset-0 bg-black/40"></div>
                        <!-- <div class="absolute inset-0 bg-gradient-to-r from-black via-black/75 to-black/20"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-black/30"></div> -->

                        <div class="relative z-20 mx-auto flex min-h-[450px] max-w-7xl items-center px-6 pb-0 pt-0 lg:min-h-[560px] lg:px-8">
                            <div class="max-w-3xl">
                                @if ($slide->subtitle)
                                    <p class="mb-5 text-xs font-semibold uppercase tracking-[0.45em] text-[#d9b33f]">
                                        {{ $slide->subtitle }}
                                    </p>
                                @endif

                                <h1 class="font-serif text-5xl leading-[0.95] tracking-tight sm:text-7xl lg:text-8xl">
                                    {{ $slide->title }}
                                </h1>

                                @if ($slide->description)
                                    <p class="mt-7 max-w-xl text-sm leading-7 text-white/65 sm:text-base">
                                        {{ $slide->description }}
                                    </p>
                                @endif

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
                    </div>
                @endforeach

                <div class="absolute bottom-6 left-1/2 z-20 flex -translate-x-1/2 items-center gap-2">
                    @foreach ($heroSlides as $index => $slide)
                        <button
                            type="button"
                            data-slide-dot="{{ $index }}"
                            class="h-2.5 w-2.5 rounded-full {{ $index === 0 ? 'bg-[#d9b33f]' : 'bg-white/40' }} transition"
                            aria-label="Show slide {{ $index + 1 }}"
                        ></button>
                    @endforeach
                </div>
            </section>
        @else
            <section class="relative overflow-hidden">
                <div class="absolute inset-0 -z-20">
                    <img
                        src="https://images.unsplash.com/photo-1563720223185-11003d516935?auto=format&fit=crop&w=2200&q=85"
                        alt="Luxury chauffeur vehicle"
                        class="h-full w-full object-cover"
                    >
                </div>

                <div class="absolute inset-0 -z-10 bg-gradient-to-r from-black via-black/75 to-black/20"></div>
                <div class="absolute inset-0 -z-10 bg-gradient-to-t from-black via-transparent to-black/30"></div>

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
            </section>
        @endif

        @include('partials.site-booking-form')
    @endif
