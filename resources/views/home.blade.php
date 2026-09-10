@extends('layouts.front-app')

@section('title', 'RoyaleDrive — Experience Luxury Without Limits')

@section('content')
<div class="min-h-screen bg-[#050505] text-white selection:bg-[#d9b33f] selection:text-black">
<header class="relative isolate overflow-hidden">
    @include('partials.site-header')
</header>

    <section id="services" class="bg-[#030303] py-6 sm:py-8 lg:py-10">
    <div class="mx-auto max-w-[1440px] px-3 sm:px-5 lg:px-6">

    <div class="flex flex-col justify-between gap-6 md:flex-row md:items-end">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.35em] text-[#d9b33f]">Our Services</p>
                    <h2 class="mt-4 font-serif text-4xl sm:text-6xl">Vehicles That<br><span class="text-[#d9b33f]">Command Respect.</span></h2>
                </div>
                <a href="#" class="text-[10px] font-bold uppercase tracking-[0.25em] text-white/60 transition hover:text-[#d9b33f]">
                    View full  fleet →
                </a>
            </div>


        @php
            $homepageServices = $services ?? collect();

            if ($homepageServices->isEmpty()) {
                $homepageServices = collect([
                    [
                        'title' => 'Airport Transfers',
                        'summary' => 'Seamless terminal-to-destination journeys, timed to the minute.',
                        'description' => 'Private airport arrivals and departures designed around your schedule, with precise timing and a polished, discreet arrival experience.',
                        'image' => asset('images/services/airport-transfers.jpg'),
                        'pricing' => [
                            ['label' => 'Standard', 'amount' => 79, 'currency' => 'GBP'],
                            ['label' => 'Executive', 'amount' => 129, 'currency' => 'GBP'],
                        ],
                    ],
                    [
                        'title' => 'Corporate Travel',
                        'summary' => 'Executive transport crafted around your schedule, not ours.',
                        'description' => 'Professional chauffeur journeys for meetings, appointments and board-level travel that require punctuality and discretion.',
                        'image' => asset('images/services/private-aviation.jpg'),
                        'pricing' => [['label' => 'Daily', 'amount' => 145, 'currency' => 'GBP']],
                    ],
                    [
                        'title' => 'Wedding Transport',
                        'summary' => 'Arrive in effortless, unforgettable elegance.',
                        'description' => 'Luxury transportation for wedding parties, family arrivals and special moments that deserve a smooth, elegant finish.',
                        'image' => asset('images/services/private-aviation.jpg'),
                        'pricing' => [['label' => 'Package', 'amount' => 220, 'currency' => 'GBP']],
                    ],
                    [
                        'title' => 'Special Events',
                        'summary' => 'Gallery openings, galas, premiers — curated journeys for every occasion.',
                        'description' => 'Tailored chauffeur service for high-profile events, dinners, launches and occasions where presentation matters.',
                        'image' => asset('images/services/private-aviation.jpg'),
                        'pricing' => [['label' => 'Event', 'amount' => 180, 'currency' => 'GBP']],
                    ],
                    [
                        'title' => 'Private Aviation',
                        'summary' => 'Ground-to-air connections with the silence of seamless coordination.',
                        'description' => 'Between the terminal and the aircraft, your onward journey is managed quietly, efficiently and with complete attention to detail.',
                        'image' => asset('images/services/private-aviation.jpg'),
                        'pricing' => [['label' => 'Transfer', 'amount' => 240, 'currency' => 'GBP']],
                    ],
                    [
                        'title' => 'Hourly Hire',
                        'summary' => 'The city at your pace, on your terms, in your own time.',
                        'description' => 'Flexible private chauffeur hire designed for meetings, shopping, city visits and personal itineraries across the day.',
                        'image' => asset('images/services/private-aviation.jpg'),
                        'pricing' => [['label' => 'Hourly', 'amount' => 65, 'currency' => 'GBP']],
                    ],
                ]);
            }
        @endphp

        <div class="grid grid-cols-1 gap-[10px] sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($homepageServices->take(6) as $index => $service)
                @php
                    $serviceData = is_object($service) ? [
                        'title' => $service->title,
                        'summary' => trim(strip_tags((string) ($service->description ?? $service->meta_description ?? ''))),
                        'description' => trim(strip_tags((string) ($service->description ?? ''))),
                        'image' => $service->banner_image_path ? asset('storage/' . $service->banner_image_path) : asset('images/services/private-aviation.jpg'),
                        'pricing' => $service->pricing_config ?? [],
                    ] : [
                        'title' => $service['title'] ?? 'Service',
                        'summary' => $service['summary'] ?? '',
                        'description' => $service['description'] ?? ($service['summary'] ?? ''),
                        'image' => $service['image'] ?? asset('images/services/private-aviation.jpg'),
                        'pricing' => $service['pricing'] ?? [],
                    ];

                    $isFeatured = $index === 0;
                    $serviceLabel = $isFeatured ? 'Most Requested' : 'Signature Service';
                    $serviceIcon = ['✦', '◈', '◇', '✦', '◉', '◎'][$index % 6];
                @endphp

                <article
                    class="service-hover-card group relative min-h-[{{ $isFeatured ? '250' : '200' }}px] cursor-pointer overflow-hidden rounded-[15px] border border-[#181818] bg-[#090909] {{ $isFeatured ? 'sm:col-span-2' : '' }}"
                    data-service-modal
                    data-title="{{ $serviceData['title'] }}"
                    data-image="{{ $serviceData['image'] }}"
                    data-summary="{{ $serviceData['summary'] }}"
                    data-description="{{ $serviceData['description'] }}"
                    data-pricing='@json($serviceData['pricing'])'
                    tabindex="0"
                    aria-label="View details for {{ $serviceData['title'] }}"
                >
                    @if ($serviceData['image'])
                        <img
                            src="{{ $serviceData['image'] }}"
                            alt="{{ $serviceData['title'] }}"
                            class="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-[1.04]"
                        >
                    @endif

                    @if ($isFeatured)
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                        <div class="absolute left-4 top-4 z-10">
                            <span class="rounded-full border border-[#d8b333]/40 bg-black/60 px-3 py-1 text-[7px] uppercase tracking-[0.25em] text-[#e1bd3d] backdrop-blur-sm">
                                {{ $serviceLabel }}
                            </span>
                        </div>
                    @else
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    @endif

                    <div class="absolute left-5 top-5 z-10 text-[13px] text-[#d7b536]">
                        {{ $serviceIcon }}
                    </div>

                    <div class="absolute bottom-0 left-0 z-10 p-5 transition-all duration-500 {{ $isFeatured ? 'group-hover:-translate-y-3 group-hover:opacity-0' : 'group-hover:opacity-0' }}">
                        <h3 class="font-serif text-[{{ $isFeatured ? '22' : '20' }}px] text-white">
                            {{ $serviceData['title'] }}
                        </h3>
                    </div>

                    <div
                        class="absolute inset-x-0 bottom-0 z-20 bg-[#fff0bd] p-5 opacity-0 transition-all duration-500 ease-out {{ $isFeatured ? 'translate-y-full group-hover:translate-y-0 group-hover:opacity-100' : 'inset-0 flex flex-col justify-end translate-y-full group-hover:translate-y-0 group-hover:opacity-100' }}"
                    >
                        <div class="mb-auto text-[13px] text-[#111]">
                            {{ $serviceIcon }}
                        </div>

                        <div>
                            <h3 class="font-serif text-[{{ $isFeatured ? '22' : '20' }}px] leading-tight text-[#080808]">
                                {{ $serviceData['title'] }}
                            </h3>

                            <p class="mt-2 text-[9px] leading-[1.55] text-[#302b1d] sm:text-[10px]">
                                {{ $serviceData['summary'] ?: 'Tailored private chauffeur service for every journey.' }}
                            </p>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>

    <div id="serviceModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true" role="dialog">
        <div class="fixed inset-0 bg-black/80 backdrop-blur-sm" data-close-service-modal></div>

        <div class="relative mx-auto flex min-h-full items-center justify-center p-4 sm:p-6">
            <div class="relative w-full max-w-2xl overflow-hidden rounded-[24px] border border-[#d9b33f]/20 bg-[#111111] shadow-2xl">
                <button type="button" data-close-service-modal class="absolute right-4 top-4 z-20 rounded-full border border-white/10 bg-black/40 px-3 py-1 text-xs text-white/80 transition hover:border-[#d9b33f] hover:text-[#d9b33f]" aria-label="Close service details">
                    Close
                </button>

                <img id="serviceModalImage" src="" alt="" class="h-56 w-full object-cover">

                <div class="p-6 sm:p-8">
                    <p class="text-[9px] font-bold uppercase tracking-[0.3em] text-[#d9b33f]">Private Chauffeur Service</p>
                    <h3 id="serviceModalTitle" class="mt-3 font-serif text-3xl text-white"></h3>
                    <p id="serviceModalDescription" class="mt-4 text-sm leading-7 text-white/70"></p>

                    <div id="serviceModalPricing" class="mt-6 space-y-3"></div>
                </div>
            </div>
        </div>
    </div>
</section>

    {{-- =========================
        OUR STORY
    ========================== --}}
    <section id="story" class="bg-[#d9b33f] py-0 sm:py-0">
        <div class="mx-auto grid max-w-7xl items-center gap-14 px-6 lg:grid-cols-2 lg:px-8">
            <div class="relative">
                <div class="absolute -left-4 -top-4 h-32 w-32 border-l border-t border-[#d9b33f]/60"></div>
                <img
                    src="https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&w=1400&q=85"
                    alt="Luxury vehicle"
                    class="relative aspect-[4/3] w-full rounded-sm object-cover grayscale-[15%]"
                >
                <div class="absolute -bottom-5 -right-5 hidden rounded-sm bg-[#d9b33f] px-7 py-5 text-black sm:block">
                    <p class="text-[9px] font-bold uppercase tracking-[0.3em]">Since 2014</p>
                    <p class="mt-1 font-serif text-2xl">Driven by detail.</p>
                </div>
            </div>

            <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.35em] text-[#d9b33f]">Our Story</p>
                <h2 class="mt-4 max-w-xl font-serif text-4xl leading-tight sm:text-5xl">
                    Born From a Belief That Travel Should <span class="text-[#d9b33f]">Elevate.</span>
                </h2>
                <p class="mt-7 text-sm leading-7 text-white/55">
                    RoyaleDrive was created around a simple idea: premium transportation should
                    be more than getting from one place to another. It should be calm, considered,
                    discreet and memorable.
                </p>
                <p class="mt-4 text-sm leading-7 text-white/55">
                    Every touchpoint is designed around the passenger — polished vehicles,
                    professional chauffeurs, thoughtful timing and a service standard that
                    respects your time.
                </p>

                <div class="mt-8 flex items-center gap-4">
                    <span class="h-px w-12 bg-[#d9b33f]"></span>
                    <span class="text-[10px] uppercase tracking-[0.25em] text-white/50">The Royale standard</span>
                </div>
            </div>
        </div>
    </section>


   {{-- =========================================================
    WHY ROYALE DRIVE SECTION
========================================================= --}}


    {{-- =========================
        PROCESS
    ========================== --}}
    <section id="process" class="bg-[#050505] pb-24 sm:pb-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="border-t border-white/10 pt-16">
                <div class="grid gap-12 lg:grid-cols-[0.8fr_1.2fr]">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-[0.35em] text-[#d9b33f]">The Process</p>
                        <h2 class="mt-4 font-serif text-4xl sm:text-5xl">Effortless From<br><span class="text-[#d9b33f]">Start to Finish.</span></h2>
                    </div>

                    <div class="grid gap-0 sm:grid-cols-4">
                        @php
                            $steps = [
                                ['01', 'Request', 'Tell us where, when and how you would like to travel.'],
                                ['02', 'Confirm', 'We confirm your vehicle, chauffeur and journey details.'],
                                ['03', 'Travel', 'Your chauffeur arrives prepared and ready on time.'],
                                ['04', 'Arrive', 'Relax and enjoy a seamless journey to your destination.'],
                            ];
                        @endphp

                        @foreach ($steps as $step)
                            <div class="border-l border-white/10 px-5 py-4 first:border-l-0">
                                <span class="text-xs text-[#d9b33f]">{{ $step[0] }}</span>
                                <h3 class="mt-7 font-serif text-xl">{{ $step[1] }}</h3>
                                <p class="mt-3 text-xs leading-6 text-white/45">{{ $step[2] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    
    {{-- =========================
        FLEET
    ========================== --}}
    <section id="fleet" class="bg-[#d9b33f] py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="flex flex-col justify-between gap-6 md:flex-row md:items-end">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.35em] text-[#ffffff]">Our Fleet</p>
                    <h2 class="mt-4 font-serif text-4xl sm:text-6xl">Vehicles That<br><span class="text-[#ffffff]">Command Respect.</span></h2>
                </div>
                <a href="#" class="text-[10px] font-bold uppercase tracking-[0.25em] text-white/60 transition hover:text-[#ffffff]">
                    View full fleet →
                </a>
            </div>

            @php
                $fleetBrowseList = $featuredFleets ?? collect();

                if ($fleetBrowseList->isEmpty()) {
                    $fleetBrowseList = collect([
                        (object) [
                            'name' => 'Mercedes S-Class',
                            'slug' => 'mercedes-s-class',
                            'category' => 'Executive Sedan',
                            'passenger_capacity' => 4,
                            'luggage_capacity' => 3,
                            'banner_image_path' => null,
                            'pricing_config' => [['label' => 'Hourly', 'price' => 65, 'currency' => 'OMR']],
                        ],
                        (object) [
                            'name' => 'BMW 7 Series',
                            'slug' => 'bmw-7-series',
                            'category' => 'Luxury Sedan',
                            'passenger_capacity' => 4,
                            'luggage_capacity' => 3,
                            'banner_image_path' => null,
                            'pricing_config' => [['label' => 'Hourly', 'price' => 75, 'currency' => 'OMR']],
                        ],
                        (object) [
                            'name' => 'Rolls Royce Ghost',
                            'slug' => 'rolls-royce-ghost',
                            'category' => 'Luxury Sedan',
                            'passenger_capacity' => 4,
                            'luggage_capacity' => 2,
                            'banner_image_path' => null,
                            'pricing_config' => [['label' => 'Hourly', 'price' => 220, 'currency' => 'OMR']],
                        ],
                    ]);
                }
            @endphp

            <div class="mt-14 grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ($fleetBrowseList->take(4) as $vehicle)
                    @php
                        $vehicleImage = $vehicle->banner_image_path
                            ? asset('storage/' . $vehicle->banner_image_path)
                            : asset('images/vehicles/mercedes-s-class.jpg');
                        $vehiclePrice = collect($vehicle->pricing_config ?? [])->first()['price']
                            ?? collect($vehicle->pricing_config ?? [])->first()['amount']
                            ?? 65;
                    @endphp

                    <article class="group overflow-hidden rounded-2xl border border-none bg-[#101010]">
                        <div class="relative aspect-[4/3] overflow-hidden">
                            <img src="{{ $vehicleImage }}" alt="{{ $vehicle->name ?? 'Luxury Vehicle' }}" class="h-full w-full object-cover transition duration-700 group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                            <span class="absolute bottom-4 left-4 rounded-full border border-white/20 bg-black/40 px-3 py-1 text-[8px] uppercase tracking-[0.2em] text-white/75 backdrop-blur">
                                {{ $vehicle->category ?? 'Luxury Vehicle' }}
                            </span>
                        </div>
                        <div class="p-5">
                            <h3 class="font-serif text-2xl">{{ $vehicle->name ?? 'Luxury Vehicle' }}</h3>
                            <p class="mt-2 text-xs leading-6 text-white/50">
                                <?= strip_tags($vehicle->description ?? 'Elegant, quiet and tailored for executive transfers and private occasions.') ?>
                            </p>
                            <div class="mt-4 text-[9px] font-bold uppercase tracking-[0.2em] text-[#d9b33f]">
                                From OMR {{ $vehiclePrice }}
                            </div>
                            <a href="{{ route('fleet.details', $vehicle->slug ?? $vehicle->name) }}" class="mt-5 inline-block text-[9px] font-bold uppercase tracking-[0.2em] text-[#d9b33f]">View Details →</a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- =========================
        CHAUFFEURS
    ========================== --}}
    


   {{-- =========================================================
    CLIENT VOICES / TESTIMONIAL SLIDER
========================================================= --}}
<section
    id="testimonials"
    class="relative overflow-hidden bg-[#050505] text-white"
>
    <div
        class="mx-auto flex min-h-[520px] max-w-[1200px] items-center justify-center px-6 py-20 sm:min-h-[560px] sm:px-8 lg:min-h-[580px] lg:px-10"
    >

        <div class="w-full text-center">

            {{-- =================================================
                SECTION LABEL
            ================================================= --}}
            <div
                class="mb-14 text-[12px] font-medium uppercase tracking-[0.18em] text-[#d9b735] sm:mb-16"
            >
                Client Voices
            </div>


            {{-- =================================================
                TESTIMONIAL SLIDER
            ================================================= --}}
            <div
                id="testimonial-slider"
                class="relative mx-auto max-w-[760px]"
            >

                {{-- =============================================
                    SLIDES
                ============================================== --}}
                <div class="relative min-h-[225px] sm:min-h-[220px]">

                    {{-- SLIDE 1 --}}
                    <div
                        class="testimonial-slide absolute inset-0 flex flex-col items-center justify-center opacity-100 transition-all duration-500 ease-out"
                        data-slide="0"
                    >

                        {{-- Large quote mark --}}
                        <div
                            class="pointer-events-none absolute -top-12 left-1/2 -translate-x-1/2 select-none font-serif text-[70px] leading-none text-white/[0.01] sm:text-[82px]"
                        >
                            “
                        </div>

                        {{-- Testimonial --}}
                        <blockquote
                            class="relative max-w-[700px] font-serif text-[18px] italic leading-[1.65] text-white sm:text-[20px] lg:text-[21px]"
                        >
                            Every journey with Royale Drive is an occasion.
                            The attention to detail, the impeccable timing,
                            the understanding of discretion — it is simply
                            unmatched in my experience.
                        </blockquote>

                        {{-- Gold divider --}}
                        <div class="mt-8 h-px w-[20px] bg-[#d8b534]"></div>

                        {{-- Client --}}
                        <div class="mt-4">
                            <div
                                class="text-[14px] font-large text-[#d8b534]"
                            >
                                Elizabeth Thornton
                            </div>

                            <div
                                class="mt-2 text-[9px] tracking-[0.02em] text-white/55 sm:text-[10px]"
                            >
                                Chief Executive Officer, Meridian Group
                                <span class="mx-1 text-white/20">•</span>
                                London
                            </div>
                        </div>

                    </div>


                    {{-- SLIDE 2 --}}
                    <div
                        class="testimonial-slide pointer-events-none absolute inset-0 flex flex-col items-center justify-center opacity-0 transition-all duration-500 ease-out"
                        data-slide="1"
                    >

                        <div
                            class="pointer-events-none absolute -top-12 left-1/2 -translate-x-1/2 select-none font-serif text-[70px] leading-none text-white/[0.01] sm:text-[82px]"
                        >
                            “
                        </div>

                        <blockquote
                            class="relative max-w-[700px] font-serif text-[18px] italic leading-[1.65] text-white sm:text-[20px] lg:text-[21px]"
                        >
                            From the first booking to the final destination,
                            every detail was handled with extraordinary
                            precision. Royale Drive understands what luxury
                            service truly means.
                        </blockquote>

                        <div class="mt-8 h-px w-[20px] bg-[#d8b534]"></div>

                        <div class="mt-4">
                            <div
                                class="text-[14px] font-large text-[#d8b534]"
                            >
                                Alexander Reid
                            </div>

                            <div
                                class="mt-2 text-[9px] tracking-[0.02em] text-white/55 sm:text-[10px]"
                            >
                                Managing Director, Sterling Partners
                                <span class="mx-1 text-white/20">•</span>
                                New York
                            </div>
                        </div>

                    </div>


                    {{-- SLIDE 3 --}}
                    <div
                        class="testimonial-slide pointer-events-none absolute inset-0 flex flex-col items-center justify-center opacity-0 transition-all duration-500 ease-out"
                        data-slide="2"
                    >

                        <div
                            class="pointer-events-none absolute -top-12 left-1/2 -translate-x-1/2 select-none font-serif text-[70px] leading-none text-white/[0.01] sm:text-[82px]"
                        >
                            “
                        </div>

                        <blockquote
                            class="relative max-w-[700px] font-serif text-[18px] italic leading-[1.65] text-white sm:text-[20px] lg:text-[21px]"
                        >
                            There is a rare confidence that comes from knowing
                            everything has already been taken care of. That is
                            exactly what Royale Drive delivers.
                        </blockquote>

                        <div class="mt-8 h-px w-[20px] bg-[#d8b534]"></div>

                        <div class="mt-4">
                            <div
                                class="text-[14px] font-large text-[#d8b534]"
                            >
                                Sophia Bennett
                            </div>

                            <div
                                class="mt-2 text-[9px] tracking-[0.02em] text-white/55 sm:text-[10px]"
                            >
                                Private Client, International Finance
                                <span class="mx-1 text-white/20">•</span>
                                Dubai
                            </div>
                        </div>

                    </div>

                </div>


                {{-- =================================================
                    SLIDER CONTROLS
                ================================================= --}}
                <div class="mt-8 flex items-center justify-center gap-4">

                    {{-- Previous --}}
                    <button
                        id="testimonial-prev"
                        type="button"
                        aria-label="Previous testimonial"
                        class="group flex h-[30px] w-[30px] items-center justify-center rounded-full border border-white/10 transition duration-300 hover:border-[#d8b534]/50 hover:bg-white/[0.03]"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1"
                            class="h-3 w-3 text-white/50 transition group-hover:text-[#d8b534]"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15 18l-6-6 6-6"
                            />
                        </svg>
                    </button>


                    {{-- Pagination --}}
                    <div
                        id="testimonial-dots"
                        class="flex items-center gap-[6px]"
                    >

                        {{-- Active --}}
                        <button
                            type="button"
                            aria-label="Go to testimonial 1"
                            data-dot="0"
                            class="testimonial-dot h-[4px] w-[17px] rounded-full bg-[#d8b534] transition-all duration-300"
                        ></button>

                        {{-- Dot --}}
                        <button
                            type="button"
                            aria-label="Go to testimonial 2"
                            data-dot="1"
                            class="testimonial-dot h-[4px] w-[4px] rounded-full bg-white/20 transition-all duration-300"
                        ></button>

                        {{-- Dot --}}
                        <button
                            type="button"
                            aria-label="Go to testimonial 3"
                            data-dot="2"
                            class="testimonial-dot h-[4px] w-[4px] rounded-full bg-white/20 transition-all duration-300"
                        ></button>

                    </div>


                    {{-- Next --}}
                    <button
                        id="testimonial-next"
                        type="button"
                        aria-label="Next testimonial"
                        class="group flex h-[30px] w-[30px] items-center justify-center rounded-full border border-white/10 transition duration-300 hover:border-[#d8b534]/50 hover:bg-white/[0.03]"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1"
                            class="h-3 w-3 text-white/50 transition group-hover:text-[#d8b534]"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9 18l6-6-6-6"
                            />
                        </svg>
                    </button>

                </div>

            </div>

        </div>
    </div>
</section>


{{-- =========================================================
    TESTIMONIAL SLIDER JAVASCRIPT
========================================================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const slides = document.querySelectorAll('.testimonial-slide');
    const dots = document.querySelectorAll('.testimonial-dot');

    const prevButton = document.getElementById('testimonial-prev');
    const nextButton = document.getElementById('testimonial-next');

    let currentSlide = 0;
    let autoplay;

    function showSlide(index) {

        if (index >= slides.length) {
            index = 0;
        }

        if (index < 0) {
            index = slides.length - 1;
        }

        currentSlide = index;

        slides.forEach((slide, i) => {

            if (i === currentSlide) {

                slide.classList.remove(
                    'opacity-0',
                    'pointer-events-none'
                );

                slide.classList.add(
                    'opacity-100'
                );

            } else {

                slide.classList.remove(
                    'opacity-100'
                );

                slide.classList.add(
                    'opacity-0',
                    'pointer-events-none'
                );
            }

        });


        // Update dots
        dots.forEach((dot, i) => {

            if (i === currentSlide) {

                dot.classList.remove(
                    'w-[4px]',
                    'bg-white/20'
                );

                dot.classList.add(
                    'w-[17px]',
                    'bg-[#d8b534]'
                );

            } else {

                dot.classList.remove(
                    'w-[17px]',
                    'bg-[#d8b534]'
                );

                dot.classList.add(
                    'w-[4px]',
                    'bg-white/20'
                );
            }

        });
    }


    function nextSlide() {
        showSlide(currentSlide + 1);
    }


    function previousSlide() {
        showSlide(currentSlide - 1);
    }


    function startAutoplay() {

        stopAutoplay();

        autoplay = setInterval(() => {
            nextSlide();
        }, 6000);
    }


    function stopAutoplay() {

        if (autoplay) {
            clearInterval(autoplay);
        }
    }


    // Previous
    prevButton.addEventListener('click', function () {
        previousSlide();
        startAutoplay();
    });


    // Next
    nextButton.addEventListener('click', function () {
        nextSlide();
        startAutoplay();
    });


    // Pagination
    dots.forEach((dot) => {

        dot.addEventListener('click', function () {

            const index = parseInt(
                this.dataset.dot,
                10
            );

            showSlide(index);
            startAutoplay();
        });

    });


    // Pause while hovering
    const slider = document.getElementById('testimonial-slider');

    slider.addEventListener('mouseenter', stopAutoplay);
    slider.addEventListener('mouseleave', startAutoplay);


    // Start
    showSlide(0);
    startAutoplay();

});
</script>

    {{-- =========================
        FAQ
    ========================== --}}
    

    {{-- ============================================================
    FLEET BLOG / INSIGHTS SECTION
============================================================ --}}
<section
    id="fleet-blog"
    class="relative overflow-hidden bg-[#d9b437] py-16 sm:py-20 lg:py-24"
>
    <div class="mx-auto max-w-[1180px] px-5 sm:px-8 lg:px-10">

        {{-- ========================================================
            SECTION HEADER
        ========================================================= --}}
        <div class="mx-auto mb-10 max-w-[760px] text-center sm:mb-12 lg:mb-14">

            <h2
                class="font-sans text-[34px] font-extrabold uppercase leading-none tracking-[-0.03em] text-white sm:text-[42px] lg:text-[48px]"
            >
                Our Blog
            </h2>

            <p
                class="mx-auto mt-5 max-w-[700px] text-[14px] font-medium leading-[1.35] text-[#111111] sm:text-[15px] lg:text-[16px]"
            >
                Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
            </p>

        </div>


        {{-- ========================================================
            BLOG GRID
        ========================================================= --}}
        @php
            $blogCards = $blogPosts ?? collect();

            if ($blogCards->isEmpty()) {
                $blogCards = collect();
            }
        @endphp

        <div class="grid gap-7 md:grid-cols-3 lg:gap-8">
            @foreach ($blogCards->take(3) as $index => $blogItem)
                @php
                    $blogTitle = $blogItem->title ?? 'Luxury Travel Insight';
                    $blogSummary = trim(strip_tags((string) ($blogItem->meta_description ?? ($blogItem->content ?? ''))));
                    $blogSummary = $blogSummary !== '' ? Str::limit($blogSummary, 140) : 'Discover the finer details of refined private travel and premium chauffeur experiences.';
                    $blogImage = $blogItem->featured_image_path
                        ? (str_starts_with($blogItem->featured_image_path, 'http') ? $blogItem->featured_image_path : asset('storage/' . $blogItem->featured_image_path))
                        : 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?auto=format&fit=crop&w=1200&q=80';
                    $blogSlug = $blogItem->slug ?? 'luxury-travel-insight';
                    $isFeatureCard = $index === 1;
                @endphp

                <article class="group flex flex-col {{ $isFeatureCard ? 'overflow-hidden rounded-[4px] bg-[#050505] text-white shadow-[0_15px_35px_rgba(0,0,0,0.12)]' : '' }}">
                    <div class="relative aspect-[1.35/1] overflow-hidden {{ $isFeatureCard ? '' : 'rounded-[5px] border border-black/20 bg-black' }}">
                        <img
                            src="{{ $blogImage }}"
                            alt="{{ $blogTitle }}"
                            class="h-full w-full object-cover transition duration-700 ease-out group-hover:scale-105"
                        >

                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-70"></div>
                    </div>

                    <div class="{{ $isFeatureCard ? 'flex flex-1 flex-col px-6 pb-5 pt-9 sm:px-7' : 'px-6 pb-1 pt-9 sm:px-7' }}">
                        <h3 class="text-[20px] font-extrabold leading-tight {{ $isFeatureCard ? 'text-[#d9b437]' : 'text-[#080808]' }} sm:text-[21px]">
                            {{ $blogTitle }}
                        </h3>

                        <p class="mt-2 max-w-[280px] text-[14px] leading-[1.18] {{ $isFeatureCard ? 'text-white' : 'text-white sm:text-[15px]' }}">
                            {{ $blogSummary }}
                        </p>

                        @if ($isFeatureCard)
                            <div class="mt-auto pt-5">
                                <a href="{{ route('blog.show', $blogSlug) }}" class="inline-flex min-w-[111px] items-center justify-center rounded-[4px] bg-[#d9b437] px-5 py-3 text-[11px] font-semibold text-white transition duration-300 hover:bg-white hover:text-[#080808]">
                                    Read More
                                </a>
                            </div>
                        @else
                            <a href="{{ route('blog.show', $blogSlug) }}" class="mt-5 inline-flex min-w-[111px] items-center justify-center rounded-[4px] bg-[#080808] px-5 py-3 text-[11px] font-semibold text-white transition duration-300 hover:bg-white hover:text-[#080808]">
                                Read More
                            </a>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>

    </div>
</section>


    {{-- =========================
        JOURNAL
    ========================== --}}


    <!-- <section id="journal" class="bg-[#050505] py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="flex items-end justify-between gap-6">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.35em] text-[#d9b33f]">Journal</p>
                    <h2 class="mt-4 font-serif text-4xl sm:text-6xl">Perspectives on<br><span class="text-[#d9b33f]">Exceptional Travel.</span></h2>
                </div>
                <a href="#" class="hidden text-[10px] font-bold uppercase tracking-[0.25em] text-white/50 hover:text-[#d9b33f] sm:block">View all articles →</a>
            </div>

            @php
                $articles = [
                    ['Travel without friction', 'The art of a seamless executive journey.', 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=1300&q=85'],
                    ['The city after dark', 'Why private travel changes the way you experience a destination.', 'https://images.unsplash.com/photo-1514565131-fce0801e5785?auto=format&fit=crop&w=1300&q=85'],
                    ['Inside the luxury cabin', 'What discerning travellers should expect from premium road travel.', 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&w=1300&q=85'],
                ];
            @endphp

            <div class="mt-14 grid gap-5 md:grid-cols-3">
                @foreach ($articles as $article)
                    <article class="group overflow-hidden rounded-2xl border border-white/10">
                        <div class="aspect-[16/10] overflow-hidden">
                            <img src="{{ $article[2] }}" alt="{{ $article[0] }}" class="h-full w-full object-cover transition duration-700 group-hover:scale-105">
                        </div>
                        <div class="p-6">
                            <p class="text-[9px] font-bold uppercase tracking-[0.2em] text-[#d9b33f]">Travel Journal</p>
                            <h3 class="mt-3 font-serif text-2xl">{{ $article[0] }}</h3>
                            <p class="mt-2 text-xs leading-6 text-white/45">{{ $article[1] }}</p>
                            <a href="#" class="mt-5 inline-block text-[9px] font-bold uppercase tracking-[0.2em] text-white/70 hover:text-[#d9b33f]">Read article →</a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section> -->
<section
    class="relative overflow-hidden bg-[#f6f2d6] px-5 pt-12 sm:px-8 sm:pt-14 lg:px-10 lg:pt-16"
>
    <div class="mx-auto max-w-[1200px] text-center">

        {{-- Exclusive chauffeur badge --}}
        <div class="mb-5 flex justify-center">
            <div
                class="inline-flex items-center gap-2 rounded-full bg-[#171610] px-5 py-2 text-[7px] font-semibold uppercase tracking-[0.2em] text-[#d9b83c] shadow-sm"
            >
                <span class="text-[7px]">✦</span>

                <span>
                    Exclusive Chauffeur Service
                </span>

                <span class="text-[7px]">✦</span>
            </div>
        </div>


        {{-- Main heading --}}
        <h1
            class="font-serif text-[34px] font-semibold leading-[1.05] tracking-[-0.025em] text-[#17130a] sm:text-[44px] lg:text-[48px]"
        >
            Arrive in Unrivalled Luxury
        </h1>


        {{-- Subtitle --}}
        <p
            class="mt-3 text-[10px] font-medium text-[#745d08] sm:text-[11px]"
        >
            Reserve your private chauffeur experience —
            seamless, sophisticated, supreme.
        </p>


        {{-- Decorative divider --}}
        <div class="mt-6 flex items-center justify-center gap-3">

            <span
                class="h-px w-12 bg-[#17130a]/25 sm:w-14"
            ></span>

            <span
                class="block h-[6px] w-[6px] rotate-45 bg-[#17130a]"
            ></span>

            <span
                class="h-px w-12 bg-[#17130a]/25 sm:w-14"
            ></span>

        </div>

    </div>
</section>


    {{-- =========================================================
    BOOK YOUR LUXURY RIDE
========================================================= --}}
<section
    id="booking"
    class="relative overflow-hidden bg-[#f6f2d6] py-16 sm:py-20 lg:py-24"
>
    <div class="mx-auto max-w-[1050px] px-5 sm:px-8 lg:px-10">

        <div class="grid items-start gap-8 lg:grid-cols-[280px_minmax(0,1fr)] lg:gap-10">

            @php
                $fleetBrowseList = $featuredFleets ?? collect();

                if ($fleetBrowseList->isEmpty()) {
                    $fleetBrowseList = collect([
                        (object) [
                            'name' => 'Mercedes S-Class',
                            'passenger_capacity' => 4,
                            'luggage_capacity' => 3,
                            'banner_image_path' => null,
                            'pricing_config' => [['label' => 'Hourly', 'amount' => 65, 'currency' => 'OMR']],
                        ],
                        (object) [
                            'name' => 'BMW 7 Series',
                            'passenger_capacity' => 4,
                            'luggage_capacity' => 3,
                            'banner_image_path' => null,
                            'pricing_config' => [['label' => 'Hourly', 'amount' => 75, 'currency' => 'OMR']],
                        ],
                        (object) [
                            'name' => 'Rolls Royce Ghost',
                            'passenger_capacity' => 4,
                            'luggage_capacity' => 2,
                            'banner_image_path' => null,
                            'pricing_config' => [['label' => 'Hourly', 'amount' => 220, 'currency' => 'OMR']],
                        ],
                    ]);
                }

                $selectedFleet = $fleetBrowseList->first();
                $selectedFleetImage = $selectedFleet && $selectedFleet->banner_image_path
                    ? asset('storage/' . $selectedFleet->banner_image_path)
                    : asset('images/vehicles/mercedes-s-class.jpg');
                $selectedFleetPrice = collect($selectedFleet->pricing_config ?? [])->first()['price']
                    ?? collect($selectedFleet->pricing_config ?? [])->first()['amount']
                    ?? 65;
                $selectedFleetPassengers = $selectedFleet->passenger_capacity ?? 4;
                $selectedFleetLuggage = $selectedFleet->luggage_capacity ?? 3;
            @endphp

            {{-- =====================================================
                LEFT VEHICLE CARD
            ====================================================== --}}
            <div
                class="mx-auto w-full max-w-[280px] overflow-hidden rounded-[12px] border border-[#252525] bg-[#111110] text-white shadow-[0_15px_50px_rgba(0,0,0,0.12)] lg:mx-0"
            >

                {{-- Vehicle image --}}
                <div class="relative h-[135px] overflow-hidden">

                    <img
                        id="featuredFleetImage"
                        src="{{ $selectedFleetImage }}"
                        alt="{{ $selectedFleet->name ?? 'Mercedes S-Class' }}"
                        class="h-full w-full object-cover"
                    >

                    <div
                        class="absolute inset-0 bg-gradient-to-t from-[#111110] via-transparent to-black/20"
                    ></div>

                    <div class="absolute left-3 top-3">
                        <span
                            class="inline-flex items-center gap-1 rounded-full border border-[#d9b83c]/30 bg-[#151515]/90 px-2 py-1 text-[8px] font-semibold uppercase tracking-[0.18em] text-[#d9b83c]"
                        >
                            <span>✦</span>
                            Premium Choice
                        </span>
                    </div>

                    <div
                        class="absolute right-3 top-3 text-[8px] text-white/60"
                    >
                        {{ $selectedFleet->category ?? 'Executive Sedan' }}
                    </div>

                </div>

                <div class="px-3 pb-3">
                    <div class="flex items-end justify-between">
                        <h3 id="featuredFleetName" class="font-serif text-[16px] font-semibold leading-none text-white">
                            {{ $selectedFleet->name ?? 'Mercedes S-Class' }}
                        </h3>

                        <div class="text-right">
                            <div class="text-[8px] uppercase tracking-wider text-white/40">
                                Starting from
                            </div>

                            <div id="featuredFleetPrice" class="font-serif text-[16px] leading-none text-[#dfbd3d]">
                                OMR {{ $selectedFleetPrice }}
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-1.5">
                        <div id="featuredFleetPassengers" class="rounded-md border border-white/10 bg-white/[0.025] px-2 py-2 text-[8px] text-white/70">
                            <span class="mr-1 text-[#8b4bd8]">♟</span>
                            {{ $selectedFleetPassengers }} Passengers
                        </div>

                        <div id="featuredFleetLuggage" class="rounded-md border border-white/10 bg-white/[0.025] px-2 py-2 text-[8px] text-white/70">
                            <span class="mr-1 text-[#368fe7]">▣</span>
                            {{ $selectedFleetLuggage }} Bags
                        </div>

                        <div class="rounded-md border border-white/10 bg-white/[0.025] px-2 py-2 text-[8px] text-white/70">
                            <span class="mr-1 text-[#32a4d8]">◈</span>
                            Premium AC
                        </div>

                        <div class="rounded-md border border-white/10 bg-white/[0.025] px-2 py-2 text-[8px] text-white/70">
                            <span class="mr-1 text-[#dfbd3d]">★</span>
                            Chauffeur Included
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="mb-2 text-[8px] uppercase tracking-[0.18em] text-white/35">
                            Browse Fleet
                        </div>

                        @foreach ($fleetBrowseList->take(3) as $fleetOption)
                            @php
                                $fleetOptionPrice = collect($fleetOption->pricing_config ?? [])->first()['price']
                                    ?? collect($fleetOption->pricing_config ?? [])->first()['amount']
                                    ?? 65;
                                $fleetOptionIsSelected = $loop->first;
                            @endphp

                            <button
                                type="button"
                                data-fleet-slug="{{ $fleetOption->slug ?? $fleetOption->name ?? 'fleet' }}"
                                data-fleet-name="{{ $fleetOption->name ?? 'Luxury Vehicle' }}"
                                data-fleet-price="{{ $fleetOptionPrice }}"
                                class="fleet-option-btn mt-1 flex w-full items-center justify-between rounded px-2.5 py-2 text-left transition {{ $fleetOptionIsSelected ? 'border border-[#d9b83c]/40 bg-[#d9b83c]/10' : 'border border-white/5 bg-white/[0.02] hover:bg-white/[0.05]' }}"
                            >
                                <span class="text-[8px] {{ $fleetOptionIsSelected ? 'text-[#d9b83c]' : 'text-white/60' }}">
                                    {{ $fleetOption->name ?? 'Luxury Vehicle' }}
                                </span>

                                <span class="text-[8px] {{ $fleetOptionIsSelected ? 'font-semibold text-[#d9b83c]' : 'text-white/30' }}">
                                    OMR {{ $fleetOptionPrice }}
                                </span>
                            </button>
                        @endforeach
                    </div>

                    <div class="mt-4 grid grid-cols-3 border-t border-white/10 pt-3 text-center">
                        <div>
                            <div class="font-serif text-[10px] text-[#d9b83c]">5.0</div>
                            <div class="text-[8px] text-white/40">Rating</div>
                        </div>

                        <div class="border-x border-white/10">
                            <div class="font-serif text-[10px] text-[#d9b83c]">2,400+</div>
                            <div class="text-[8px] text-white/40">Rides</div>
                        </div>

                        <div>
                            <div class="font-serif text-[10px] text-[#d9b83c]">24/7</div>
                            <div class="text-[8px] text-white/40">Support</div>
                        </div>
                    </div>
                </div>
            </div>


            {{-- =====================================================
                RIGHT BOOKING FORM
            ====================================================== --}}
            <div
                class="rounded-[12px] border border-[#292929] bg-[#121211] p-5 text-white shadow-[0_20px_60px_rgba(0,0,0,0.15)] sm:p-6 lg:p-7"
            >

                {{-- =================================================
                    FORM HEADER
                ================================================= --}}
                <div class="border-b border-white/10 pb-5">

                    <h2
                        class="font-serif text-[25px] font-semibold leading-none text-white sm:text-[28px]"
                    >
                        Book Your Luxury Ride
                    </h2>

                    <p
                        class="mt-2 text-[7px] text-white/40 sm:text-[8px]"
                    >
                        Reserve your chauffeur in less than a minute.
                    </p>

                    <div class="mt-4 flex items-center gap-2">
                        <span class="h-[1px] w-8 bg-[#d9b83c]"></span>
                        <span class="h-[1px] w-2 bg-[#d9b83c]/30"></span>
                        <span class="h-[1px] w-2 bg-[#d9b83c]/10"></span>
                    </div>

                </div>


                {{-- =================================================
                    FORM
                ================================================= --}}
                <form
                    action="{{ route('booking.store') }}"
                    method="POST"
                    class="mt-5"
                >
                    @csrf
                    <input type="hidden" id="fleet_id" name="fleet_id" value="{{ $selectedFleet->id ?? '' }}">
                    <input type="hidden" id="vehicle" name="vehicle" value="{{ $selectedFleet->slug ?? '' }}">

                    {{-- =================================================
                        PICKUP / DROP OFF
                    ================================================= --}}
                    <div class="grid gap-4 sm:grid-cols-2">

                        {{-- Pickup --}}
                        <div>
                            <label
                                for="pickup_location"
                                class="mb-2 block text-[6px] font-semibold uppercase tracking-[0.08em] text-white/80"
                            >
                                Pickup Location
                            </label>

                            <div class="relative">

                                <span
                                    class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-[9px] text-[#df357d]"
                                >
                                    ♥
                                </span>

                                <input
                                    id="pickup_location"
                                    name="pickup_location"
                                    type="text"
                                    placeholder="Enter pickup address..."
                                    class="h-[35px] w-full rounded-[7px] border border-white/10 bg-[#1b1b1a] pl-8 pr-3 text-[8px] text-white outline-none placeholder:text-white/25 transition focus:border-[#d9b83c]/50 focus:ring-1 focus:ring-[#d9b83c]/20"
                                >

                            </div>
                        </div>


                        {{-- Dropoff --}}
                        <div>
                            <label
                                for="dropoff_location"
                                class="mb-2 block text-[6px] font-semibold uppercase tracking-[0.08em] text-white/80"
                            >
                                Drop-Off Location
                            </label>

                            <div class="relative">

                                <span
                                    class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-[9px] text-[#df357d]"
                                >
                                    ♥
                                </span>

                                <input
                                    id="dropoff_location"
                                    name="dropoff_location"
                                    type="text"
                                    placeholder="Enter destination..."
                                    class="h-[35px] w-full rounded-[7px] border border-white/10 bg-[#1b1b1a] pl-8 pr-3 text-[8px] text-white outline-none placeholder:text-white/25 transition focus:border-[#d9b83c]/50 focus:ring-1 focus:ring-[#d9b83c]/20"
                                >

                            </div>
                        </div>

                    </div>


                    {{-- =================================================
                        DATE / TIME
                    ================================================= --}}
                    <div class="mt-4 grid gap-4 sm:grid-cols-2">

                        {{-- Pickup date --}}
                        <div>

                            <label
                                for="pickup_date"
                                class="mb-2 block text-[6px] font-semibold uppercase tracking-[0.08em] text-white/80"
                            >
                                Pickup Date
                            </label>

                            <div class="relative">

                                <span
                                    class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-[8px] text-[#4287df]"
                                >
                                    ▣
                                </span>

                                <input
                                    id="pickup_date"
                                    name="pickup_date"
                                    type="date"
                                    class="h-[35px] w-full rounded-[7px] border border-white/10 bg-[#1b1b1a] px-3 pl-8 text-[8px] text-white outline-none transition focus:border-[#d9b83c]/50"
                                >

                            </div>

                        </div>


                        {{-- Pickup time --}}
                        <div>

                            <label
                                for="pickup_time"
                                class="mb-2 block text-[6px] font-semibold uppercase tracking-[0.08em] text-white/80"
                            >
                                Pickup Time
                            </label>

                            <div class="relative">

                                <span
                                    class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-[8px] text-white/60"
                                >
                                    ◷
                                </span>

                                <input
                                    id="pickup_time"
                                    name="pickup_time"
                                    type="time"
                                    class="h-[35px] w-full rounded-[7px] border border-white/10 bg-[#1b1b1a] px-3 pl-8 text-[8px] text-white outline-none transition focus:border-[#d9b83c]/50"
                                >

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                        RETURN TRIP
                    ================================================= --}}
                    <div class="mt-4">

                        <div
                            class="rounded-[7px] border border-white/10 bg-[#1b1b1a] px-3 py-3"
                        >

                            <div class="flex items-center justify-between">

                                <div>
                                    <div
                                        class="text-[6px] font-semibold uppercase tracking-[0.08em] text-white/70"
                                    >
                                        Return Trip
                                    </div>

                                    <div class="mt-1 text-[6px] text-white/25">
                                        Once way journey
                                    </div>
                                </div>


                                <div class="flex items-center gap-2">

                                    <span
                                        class="text-[6px] font-medium text-[#d9b83c]"
                                    >
                                        One Way
                                    </span>

                                    {{-- Toggle --}}
                                    <label class="relative inline-flex cursor-pointer">

                                        <input
                                            type="checkbox"
                                            id="return_trip"
                                            name="return_trip"
                                            value="1"
                                            class="peer sr-only"
                                        >

                                        <span
                                            class="h-[17px] w-[30px] rounded-full bg-white/10 transition peer-checked:bg-[#d9b83c]"
                                        ></span>

                                        <span
                                            class="absolute left-[2px] top-[2px] h-[13px] w-[13px] rounded-full bg-white shadow-sm transition peer-checked:translate-x-[13px]"
                                        ></span>

                                    </label>

                                    <span class="text-[6px] text-white/25">
                                        Return
                                    </span>

                                </div>

                            </div>

                        </div>

                        <div id="returnTripFields" class="mt-4 hidden grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="return_date" class="mb-2 block text-[6px] font-semibold uppercase tracking-[0.08em] text-white/80">
                                    Return Date
                                </label>
                                <input
                                    id="return_date"
                                    name="return_date"
                                    type="date"
                                    class="h-[35px] w-full rounded-[7px] border border-white/10 bg-[#1b1b1a] px-3 text-[8px] text-white outline-none transition focus:border-[#d9b83c]/50"
                                >
                            </div>

                            <div>
                                <label for="return_time" class="mb-2 block text-[6px] font-semibold uppercase tracking-[0.08em] text-white/80">
                                    Return Time
                                </label>
                                <input
                                    id="return_time"
                                    name="return_time"
                                    type="time"
                                    class="h-[35px] w-full rounded-[7px] border border-white/10 bg-[#1b1b1a] px-3 text-[8px] text-white outline-none transition focus:border-[#d9b83c]/50"
                                >
                            </div>
                        </div>

                    </div>


                    {{-- =================================================
                        JOURNEY DETAILS
                    ================================================= --}}
                    <div class="mt-5">

                        <div
                            class="mb-3 text-[5px] uppercase tracking-[0.2em] text-white/30"
                        >
                            Journey Details
                        </div>


                        <div class="grid gap-4 sm:grid-cols-2">

                            {{-- Passengers --}}
                            <div>

                                <label
                                    for="passengers"
                                    class="mb-2 block text-[6px] font-semibold uppercase text-white/80"
                                >
                                    Passengers
                                </label>

                                <div class="relative">

                                    <span
                                        class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-[8px] text-[#8b4bd8]"
                                    >
                                        ♟
                                    </span>

                                    <select
                                        id="passengers"
                                        name="passengers"
                                        class="h-[35px] w-full appearance-none rounded-[7px] border border-white/10 bg-[#1b1b1a] px-3 pl-8 pr-8 text-[8px] text-white outline-none focus:border-[#d9b83c]/50"
                                    >
                                        <option value="">
                                            Select passengers
                                        </option>
                                        <option value="1">1 Passenger</option>
                                        <option value="2">2 Passengers</option>
                                        <option value="3">3 Passengers</option>
                                        <option value="4">4 Passengers</option>
                                    </select>

                                    <span
                                        class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-[7px] text-[#d9b83c]"
                                    >
                                        ▼
                                    </span>

                                </div>

                            </div>


                            {{-- Luggage --}}
                            <div>

                                <label
                                    for="luggage"
                                    class="mb-2 block text-[6px] font-semibold uppercase text-white/80"
                                >
                                    Luggage
                                </label>

                                <div class="relative">

                                    <span
                                        class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-[8px] text-[#4287df]"
                                    >
                                        ▣
                                    </span>

                                    <select
                                        id="luggage"
                                        name="luggage"
                                        class="h-[35px] w-full appearance-none rounded-[7px] border border-white/10 bg-[#1b1b1a] px-3 pl-8 pr-8 text-[8px] text-white outline-none focus:border-[#d9b83c]/50"
                                    >
                                        <option value="">
                                            Select bags
                                        </option>
                                        <option value="1">1 Bag</option>
                                        <option value="2">2 Bags</option>
                                        <option value="3">3 Bags</option>
                                        <option value="4">4 Bags</option>
                                    </select>

                                    <span
                                        class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-[7px] text-[#d9b83c]"
                                    >
                                        ▼
                                    </span>

                                </div>

                            </div>


                            {{-- Vehicle --}}
                            <div>

                                <label
                                    for="vehicle"
                                    class="mb-2 block text-[6px] font-semibold uppercase text-white/80"
                                >
                                    Vehicle Class
                                </label>

                                <div class="relative">

                                    <span
                                        class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-[8px] text-[#a443d8]"
                                    >
                                        ◆
                                    </span>

                                    <select
                                        id="vehicle"
                                        name="vehicle"
                                        class="h-[35px] w-full appearance-none rounded-[7px] border border-white/10 bg-[#1b1b1a] px-3 pl-8 pr-8 text-[8px] text-white outline-none focus:border-[#d9b83c]/50"
                                    >
                                        <option value="">
                                            Select your vehicle
                                        </option>
                                        <option value="mercedes-s-class">
                                            Mercedes S-Class
                                        </option>
                                        <option value="bmw-7-series">
                                            BMW 7 Series
                                        </option>
                                        <option value="rolls-royce-ghost">
                                            Rolls Royce Ghost
                                        </option>
                                    </select>

                                    <span
                                        class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-[7px] text-[#d9b83c]"
                                    >
                                        ▼
                                    </span>

                                </div>

                            </div>


                            {{-- Service type --}}
                            <div>

                                <label
                                    for="service_type"
                                    class="mb-2 block text-[6px] font-semibold uppercase text-white/80"
                                >
                                    Service Type
                                </label>

                                <div class="relative">

                                    <span
                                        class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-[8px] text-white/60"
                                    >
                                        ✦
                                    </span>

                                    <select
                                        id="service_type"
                                        name="service_type"
                                        class="h-[35px] w-full appearance-none rounded-[7px] border border-white/10 bg-[#1b1b1a] px-3 pl-8 pr-8 text-[8px] text-white outline-none focus:border-[#d9b83c]/50"
                                    >
                                        <option value="">
                                            Airport Transfer
                                        </option>
                                        <option value="airport-transfer">
                                            Airport Transfer
                                        </option>
                                        <option value="corporate-travel">
                                            Corporate Travel
                                        </option>
                                        <option value="hourly-hire">
                                            Hourly Hire
                                        </option>
                                        <option value="special-event">
                                            Special Event
                                        </option>
                                        <option value="wedding-transport">
                                            Wedding Transport
                                        </option>
                                        <option value="private-aviation">
                                            Private Aviation
                                        </option>
                                    </select>

                                    <span
                                        class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-[7px] text-[#d9b83c]"
                                    >
                                        ▼
                                    </span>

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                            FLIGHT NUMBER
                        ================================================= --}}
                        <div class="mt-4">

                            <label
                                for="flight_number"
                                class="mb-2 block text-[6px] font-semibold uppercase text-white/80"
                            >
                                Flight Number
                            </label>

                            <div class="relative">

                                <span
                                    class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-[8px] text-white/60"
                                >
                                    ✦
                                </span>

                                <input
                                    id="flight_number"
                                    name="flight_number"
                                    type="text"
                                    placeholder="e.g. EK 417, AA 204..."
                                    class="h-[35px] w-full rounded-[7px] border border-white/10 bg-[#1b1b1a] pl-8 pr-3 text-[8px] text-white outline-none placeholder:text-white/25 focus:border-[#d9b83c]/50"
                                >

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                        SPECIAL REQUESTS
                    ================================================= --}}
                    <div class="mt-5">

                        <div
                            class="mb-3 text-[5px] uppercase tracking-[0.2em] text-white/30"
                        >
                            Special Requests
                        </div>

                        <label
                            for="special_requirements"
                            class="sr-only"
                        >
                            Special Requirements
                        </label>

                        <textarea
                            id="special_requirements"
                            name="special_requirements"
                            rows="3"
                            placeholder="Tell us any special requirements — child seat, meet & greet, preferred route, beverages, or any specific needs..."
                            class="w-full resize-none rounded-[7px] border border-white/10 bg-[#1b1b1a] px-3 py-3 text-[8px] leading-[1.5] text-white outline-none placeholder:text-white/25 focus:border-[#d9b83c]/50"
                        ></textarea>

                    </div>


                    {{-- =================================================
                        SECURITY / BENEFITS
                    ================================================= --}}
                    <div
                        class="mt-4 grid grid-cols-2 gap-2 rounded-[7px] border border-white/10 bg-[#171716] px-3 py-3 sm:grid-cols-4"
                    >

                        <div class="flex items-center gap-1.5">
                            <span class="text-[7px] text-[#d9b83c]">
                                🔒
                            </span>
                            <span class="text-[5px] text-white/50">
                                Secure Booking
                            </span>
                        </div>

                        <div class="flex items-center gap-1.5">
                            <span class="text-[7px] text-[#d9b83c]">
                                ⚡
                            </span>
                            <span class="text-[5px] text-white/50">
                                Instant Confirmation
                            </span>
                        </div>

                        <div class="flex items-center gap-1.5">
                            <span class="text-[7px] text-[#438be2]">
                                ▣
                            </span>
                            <span class="text-[5px] text-white/50">
                                Free Cancellation
                            </span>
                        </div>

                        <div class="flex items-center gap-1.5">
                            <span class="text-[7px] text-[#8d4bda]">
                                ▣
                            </span>
                            <span class="text-[5px] text-white/50">
                                24/7 Support
                            </span>
                        </div>

                    </div>


                    {{-- =================================================
                        FORM ACTIONS
                    ================================================= --}}
                    <div class="mt-4 flex gap-2">

                        {{-- Cancel --}}
                        <button
                            type="reset"
                            class="h-[35px] rounded-[7px] border border-[#d9b83c]/30 bg-transparent px-5 text-[7px] font-semibold text-[#d9b83c] transition hover:bg-[#d9b83c]/10"
                        >
                            Cancel
                        </button>


                        {{-- Reserve --}}
                        <button
                            type="submit"
                            class="group flex h-[35px] flex-1 items-center justify-center gap-2 rounded-[7px] bg-gradient-to-r from-[#f4cf51] to-[#d9a900] text-[8px] font-bold text-[#17130a] shadow-[0_5px_20px_rgba(217,169,0,0.15)] transition duration-300 hover:brightness-105"
                        >
                            <span>
                                ◆
                            </span>

                            <span>
                                Reserve Now
                            </span>

                            <span class="transition-transform duration-300 group-hover:translate-x-1">
                                →
                            </span>
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>
</section>

    {{-- =========================
        FINAL CTA
    ========================== --}}
    <section class="relative overflow-hidden bg-[#050505]">
        <div class="absolute inset-0">
            <img
                src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=2200&q=85"
                alt=""
                class="h-full w-full object-cover"
            >
        </div>
        <div class="absolute inset-0 bg-black/75"></div>

        <div class="relative mx-auto max-w-7xl px-6 py-28 text-center lg:px-8">
            <p class="text-[10px] font-bold uppercase tracking-[0.4em] text-[#d9b33f]">Begin Your Journey</p>
            <h2 class="mx-auto mt-5 max-w-4xl font-serif text-5xl leading-none sm:text-7xl">
                Every Journey Deserves<br>
                <span class="text-[#d9b33f]">To Be Remembered.</span>
            </h2>
            <p class="mx-auto mt-7 max-w-xl text-sm leading-7 text-white/55">
                Tell us where you're going. We'll take care of how you get there.
            </p>
            <a href="#booking" class="mt-9 inline-flex rounded-full bg-[#d9b33f] px-8 py-4 text-[10px] font-bold uppercase tracking-[0.22em] text-black transition hover:bg-white">
                Reserve Your Journey
            </a>
        </div>
    </section>


    {{-- =========================
        FOOTER
    ========================== --}}
    <footer class="bg-[#050505] px-6 py-12 lg:px-8">
        <div class="mx-auto grid max-w-5xl gap-10 border-b border-white/10 pb-10 md:grid-cols-4">
            <div class="md:col-span-2">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-full border border-[#d9b33f]/70 text-sm font-semibold tracking-[0.25em] text-[#d9b33f]">RD</span>
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.35em]">RoyaleDrive</p>
                        <p class="text-[9px] uppercase tracking-[0.45em] text-[#d9b33f]">Chauffeur Services</p>
                    </div>
                </div>
                <p class="mt-5 max-w-sm text-xs leading-6 text-white/40">
                    Private chauffeur travel created around discretion, precision, comfort and exceptional service.
                </p>
            </div>

            <div>
                <p class="text-[9px] font-bold uppercase tracking-[0.25em] text-[#d9b33f]">Explore</p>
                <div class="mt-5 space-y-3 text-xs text-white/50">
                    <a href="#story" class="block hover:text-white">Our Story</a>
                    <a href="#fleet" class="block hover:text-white">Our Fleet</a>
                    <a href="#process" class="block hover:text-white">The Process</a>
                    <a href="#journal" class="block hover:text-white">Journal</a>
                </div>
            </div>

            <div>
                <p class="text-[9px] font-bold uppercase tracking-[0.25em] text-[#d9b33f]">Contact</p>
                <div class="mt-5 space-y-3 text-xs text-white/50">
                    <a href="tel:+440000000000" class="block hover:text-white">+44 00 0000 0000</a>
                    <a href="mailto:hello@royaledrive.com" class="block hover:text-white">hello@royaledrive.com</a>
                    <p>Available 24 / 7</p>
                </div>
            </div>
        </div>

        <div class="mx-auto flex max-w-5xl flex-col justify-between gap-4 pt-7 text-[9px] uppercase tracking-[0.2em] text-white/30 sm:flex-row">
            <p>© {{ date('Y') }} RoyaleDrive. All rights reserved.</p>
            <div class="flex gap-5">
                <a href="#" class="hover:text-white">Privacy</a>
                <a href="#" class="hover:text-white">Terms</a>
            </div>
        </div>
    </footer>
</div>

@push('scripts')
<script>
    const BaseURL = "{{ url('/') }}";
    document.addEventListener('DOMContentLoaded', () => {
        const menuButton = document.getElementById('menuButton');
        const mobileMenu = document.getElementById('mobileMenu');
        const returnTripToggle = document.getElementById('return_trip');
        const returnTripFields = document.getElementById('returnTripFields');
        const serviceModal = document.getElementById('serviceModal');
        const serviceModalImage = document.getElementById('serviceModalImage');
        const serviceModalTitle = document.getElementById('serviceModalTitle');
        const serviceModalDescription = document.getElementById('serviceModalDescription');
        const serviceModalPricing = document.getElementById('serviceModalPricing');
        const fleetButtons = document.querySelectorAll('.fleet-option-btn');
        const featuredFleetName = document.getElementById('featuredFleetName');
        const featuredFleetPrice = document.getElementById('featuredFleetPrice');
        const featuredFleetImage = document.getElementById('featuredFleetImage');
        const featuredFleetPassengers = document.getElementById('featuredFleetPassengers');
        const featuredFleetLuggage = document.getElementById('featuredFleetLuggage');

        const updateFeaturedFleetCard = (data) => {
            const fleetIdInput = document.getElementById('fleet_id');
            const vehicleInput = document.getElementById('vehicle');

            if (fleetIdInput && data.id) {
                fleetIdInput.value = data.id;
            }

            if (vehicleInput && data.slug) {
                vehicleInput.value = data.slug;
            }

            if (featuredFleetName) {
                featuredFleetName.textContent = data.name || 'Luxury Vehicle';
            }

            if (featuredFleetPrice) {
                featuredFleetPrice.textContent = 'OMR ' + Number(data.price || 0).toFixed(0);
            }

            if (featuredFleetImage) {
                featuredFleetImage.src = data.image || '/images/vehicles/mercedes-s-class.jpg';
                featuredFleetImage.alt = data.name || 'Luxury Vehicle';
            }

            if (featuredFleetPassengers) {
                featuredFleetPassengers.innerHTML = '<span class="mr-1 text-[#8b4bd8]">♟</span>' + (data.passengers || 4) + ' Passengers';
            }

            if (featuredFleetLuggage) {
                featuredFleetLuggage.innerHTML = '<span class="mr-1 text-[#368fe7]">▣</span>' + (data.luggage || 3) + ' Bags';
            }

            document.querySelectorAll('.fleet-option-btn').forEach(button => {
                const isSelected = button.dataset.fleetSlug === (data.slug || button.dataset.fleetSlug);
                const spans = button.querySelectorAll('span');

                button.classList.toggle('border', true);
                button.classList.toggle('border-[#d9b83c]/40', isSelected);
                button.classList.toggle('bg-[#d9b83c]/10', isSelected);
                button.classList.toggle('border-white/5', !isSelected);
                button.classList.toggle('bg-white/[0.02]', !isSelected);
                button.classList.toggle('hover:bg-white/[0.05]', !isSelected);

                if (spans[0]) {
                    spans[0].classList.toggle('text-[#d9b83c]', isSelected);
                    spans[0].classList.toggle('text-white/60', !isSelected);
                }

                if (spans[1]) {
                    spans[1].classList.toggle('font-semibold', isSelected);
                    spans[1].classList.toggle('text-[#d9b83c]', isSelected);
                    spans[1].classList.toggle('text-white/30', !isSelected);
                }
            });
        };

        fleetButtons.forEach(button => {
            button.addEventListener('click', () => {
                const slug = button.dataset.fleetSlug;
                if (!slug) return;

                fetch(`${BaseURL}/fleet-details/${slug}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                })
                    .then(response => response.ok ? response.json() : Promise.reject(new Error('Fleet fetch failed')))
                    .then(data => updateFeaturedFleetCard(data))
                    .catch(() => {
                        const fallbackName = button.dataset.fleetName || 'Luxury Vehicle';
                        const fallbackPrice = Number(button.dataset.fleetPrice || 0);

                        updateFeaturedFleetCard({
                            slug,
                            name: fallbackName,
                            price: fallbackPrice,
                            image: '/images/vehicles/mercedes-s-class.jpg',
                            passengers: 4,
                            luggage: 3,
                        });
                    });
            });
        });

        menuButton?.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        document.querySelectorAll('#mobileMenu a').forEach(link => {
            link.addEventListener('click', () => mobileMenu.classList.add('hidden'));
        });

        const syncReturnTripFields = () => {
            if (!returnTripToggle || !returnTripFields) return;
            const isChecked = returnTripToggle.checked;
            returnTripFields.classList.toggle('hidden', !isChecked);

            if (!isChecked) {
                const returnDate = document.getElementById('return_date');
                const returnTime = document.getElementById('return_time');
                if (returnDate) returnDate.value = '';
                if (returnTime) returnTime.value = '';
            }
        };

        returnTripToggle?.addEventListener('change', syncReturnTripFields);
        syncReturnTripFields();

        const closeServiceModal = () => {
            if (!serviceModal) return;
            serviceModal.classList.add('hidden');
        };

        const openServiceModal = (card) => {
            if (!serviceModal || !serviceModalImage || !serviceModalTitle || !serviceModalDescription || !serviceModalPricing) {
                return;
            }

            const title = card.dataset.title || 'Service';
            const description = card.dataset.description || 'Luxury private chauffeur service tailored to your schedule.';
            const image = card.dataset.image || '';
            const pricing = JSON.parse(card.dataset.pricing || '[]');

            serviceModalTitle.textContent = title;
            serviceModalDescription.textContent = description;
            serviceModalImage.src = image;
            serviceModalImage.alt = title;

            if (pricing.length) {
                serviceModalPricing.innerHTML = pricing.map(item => `
                    <div class="flex items-center justify-between rounded-xl border border-white/10 bg-white/5 px-4 py-3">
                        <span class="text-xs uppercase tracking-[0.2em] text-white/60">${item.label || 'Service'}</span>
                        <span class="font-serif text-xl text-[#d9b33f]">${item.currency || 'OMR'} ${Number(item.amount || 0).toFixed(0)}</span>
                    </div>
                `).join('');
            } else {
                serviceModalPricing.innerHTML = '<div class="rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-xs uppercase tracking-[0.2em] text-white/60">Custom itinerary available</div>';
            }

            serviceModal.classList.remove('hidden');
        };

        document.querySelectorAll('[data-service-modal]').forEach(card => {
            const trigger = () => openServiceModal(card);

            card.addEventListener('click', trigger);
            card.addEventListener('keydown', (event) => {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    trigger();
                }
            });
        });

        document.querySelectorAll('[data-close-service-modal]').forEach(button => {
            button.addEventListener('click', closeServiceModal);
        });

        if (serviceModal) {
            serviceModal.addEventListener('click', (event) => {
                if (event.target === serviceModal) {
                    closeServiceModal();
                }
            });
        }

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && serviceModal && !serviceModal.classList.contains('hidden')) {
                closeServiceModal();
            }
        });

        document.querySelectorAll('.faq-button').forEach(button => {
            button.addEventListener('click', () => {
                const answer = button.parentElement.querySelector('.faq-answer');
                const icon = button.querySelector('.faq-icon');
                const expanded = button.getAttribute('aria-expanded') === 'true';

                button.setAttribute('aria-expanded', String(!expanded));
                answer.classList.toggle('hidden', expanded);
                icon.textContent = expanded ? '+' : '−';
            });
        });
    });
</script>
@endpush
@endsection
