@extends('layouts.front-app')

@section('title', 'Booking Confirmed | RoyaleDrive')

@section('content')
<div class="min-h-screen bg-[#050505] px-6 py-16 text-white">
    <div class="mx-auto max-w-4xl">
        <div class="overflow-hidden rounded-[28px] border border-[#d9b33f]/25 bg-[#111110] shadow-[0_25px_80px_rgba(0,0,0,0.45)]">
            <div class="relative overflow-hidden border-b border-white/10 bg-gradient-to-r from-[#d9b33f]/18 via-[#151412] to-[#111110] px-6 py-8 sm:px-10 lg:px-12">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(217,179,63,0.18),transparent_30%)]"></div>
                <div class="relative">
                    <p class="text-[10px] font-bold uppercase tracking-[0.4em] text-[#d9b33f]">Booking Confirmed</p>
                    <h1 class="mt-4 font-serif text-4xl sm:text-5xl lg:text-6xl">Your reservation is in place</h1>
                </div>
            </div>

            <div class="grid gap-8 px-6 py-8 sm:px-10 lg:grid-cols-[1.15fr_0.85fr] lg:items-center lg:px-12 lg:py-10">
                <div class="space-y-7">
                    <div class="rounded-[22px] border border-[#d9b33f]/25 bg-[#1a1a18] p-6 text-center sm:p-7">
                        <div class="mb-4 flex items-center justify-center">
                            <span class="flex h-12 w-12 items-center justify-center rounded-full border border-[#d9b33f]/50 bg-[#d9b33f]/10 text-xl text-[#d9b33f]">✓</span>
                        </div>
                        <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-white/60">Booking Number</p>
                        <div class="mt-4 font-mono text-xl font-bold tracking-[0.18em] text-[#d9b33f] sm:text-2xl lg:text-3xl">
                            {{ $bookingNumber ?? 'RD-0000' }}
                        </div>
                    </div>

                    <div class="space-y-4 text-sm leading-7 text-white/70">
                        <p>Thank you for choosing RoyaleDrive. Our concierge team has received your booking request and will confirm the itinerary shortly.</p>
                        <p>Please keep this reference number when contacting us for any updates or special requests.</p>
                    </div>
                </div>

                <div class="rounded-[22px] border border-white/10 bg-[#161615] p-6 sm:p-7">
                    <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-[#d9b33f]">Next Steps</p>

                    <ul class="mt-5 space-y-4 text-sm text-white/70">
                        <li class="flex gap-3">
                            <span class="mt-1 text-[#d9b33f]">◆</span>
                            <span>Our team will review your booking details.</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="mt-1 text-[#d9b33f]">◆</span>
                            <span>You will receive a confirmation update soon.</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="mt-1 text-[#d9b33f]">◆</span>
                            <span>Keep your booking number handy for quick support.</span>
                        </li>
                    </ul>

                    <div class="mt-7 flex flex-col gap-3">
                        <a href="{{ url('/') }}" class="inline-flex items-center justify-center rounded-full border border-[#d9b33f] bg-[#d9b33f] px-6 py-3 text-[10px] font-bold uppercase tracking-[0.2em] text-black transition hover:bg-transparent hover:text-[#d9b33f]">
                            Back Home
                        </a>
                        <a href="{{ url('/#booking') }}" class="inline-flex items-center justify-center rounded-full border border-white/15 bg-transparent px-6 py-3 text-[10px] font-bold uppercase tracking-[0.2em] text-white transition hover:border-[#d9b33f] hover:text-[#d9b33f]">
                            New Booking
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
