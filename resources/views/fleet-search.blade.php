@extends('layouts.front-app')

@section('title', 'Available Fleet | RoyaleDrive')

@section('content')
<div class="min-h-screen bg-[#050505] text-white">
    @include('partials.site-header')
 
    <main class="mx-auto max-w-7xl px-6 py-12 lg:px-8">
        <div class="mb-8 rounded-[24px] border border-[#d9b33f]/20 bg-[#0f0f0f] p-5 sm:p-6">
            <p class="text-[10px] font-bold uppercase tracking-[0.35em] text-[#d9b33f]">Your search</p>
            <div class="mt-4 flex flex-wrap gap-3 text-sm text-white/80">
                <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1.5">From: {{ $pickupLocation ?: 'Any location' }}</span>
                <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1.5">To: {{ $dropoffLocation ?: 'Any location' }}</span>
                <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1.5">Passengers: {{ $passengers }}</span>
            </div>
        </div>

        @if ($fleets->isEmpty())
            <div class="rounded-[24px] border border-dashed border-white/15 bg-[#0a0a0a] p-10 text-center">
                <p class="text-[10px] font-bold uppercase tracking-[0.35em] text-[#d9b33f]">No fleet found</p>
                <h1 class="mt-4 font-serif text-4xl text-white">No vehicles match your search.</h1>
                <p class="mt-3 text-white/60">Try increasing the passenger count or searching with a different location.</p>
                <a href="{{ url('/') }}#booking" class="mt-6 inline-flex rounded-full bg-[#d9b33f] px-6 py-3 text-[10px] font-bold uppercase tracking-[0.2em] text-black transition hover:bg-white">
                    Modify Search
                </a>
            </div>
        @else
            <div class="mb-8 flex items-end justify-between gap-3">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.35em] text-[#d9b33f]">Available fleet</p>
                    <h1 class="mt-3 font-serif text-4xl sm:text-5xl">Luxury vehicles matching your trip</h1>
                </div>
                <span class="rounded-full border border-[#d9b33f]/30 bg-[#d9b33f]/10 px-3 py-2 text-[10px] font-bold uppercase tracking-[0.28em] text-[#f4d97c]">
                    {{ $fleets->total() }} results
                </span>
            </div>

            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($fleets as $fleet)
                    @php
                        $fleetImage = $fleet->banner_image_path
                            ? asset('storage/' . $fleet->banner_image_path)
                            : 'https://images.unsplash.com/photo-1553440569-bcc63803a83d?auto=format&fit=crop&w=1200&q=80';

                        $price = collect($fleet->pricing_config ?? [])->first();
                        $priceAmount = (float) ($price['price'] ?? $price['amount'] ?? 0);
                    @endphp

                    <article class="group overflow-hidden rounded-[22px] border border-white/10 bg-[#0d0d0d] shadow-[0_18px_40px_rgba(0,0,0,0.18)] transition duration-300 hover:-translate-y-1 hover:border-[#d9b33f]/40">
                        <div class="relative h-[220px] overflow-hidden bg-black">
                            <img src="{{ $fleetImage }}" alt="{{ $fleet->name }}" class="h-full w-full object-cover transition duration-700 group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
                            <div class="absolute left-4 top-4 rounded-full border border-[#d9b33f]/40 bg-black/40 px-3 py-1.5 text-[10px] font-bold uppercase tracking-[0.2em] text-[#f2cf62]">
                                {{ $fleet->category ?? 'Executive' }}
                            </div>
                            <div class="absolute right-4 top-4 rounded-full bg-[#d9b33f] px-3 py-1.5 text-[11px] font-semibold text-black">
                                OMR {{ number_format($priceAmount, 2) }}
                            </div>
                        </div>

                        <div class="p-5">
                            <h2 class="font-serif text-2xl text-white">{{ $fleet->name }}</h2>

                            <div class="mt-4 flex items-center gap-5 text-[12px] text-[#d9b33f]">
                                <span class="flex items-center gap-2">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M16 19V17C16 15.3431 14.6569 14 13 14H7C5.34315 14 4 15.3431 4 17V19"/><circle cx="10" cy="7" r="3.5"/><path d="M18.5 10.5C19.8807 10.5 21 9.38071 21 8C21 6.61929 19.8807 5.5 18.5 5.5C17.1193 5.5 16 6.61929 16 8C16 9.38071 17.1193 10.5 18.5 10.5Z"/><path d="M18.5 11.5V19"/></svg>
                                    {{ $fleet->passenger_capacity ?? 4 }} passengers
                                </span>
                                <span class="flex items-center gap-2">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M8 8V7C8 5.34315 9.34315 4 11 4H13C14.6569 4 16 5.34315 16 7V8"/><rect x="5" y="8" width="14" height="11" rx="2"/><path d="M9 12H15"/></svg>
                                    {{ $fleet->luggage_capacity ?? 3 }} luggage
                                </span>
                            </div>

                            <p class="mt-4 text-sm leading-7 text-white/65">
                                {{ Str::limit(strip_tags($fleet->description ?? 'Luxury transport tailored to your schedule and style.'), 130) }}
                            </p>

                            <div class="mt-6 flex items-center justify-between gap-3 border-t border-white/10 pt-4">
                                <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-white/50">From {{ $price['label'] ?? 'Hourly' }}</span>
                                <a href="{{ route('fleet.details', $fleet) }}?pickup_location={{ urlencode($pickupLocation) }}&dropoff_location={{ urlencode($dropoffLocation) }}&pickup_date={{ urlencode(request('pickup_date', '')) }}&return_date={{ urlencode(request('return_date', '')) }}&passengers={{ (int) $passengers }}" class="inline-flex rounded-full bg-[#d9b33f] px-4 py-2 text-[10px] font-bold uppercase tracking-[0.2em] text-black transition hover:bg-white">
                                    View details
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            @if ($fleets->hasPages())
                <div class="custom-pagination mt-10 flex justify-center">
                    <nav aria-label="Pagination Navigation" class="flex items-center gap-2 sm:gap-3">
                        @if ($fleets->onFirstPage())
                            <span class="page-nav disabled" aria-disabled="true" aria-label="Previous page">«</span>
                        @else
                            <a href="{{ $fleets->previousPageUrl() }}" class="page-nav" rel="prev" aria-label="Previous page">«</a>
                        @endif

                        @php
                            $currentPage = $fleets->currentPage();
                            $lastPage = $fleets->lastPage();
                            $startPage = max(1, $currentPage - 1);
                            $endPage = min($lastPage, $currentPage + 1);
                            $range = range($startPage, $endPage);
                        @endphp

                        @foreach ($range as $page)
                            @if ($page == $currentPage)
                                <span aria-current="page" class="page-number active" aria-label="Current page, page {{ $page }}">{{ $page }}</span>
                            @else
                                <a href="{{ $fleets->url($page) }}" class="page-number" aria-label="Go to page {{ $page }}">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if ($fleets->hasMorePages())
                            <a href="{{ $fleets->nextPageUrl() }}" class="page-nav" rel="next" aria-label="Next page">»</a>
                        @else
                            <span class="page-nav disabled" aria-disabled="true" aria-label="Next page">»</span>
                        @endif
                    </nav>
                </div>
            @endif
        @endif
    </main>
</div>

<style>
    .custom-pagination .page-nav,
    .custom-pagination .page-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 9999px;
        border: 1px solid rgba(217, 179, 63, 0.8);
        background: #d9b33f;
        color: #111111;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-decoration: none;
        transition: all 0.2s ease;
        box-shadow: 0 8px 20px rgba(217, 179, 63, 0.18);
    }

    .custom-pagination .page-nav {
        font-size: 1.1rem;
        line-height: 1;
        padding: 0;
    }

    .custom-pagination .page-number {
        padding: 0;
    }

    .custom-pagination .page-nav:hover,
    .custom-pagination .page-number:hover {
        background: #f4d97c;
        border-color: #f4d97c;
        color: #111111;
        transform: translateY(-1px);
    }

    .custom-pagination .page-number.active {
        background: #f4d97c;
        border-color: #f4d97c;
        color: #111111;
        box-shadow: 0 0 0 2px rgba(217, 179, 63, 0.2), 0 8px 20px rgba(244, 217, 124, 0.2);
    }

    .custom-pagination .page-nav.disabled {
        opacity: 0.45;
        cursor: not-allowed;
        background: rgba(17, 17, 17, 0.9);
        border-color: rgba(217, 179, 63, 0.3);
        color: rgba(255, 255, 255, 0.4);
        box-shadow: none;
    }
</style>
@endsection
