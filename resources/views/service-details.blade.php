@extends('layouts.front-app')

@section('title', ($service->meta_title ?? $service->title) . ' | RoyaleDrive')

@section('content')
@php
    $serviceType = $service->service_type ?? 'Daily';
    // $pricingLabel = $serviceType === 'Long Lease' ? 'Lease' : ($serviceType === 'Monthly' ? 'Month' : ($serviceType === 'Weekly' ? 'Week' : 'Day'));
    $pricingLabel = $serviceType ?: 'Daily';
    $startingPrice = $matchingFleets->min('matched_price') ?? 0;
    $galleryImages = collect();

    if ($service->banner_image_path) {
        $galleryImages->push(
            str_starts_with($service->banner_image_path, 'http')
                ? $service->banner_image_path
                : asset('storage/' . $service->banner_image_path)
        );
    }

    foreach ($service->images ?? [] as $image) {
        $imageUrl = $image->image_path ?? null;
        if (! empty($imageUrl)) {
            $resolved = str_starts_with($imageUrl, 'http') ? $imageUrl : asset('storage/' . $imageUrl);
            if (! $galleryImages->contains($resolved)) {
                $galleryImages->push($resolved);
            }
        }
    }

    if ($galleryImages->isEmpty()) {
        $galleryImages = collect([
            asset('images/services/private-aviation.jpg'),
            'https://images.unsplash.com/photo-1553440569-bcc63803a83d?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=1200&q=85',
        ]);
    }
@endphp

<div class="min-h-screen bg-[#050505] text-white">
    <header class="relative overflow-hidden border-b border-white/10">
        @include('partials.site-header')

        <div class="absolute inset-0 -z-10">
            <img
                src="{{ $service->banner_image_path ? asset('storage/' . $service->banner_image_path) : asset('images/services/private-aviation.jpg') }}"
                alt="{{ $service->title }}"
                class="h-full w-full object-cover opacity-70"
            >
        </div>
        <div class="absolute inset-0 -z-10 bg-gradient-to-r from-black via-black/80 to-black/50"></div>

        <div class="mx-auto max-w-7xl px-6 pb-8 pt-3 lg:px-8">
            <p class="text-[10px] font-bold uppercase tracking-[0.35em] text-[#d9b33f]">Service Detail</p>
            <h1 class="mt-5 font-serif text-3xl sm:text-[42px] lg:text-[56px]">
                {{ $service->title }}
            </h1>
            <div class="mt-4 flex flex-wrap items-center gap-4 text-[10px] uppercase tracking-[0.2em] text-white/60">
                <span>{{ $serviceType }}</span>
                <span>•</span>
                <span>Private Chauffeur Service</span>
                @if ($startingPrice > 0)
                    <span>•</span>
                    <span>From OMR {{ number_format((float) $startingPrice, 2) }} / {{ $pricingLabel }}</span>
                @endif
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-6 py-10 lg:px-8">
          

        <section class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">
            <div class="rounded-[20px] border border-white/10 bg-[#111110] p-6 sm:p-8">
                <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-[#d9b33f]">Overview</p>
                <h2 class="mt-4 font-serif text-3xl text-white sm:text-4xl">{{ $service->title }}</h2>

                <div class="service-gallery-slider relative overflow-hidden rounded-[24px] border border-white/10 bg-black mt-3">
                        <div class="flex transition-transform duration-500 ease-out" data-service-slider-track>
                            @foreach ($galleryImages as $galleryImage)
                                <div class="min-w-full">
                                    <img
                                        src="{{ $galleryImage }}"
                                        alt="{{ $service->title }} gallery image"
                                        class="h-[260px] w-full object-cover sm:h-[320px]"
                                    >
                                </div>
                            @endforeach
                        </div>

                        <div class="absolute inset-x-0 bottom-4 flex items-center justify-center gap-3 px-4">
                            <button type="button" data-service-slider-prev class="flex h-9 w-9 items-center justify-center rounded-full border border-white/15 bg-black/50 text-sm text-white/80 transition hover:border-[#d9b33f] hover:text-[#d9b33f]" aria-label="Previous gallery image">‹</button>
                            <div class="flex items-center gap-2" data-service-slider-dots>
                                @foreach ($galleryImages as $index => $galleryImage)
                                    <button
                                        type="button"
                                        data-service-slider-dot="{{ $index }}"
                                        class="h-2.5 rounded-full bg-white/40 transition-all duration-300 {{ $index === 0 ? 'w-7 bg-[#d9b33f]' : 'w-2.5' }}"
                                        aria-label="Go to gallery image {{ $index + 1 }}"
                                    ></button>
                                @endforeach
                            </div>
                            <button type="button" data-service-slider-next class="flex h-9 w-9 items-center justify-center rounded-full border border-white/15 bg-black/50 text-sm text-white/80 transition hover:border-[#d9b33f] hover:text-[#d9b33f]" aria-label="Next gallery image">›</button>
                        </div>
                    </div>

                <div class="mt-6 space-y-5 text-sm leading-7 text-white/70">
                    @if ($service->description)
                        {!! $service->description !!}
                    @else
                        <p>Tailored private chauffeur service designed for a refined, seamless journey with thoughtful attention to timing, comfort, and discretion.</p>
                    @endif
                </div>
            </div>

            <aside class="rounded-[20px] border border-[#292929] bg-[#121211] p-6 text-white shadow-[0_20px_60px_rgba(0,0,0,0.15)] sm:p-7">
                <div class="text-[12px] font-semibold uppercase tracking-[0.25em] text-[#d9b83c]">Service Type</div>
                <div class="mt-4 font-serif text-4xl text-[#d9b83c]">
                    {{ $serviceType }}
                </div>

                <div class="mt-6 space-y-3 border-t border-white/10 pt-5 text-sm text-white/70">
                    <div class="flex items-center justify-between gap-3">
                        <span>Starting From</span>
                        <span class="font-semibold text-[#f0d869]">
                            @if ($startingPrice > 0)
                                OMR {{ number_format((float) $startingPrice, 2) }}
                            @else
                                Enquire
                            @endif
                        </span>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <span>Unit</span>
                        <span>{{ $pricingLabel }}</span>
                    </div>
                </div>

                <a href="{{ route('home') }}#services" class="mt-6 inline-flex items-center justify-center rounded-[7px] bg-gradient-to-r from-[#f4cf51] to-[#d9a900] px-5 py-3 text-[10px] font-bold uppercase tracking-[0.2em] text-[#17130a] transition hover:brightness-110">
                    Explore More Services
                </a>
            </aside>
        </section>

        <section class="mt-16">
            <div class="mb-8 flex items-end justify-between gap-4">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.35em] text-[#d9b33f]">Fleet Options</p>
                    <h2 class="mt-4 font-serif text-3xl text-white sm:text-5xl">Available Vehicles</h2>
                </div>
            </div>

            @if ($matchingFleets->isNotEmpty())
                <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($matchingFleets as $fleet)
                        @php
                            $fleetImage = $fleet->banner_image_path
                                ? asset('storage/' . $fleet->banner_image_path)
                                : 'https://images.unsplash.com/photo-1553440569-bcc63803a83d?auto=format&fit=crop&w=1200&q=80';
                            $price = (float) ($fleet->matched_price ?? 0);
                        @endphp


                         @php
                        $fleetImage = $fleet->banner_image_path
                            ? asset('storage/' . $fleet->banner_image_path)
                            : 'https://images.unsplash.com/photo-1553440569-bcc63803a83d?auto=format&fit=crop&w=1200&q=80';

                        $price = collect($fleet->pricing_config ?? [])->first();
                        $priceAmount = (float) ($price['price'] ?? $price['amount'] ?? 0);
                    @endphp

                        <a href="{{ route('fleet.details', $fleet->slug) }}" class="group overflow-hidden rounded-[18px] border border-[#1b150d] bg-[#090909] shadow-[0_12px_30px_rgba(0,0,0,0.18)] transition duration-300 hover:-translate-y-1 hover:shadow-[0_18px_40px_rgba(0,0,0,0.24)]">
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

                            <div class="p-5 text-white">
                              

                                <h3 class="mt-4 font-serif text-[24px] leading-[1.1] text-white">
                                    {{ $fleet->name }}
                                </h3>

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
                                <div  class="inline-flex rounded-full bg-[#d9b33f] px-4 py-2 text-[10px] font-bold uppercase tracking-[0.2em] text-black transition hover:bg-white">
                                    View details
                                </div>
                            </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="rounded-[20px] border border-dashed border-white/15 bg-[#111110] p-8 text-center text-white/60">
                    No vehicle is currently assigned to this service plan.
                </div>
            @endif
        </section>
    </main>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const sliderTrack = document.querySelector('[data-service-slider-track]');
    const prevButton = document.querySelector('[data-service-slider-prev]');
    const nextButton = document.querySelector('[data-service-slider-next]');
    const dots = document.querySelectorAll('[data-service-slider-dot]');

    if (!sliderTrack || !prevButton || !nextButton || !dots.length) {
        return;
    }

    let currentIndex = 0;
    const totalSlides = dots.length;

    const updateSlider = (index) => {
        currentIndex = (index + totalSlides) % totalSlides;
        sliderTrack.style.transform = `translateX(-${currentIndex * 100}%)`;

        dots.forEach((dot, dotIndex) => {
            const isActive = dotIndex === currentIndex;
            dot.classList.toggle('w-7', isActive);
            dot.classList.toggle('bg-[#d9b33f]', isActive);
            dot.classList.toggle('w-2.5', !isActive);
            dot.classList.toggle('bg-white/40', !isActive);
        });
    };

    prevButton.addEventListener('click', () => updateSlider(currentIndex - 1));
    nextButton.addEventListener('click', () => updateSlider(currentIndex + 1));

    dots.forEach((dot) => {
        dot.addEventListener('click', () => {
            updateSlider(Number(dot.dataset.serviceSliderDot));
        });
    });

    updateSlider(0);
});
</script>
@endpush
