@extends('layouts.front-app')

@section('title', ($fleet->meta_title ?? $fleet->name) . ' | RoyaleDrive')

@section('content')
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

                <div class="rounded-[20px] border border-white/10 bg-[#111110] p-6 sm:p-8">
                    <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-[#d9b33f]">Fleet Features</p>
                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        @foreach ($fleetFeatures as $feature)
                            <div class="flex items-start gap-3 rounded-[12px] border border-white/10 bg-white/[0.02] p-4">
                                <span class="mt-1 text-[#d9b33f]">✦</span>
                                <span class="text-sm text-white/80">{{ $feature }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <aside class="rounded-[20px] border border-[#292929] bg-[#121211] p-5 text-white shadow-[0_20px_60px_rgba(0,0,0,0.15)] sm:p-6 lg:p-7">
                <div class="border-b border-white/10 pb-5">
                    <div class="text-[8px] font-semibold uppercase tracking-[0.25em] text-[#d9b83c]">From</div>
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
                            <input id="pickup_location" name="pickup_location" type="text" placeholder="Enter pickup address..." class="h-[38px] w-full rounded-[7px] border border-white/10 bg-[#1b1b1a] px-3 text-[8px] text-white outline-none placeholder:text-white/25 focus:border-[#d9b83c]/50">
                        </div>

                        <div>
                            <label for="dropoff_location" class="mb-2 block text-[12px] font-semibold uppercase tracking-[0.08em] text-white/80">Drop-Off Location</label>
                            <input id="dropoff_location" name="dropoff_location" type="text" placeholder="Enter destination..." class="h-[38px] w-full rounded-[7px] border border-white/10 bg-[#1b1b1a] px-3 text-[8px] text-white outline-none placeholder:text-white/25 focus:border-[#d9b83c]/50">
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="pickup_date" class="mb-2 block text-[12px] font-semibold uppercase tracking-[0.08em] text-white/80">Pickup Date</label>
                                <input id="pickup_date" name="pickup_date" type="date" class="h-[38px] w-full rounded-[7px] border border-white/10 bg-[#1b1b1a] px-3 text-[8px] text-white outline-none focus:border-[#d9b83c]/50">
                            </div>
                            <div>
                                <label for="pickup_time" class="mb-2 block text-[12px] font-semibold uppercase tracking-[0.08em] text-white/80">Pickup Time</label>
                                <input id="pickup_time" name="pickup_time" type="time" class="h-[38px] w-full rounded-[7px] border border-white/10 bg-[#1b1b1a] px-3 text-[8px] text-white outline-none focus:border-[#d9b83c]/50">
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="passengers" class="mb-2 block text-[12px] font-semibold uppercase tracking-[0.08em] text-white/80">Passengers</label>
                                <select id="passengers" name="passengers" class="h-[38px] w-full rounded-[7px] border border-white/10 bg-[#1b1b1a] px-3 text-[8px] text-white outline-none focus:border-[#d9b83c]/50">
                                    <option value="">Select passengers</option>
                                    <option value="1">1 Passenger</option>
                                    <option value="2">2 Passengers</option>
                                    <option value="3">3 Passengers</option>
                                    <option value="4">4 Passengers</option>
                                </select>
                            </div>
                            <div>
                                <label for="luggage" class="mb-2 block text-[12px] font-semibold uppercase tracking-[0.08em] text-white/80">Luggage</label>
                                <select id="luggage" name="luggage" class="h-[38px] w-full rounded-[7px] border border-white/10 bg-[#1b1b1a] px-3 text-[8px] text-white outline-none focus:border-[#d9b83c]/50">
                                    <option value="">Select bags</option>
                                    <option value="1">1 Bag</option>
                                    <option value="2">2 Bags</option>
                                    <option value="3">3 Bags</option>
                                    <option value="4">4 Bags</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="service_type" class="mb-2 block text-[12px] font-semibold uppercase tracking-[0.08em] text-white/80">Service Type</label>
                            <select id="service_type" name="service_type" class="h-[38px] w-full rounded-[7px] border border-white/10 bg-[#1b1b1a] px-3 text-[8px] text-white outline-none focus:border-[#d9b83c]/50">
                                <option value="airport-transfer">Airport Transfer</option>
                                <option value="corporate-travel">Corporate Travel</option>
                                <option value="hourly-hire">Hourly Hire</option>
                            </select>
                        </div>

                        <div>
                            <label for="special_requirements" class="mb-2 block text-[12px] font-semibold uppercase tracking-[0.08em] text-white/80">Special Requests</label>
                            <textarea id="special_requirements" name="special_requirements" rows="3" placeholder="Tell us any special requirements..." class="w-full resize-none rounded-[7px] border border-white/10 bg-[#1b1b1a] px-3 py-3 text-[8px] leading-[1.5] text-white outline-none placeholder:text-white/25 focus:border-[#d9b83c]/50"></textarea>
                        </div>
                    </div>

                    <button type="submit" class="mt-6 flex h-[42px] w-full items-center justify-center gap-2 rounded-[7px] bg-gradient-to-r from-[#f4cf51] to-[#d9a900] text-[18px] font-bold uppercase text-[#17130a] shadow-[0_5px_20px_rgba(217,169,0,0.15)]">
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
