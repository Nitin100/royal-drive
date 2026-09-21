@extends('layouts.front-app')

@section('title', ($fleet->meta_title ?? $fleet->name) . ' | RoyaleDrive')

@php
    $prefillPickupLocation = trim((string) request('pickup_location', ''));
    $prefillDropoffLocation = trim((string) request('dropoff_location', ''));
    $prefillPickupDate = trim((string) request('pickup_date', ''));
    $prefillDropoffDate = trim((string) request('dropoff_date', ''));
    $prefillPassengers = (int) request('passengers', $fleet->passenger_capacity ?? 4);
    $serviceOptions = \App\Models\Service::query()->orderBy('title')->get();

    $heroSlides = collect();
    $fallbackHero = asset('images/vehicles/mercedes-s-class.jpg');

    if ($fleet->banner_image_path) {
        $heroSlides->push(
            str_starts_with($fleet->banner_image_path, 'http')
                ? $fleet->banner_image_path
                : Storage::disk('public')->url($fleet->banner_image_path)
        );
    }

    foreach ($fleet->images ?? [] as $fleetImage) {
        $imageUrl = null;

        if (! empty($fleetImage->image_path)) {
            $imageUrl = str_starts_with($fleetImage->image_path, 'http')
                ? $fleetImage->image_path
                : Storage::disk('public')->url($fleetImage->image_path);
        }

        if ($imageUrl && ! $heroSlides->contains($imageUrl)) {
            $heroSlides->push($imageUrl);
        }
    }

    if ($heroSlides->isEmpty()) {
        $heroSlides->push($fallbackHero);
    }
@endphp

@section('content')
<style>
    .gold-icon {
        filter: brightness(0) saturate(100%) invert(72%) sepia(33%) saturate(782%) hue-rotate(9deg) brightness(92%) contrast(102%);
    }
</style>
<div class="min-h-screen bg-[#050505] text-white">
    <header class="relative overflow-hidden border-b border-white/10">
     @include('partials.site-header')    
    <div class="absolute inset-0 -z-10">
            <img
                src="{{ $fleet->banner_image_path ? asset('storage/' . $fleet->banner_image_path) : asset('images/vehicles/mercedes-s-class.jpg') }}"
                alt="{{ $fleet->name }}"
                class="h-full w-full object-cover opacity-70"
            >
        </div>
        <div class="absolute inset-0 -z-10 bg-gradient-to-r from-black via-black/80 to-black/50"></div>

        <div class="mx-auto max-w-7xl px-6 pb-5 pt-3 lg:px-8">
            <p class="text-[10px] font-bold uppercase tracking-[0.35em] text-[#d9b33f]">Fleet Detail</p>
            <h1 class="mt-5 font-serif text-2xl sm:text-[22px] lg:text-6x1">
                {{ $fleet->name }}
            </h1>
            <div class="mt-5 flex flex-wrap items-center gap-4 text-[10px] uppercase tracking-[0.2em] text-white/60">
                <span>{{ $fleet->category ?? 'Luxury Class' }}</span>
                <span>•</span>
                <span>{{ $fleet->passenger_capacity ?? 4 }} passengers</span>
                <span>•</span>
                <span>{{ $fleet->luggage_capacity ?? 3 }} luggage bags</span>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-6 py-16 lg:px-8">
        <div class="grid gap-10 lg:grid-cols-2">
            <section class="space-y-8">
                <div class="overflow-hidden rounded-[20px] border border-white/10 bg-[#111110]">
                    <img
                        src="{{ $fleet->banner_image_path ? asset('storage/' . $fleet->banner_image_path) : asset('images/vehicles/mercedes-s-class.jpg') }}"
                        alt="{{ $fleet->name }}"
                        class="h-[420px] w-full object-cover"
                    >
                </div>

@php 
    $vehicleFeatureIcons = [
    '247-support' => [
        'label' => '24/7 Support',
        'icon' => 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/icons/telephone-fill.svg',
    ],
    'air-conditioning' => [
        'label' => 'Air Conditioning',
        'icon' => 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/icons/snow.svg',
    ],
    'airport-pickup' => [
        'label' => 'Airport Pickup',
        'icon' => 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/icons/airplane.svg',
    ],
    'automatic-transmission' => [
        'label' => 'Automatic Transmission',
        'icon' => 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/icons/gear-fill.svg',
    ],
    'bluetooth' => [
        'label' => 'Bluetooth',
        'icon' => 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/icons/bluetooth.svg',
    ],
    'child-seat' => [
        'label' => 'Child Seat',
        'icon' => 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/icons/people-fill.svg',
    ],
    'gps-navigation' => [
        'label' => 'GPS Navigation',
        'icon' => 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/icons/geo-alt-fill.svg',
    ],
    'leather-seats' => [
        'label' => 'Leather Seats',
        'icon' => 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/icons/journal-richtext.svg',
    ],
    'luggage-space' => [
        'label' => 'Luggage Space',
        'icon' => 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/icons/bag-fill.svg',
    ],
    'pet-friendly' => [
        'label' => 'Pet Friendly',
        'icon' => 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/icons/heart-pulse-fill.svg',
    ],
    'premium-audio' => [
        'label' => 'Premium Audio',
        'icon' => 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/icons/speaker-fill.svg',
    ],
    'snow-tire' => [
        'label' => 'Snow Tire',
        'icon' => 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/icons/cloud-snow-fill.svg',
    ],
    'sunroof' => [
        'label' => 'Sunroof',
        'icon' => 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/icons/sun-fill.svg',
    ],
    'usb-charging' => [
        'label' => 'USB Charging',
        'icon' => 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/icons/usb-symbol.svg',
    ],
    'wi-fi' => [
        'label' => 'Wi‑Fi',
        'icon' => 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/icons/wifi.svg',
    ],
];
@endphp
                <div class="rounded-[20px] border border-white/10 bg-[#111110] p-6 sm:p-8">
                    <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-[#d9b33f]">Fleet Features</p>
                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        @foreach ($fleetFeatures as $feature)
                            <div class="flex items-start gap-3 rounded-[12px] border border-white/10 bg-white/[0.02] p-4">
                                <span class="mt-1 text-[#d9b33f]">
                                   @if ($vehicleFeatureIcons[$feature['slug']]['icon'])
                                        <img src="{{ $vehicleFeatureIcons[$feature['slug']]['icon'] ?? '' }}" alt="{{ $vehicleFeatureIcons[$feature['slug']]['label'] ?? '' }}" class="gold-icon h-5 w-5" aria-hidden="true">
                                    @else
                                        ✦
                                    @endif
                                </span>
                                <span class="text-sm text-white/80">{{ $vehicleFeatureIcons[$feature['slug']]['label'] ?? $feature['name'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <aside class="rounded-[20px] border border-[#292929] bg-[#121211] p-5 text-white shadow-[0_20px_60px_rgba(0,0,0,0.15)] sm:p-6 lg:p-7">
                <div class="border-b border-white/10 pb-5">
                    <div class="text-[12px] font-semibold uppercase tracking-[0.25em] text-[#d9b83c]">From</div>
                    <div class="mt-3 font-serif text-4xl text-[#d9b83c]">OMR {{ number_format((float) $fleetPrice, 2) }}</div>
                    <p class="mt-2 text-[10px] text-white/50">Ideal for executive trips, airport arrivals, and premium city transfers.</p>
                </div>

                <form action="{{ route('booking.store') }}" method="POST" class="mt-6">
                    @csrf
                    <input type="hidden" name="fleet_id" value="{{ $fleet->id }}">
                    <input type="hidden" name="vehicle" value="{{ $fleet->slug }}">

                    <div class="space-y-4">
                        <div>
                            <label for="pickup_location" class="mb-2 block text-[12px] font-semibold uppercase tracking-[0.08em] text-white/80">Pickup Location</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-[10px] text-[#d9b83c]">◆</span>
                                <input
                                    id="pickup_location"
                                    name="pickup_location"
                                    type="text"
                                    value="{{ old('pickup_location', $prefillPickupLocation) }}"
                                    placeholder="Enter pickup address..."
                                    autocomplete="off"
                                    class="location-search h-[38px] w-full rounded-[7px] border border-white/10 bg-[#1b1b1a] pl-8 pr-3 text-[12px] text-white outline-none placeholder:text-white/25 transition focus:border-[#d9b83c]/50 focus:ring-1 focus:ring-[#d9b83c]/20"
                                >
                                <div class="location-results absolute left-0 right-0 top-[calc(100%+8px)] z-50 hidden max-h-72 overflow-y-auto rounded-xl border border-[#d9b83c]/20 bg-white shadow-[0_20px_40px_rgba(0,0,0,0.3)]"></div>
                            </div>
                        </div>

                        <div>
                            <label for="dropoff_location" class="mb-2 block text-[12px] font-semibold uppercase tracking-[0.08em] text-white/80">Drop-Off Location</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-[10px] text-[#d9b83c]">◆</span>
                                <input
                                    id="dropoff_location"
                                    name="dropoff_location"
                                    type="text"
                                    value="{{ old('dropoff_location', $prefillDropoffLocation) }}"
                                    placeholder="Enter destination..."
                                    autocomplete="off"
                                    class="location-search h-[38px] w-full rounded-[7px] border border-white/10 bg-[#1b1b1a] pl-8 pr-3 text-[12px] text-white outline-none placeholder:text-white/25 transition focus:border-[#d9b83c]/50 focus:ring-1 focus:ring-[#d9b83c]/20"
                                >
                                <div class="location-results absolute left-0 right-0 top-[calc(100%+8px)] z-50 hidden max-h-72 overflow-y-auto rounded-xl border border-[#d9b83c]/20 bg-white shadow-[0_20px_40px_rgba(0,0,0,0.3)]"></div>
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="pickup_date" class="mb-2 block text-[12px] font-semibold uppercase tracking-[0.08em] text-white/80">Pickup Date</label>
                                <input id="pickup_date" name="pickup_date" type="date" value="{{ old('pickup_date', $prefillPickupDate) }}" class="h-[38px] w-full rounded-[7px] border border-white/10 bg-[#1b1b1a] px-3 text-[12px] text-white outline-none focus:border-[#d9b83c]/50">
                            </div>
                            <div>
                                <label for="pickup_time" class="mb-2 block text-[12px] font-semibold uppercase tracking-[0.08em] text-white/80">Pickup Time</label>
                                <input id="pickup_time" name="pickup_time" type="time" class="h-[38px] w-full rounded-[7px] border border-white/10 bg-[#1b1b1a] px-3 text-[12px] text-white outline-none focus:border-[#d9b83c]/50">
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="dropoff_date" class="mb-2 block text-[12px] font-semibold uppercase tracking-[0.08em] text-white/80">Drop-Off Date</label>
                                <input id="dropoff_date" name="return_date" type="date" value="{{ old('return_date', $prefillDropoffDate) }}" class="h-[38px] w-full rounded-[7px] border border-white/10 bg-[#1b1b1a] px-3 text-[12px] text-white outline-none focus:border-[#d9b83c]/50">
                            </div>
                            <div>
                                <label for="dropoff_time" class="mb-2 block text-[12px] font-semibold uppercase tracking-[0.08em] text-white/80">Drop-Off Time</label>
                                <input id="dropoff_time" name="return_time" type="time" class="h-[38px] w-full rounded-[7px] border border-white/10 bg-[#1b1b1a] px-3 text-[12px] text-white outline-none focus:border-[#d9b83c]/50">
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="passengers" class="mb-2 block text-[12px] font-semibold uppercase tracking-[0.08em] text-white/80">Passengers</label>
                                <select id="passengers" name="passengers" class="h-[38px] w-full rounded-[7px] border border-white/10 bg-[#1b1b1a] px-3 text-[12px] text-white outline-none focus:border-[#d9b83c]/50">
                                    <option value="">Select passengers</option>
                                    <option value="1" {{ old('passengers', $prefillPassengers) == 1 ? 'selected' : '' }}>1 Passenger</option>
                                    <option value="2" {{ old('passengers', $prefillPassengers) == 2 ? 'selected' : '' }}>2 Passengers</option>
                                    <option value="3" {{ old('passengers', $prefillPassengers) == 3 ? 'selected' : '' }}>3 Passengers</option>
                                    <option value="4" {{ old('passengers', $prefillPassengers) == 4 ? 'selected' : '' }}>4 Passengers</option>
                                </select>
                            </div>
                            <div>
                                <label for="luggage" class="mb-2 block text-[12px] font-semibold uppercase tracking-[0.08em] text-white/80">Luggage</label>
                                <select id="luggage" name="luggage" class="h-[38px] w-full rounded-[7px] border border-white/10 bg-[#1b1b1a] px-3 text-[12px] text-white outline-none focus:border-[#d9b83c]/50">
                                    <option value="">Select bags</option>
                                    <option value="1">1 Bag</option>
                                    <option value="2">2 Bags</option>
                                    <option value="3">3 Bags</option>
                                    <option value="4">4 Bags</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="customer_name" class="mb-2 block text-[12px] font-semibold uppercase tracking-[0.08em] text-white/80">Customer Name</label>
                                <input id="customer_name" name="customer_name" type="text" value="{{ old('customer_name') }}" placeholder="Full name" required class="h-[38px] w-full rounded-[7px] border border-white/10 bg-[#1b1b1a] px-3 text-[12px] text-white outline-none placeholder:text-white/25 focus:border-[#d9b83c]/50">
                            </div>
                            <div>
                                <label for="customer_phone" class="mb-2 block text-[12px] font-semibold uppercase tracking-[0.08em] text-white/80">Contact Number</label>
                                <input id="customer_phone" name="customer_phone" type="tel" value="{{ old('customer_phone') }}" placeholder="Phone number" class="h-[38px] w-full rounded-[7px] border border-white/10 bg-[#1b1b1a] px-3 text-[12px] text-white outline-none placeholder:text-white/25 focus:border-[#d9b83c]/50">
                            </div>
                        </div>

                        <div>
                            <label for="customer_email" class="mb-2 block text-[12px] font-semibold uppercase tracking-[0.08em] text-white/80">Email Address</label>
                            <input id="customer_email" name="customer_email" type="email" value="{{ old('customer_email') }}" placeholder="Email address" required class="h-[38px] w-full rounded-[7px] border border-white/10 bg-[#1b1b1a] px-3 text-[12px] text-white outline-none placeholder:text-white/25 focus:border-[#d9b83c]/50">
                        </div>

                        <div>
                            <label for="service_type" class="mb-2 block text-[12px] font-semibold uppercase tracking-[0.08em] text-white/80">Service Type</label>
                            <select id="service_type" name="service_id" class="h-[38px] w-full rounded-[7px] border border-white/10 bg-[#1b1b1a] px-3 text-[12px] text-white outline-none focus:border-[#d9b83c]/50">
                                <option value="">Select service</option>
                                @foreach ($serviceOptions as $service)
                                    <option value="{{ $service->id }}">{{ $service->title }}</option>
                                @endforeach
                            </select>
                        </div>
<div class="mt-4">

                            <label
                                for="flight_number"
                                class="mb-2 block text-[12px] font-semibold uppercase text-white/80"
                            >
                                Flight Number
                            </label>

                            <div class="relative">

                                <span
                                    class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-[12px] text-white/60"
                                >
                                    ✦
                                </span>

                                <input
                                    id="flight_number"
                                    name="flight_number"
                                    type="text"
                                    placeholder="e.g. EK 417, AA 204..."
                                    class="h-[35px] w-full rounded-[7px] border border-white/10 bg-[#1b1b1a] pl-8 pr-3 text-[12px] text-white outline-none placeholder:text-white/25 focus:border-[#d9b83c]/50"
                                >

                            </div>

                        </div>
                        <div>
                            <label for="special_requirements" class="mb-2 block text-[12px] font-semibold uppercase tracking-[0.08em] text-white/80">Special Requests</label>
                            <textarea id="special_requirements" name="special_requirements" rows="3" placeholder="Tell us any special requirements..." required class="w-full resize-none rounded-[7px] border border-white/10 bg-[#1b1b1a] px-3 py-3 text-[12px] leading-[1.5] text-white outline-none placeholder:text-white/25 focus:border-[#d9b83c]/50"></textarea>
                        </div>
                    </div>

                    <button id="submit" type="submit" class="mt-6 flex h-[42px] w-full items-center justify-center gap-2 rounded-[7px] bg-gradient-to-r from-[#f4cf51] to-[#d9a900] text-[18px] font-bold uppercase text-[#17130a] shadow-[0_5px_20px_rgba(217,169,0,0.15)]">
                        <span>◆</span>
                        <span>Reserve Now</span>
                    </button>
                </form>
            </aside>
            
        </div>
         <section class="space-y-8">
               
                <div class="rounded-[20px] border border-white/10 bg-[#111110] p-6 sm:p-8">
                    <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-[#d9b33f]">Vehicle Description</p>
                    <div class="mt-5 text-sm leading-7 text-white/70">
                        <?=$fleet->description ? $fleet->description : '' ?>
                    </div>
                </div>
            </section>
    </main>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const locationSearchUrl = "{{ route('locations.search') }}";
    const searchInputs = document.querySelectorAll('.location-search');

    searchInputs.forEach((input) => {
        const resultsBox = input.parentElement.querySelector('.location-results');
        if (!resultsBox) return;

        let searchTimer = null;

        const hideResults = () => {
            resultsBox.classList.add('hidden');
            resultsBox.innerHTML = '';
        };

        const renderResults = (groups) => {
            if (!groups || Object.keys(groups).length === 0) {
                hideResults();
                return;
            }

            const html = Object.entries(groups).map(([groupName, items]) => {
                if (!items || !items.length) return '';

                return `
                    <div class="border-b border-[#d9b83c]/20 last:border-b-0">
                        <div class="bg-[#f6f2d6] px-3 py-2 text-[9px] font-bold uppercase tracking-[0.18em] text-[#17130a]">
                            ${groupName}
                        </div>
                        <div class="divide-y divide-[#d9b83c]/20 bg-white">
                            ${items.map((item) => `
                                <button
                                    type="button"
                                    class="location-option block w-full border-l border-transparent bg-white px-3 py-2 text-left text-[10px] text-[#17130a] transition duration-200 hover:border-[#d9b83c]/60 hover:bg-[#f7e7a1] hover:text-[#17130a]"
                                    data-value="${item.value || item.name}"
                                >
                                    ${item.name}
                                </button>
                            `).join('')}
                        </div>
                    </div>
                `;
            }).join('');

            resultsBox.innerHTML = html;
            resultsBox.classList.remove('hidden');

            resultsBox.querySelectorAll('.location-option').forEach((button) => {
                button.addEventListener('click', () => {
                    input.value = button.dataset.value;
                    hideResults();
                });
            });
        };

        const fetchResults = () => {
            const query = input.value.trim();

            if (query.length < 2) {
                hideResults();
                return;
            }

            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                fetch(`${locationSearchUrl}?q=${encodeURIComponent(query)}&limit=8`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                })
                    .then((response) => response.ok ? response.json() : Promise.reject(new Error('Search failed')))
                    .then(renderResults)
                    .catch(() => hideResults());
            }, 200);
        };

        input.addEventListener('input', fetchResults);
        input.addEventListener('focus', () => {
            if (input.value.trim().length >= 2) {
                fetchResults();
            }
        });

        document.addEventListener('click', (event) => {
            if (!input.parentElement.contains(event.target)) {
                hideResults();
            }
        });
    });
});
</script>
@endpush
