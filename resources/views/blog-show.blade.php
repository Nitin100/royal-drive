@extends('layouts.front-app')

@section('title', $blog->meta_title ?: $blog->title)
@section('description', $blog->meta_description ?: 'RoyaleDrive luxury travel article.')

@section('content')
    <div class="min-h-screen bg-[#050505] text-white">
        @include('partials.site-header')

        <header class="relative border-b border-white/10 bg-[#0b0b0a]">
            <div class="absolute inset-0 z-0">
                <img
                    src="{{ $blog->featured_image_path ? (str_starts_with($blog->featured_image_path, 'http') ? $blog->featured_image_path : asset('storage/' . $blog->featured_image_path)) : 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?auto=format&fit=crop&w=1600&q=80' }}"
                    alt="{{ $blog->title }}"
                    class="h-full w-full object-cover opacity-55"
                >
                <div class="absolute inset-0 bg-gradient-to-r from-black via-black/80 to-black/50"></div>
            </div>

            <div class="relative z-10 mx-auto max-w-6xl px-6 pb-16 pt-10 lg:px-8 lg:pb-20 lg:pt-14">
                <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-[#d9b33f]/40 bg-[#d9b33f]/10 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.28em] text-[#d9b33f]">
                    RoyaleDrive Journal
                </div>

                <h1 class="max-w-4xl font-serif text-4xl leading-tight text-white sm:text-5xl lg:text-6xl">
                    {{ $blog->title }}
                </h1>

                @if ($blog->meta_description)
                    <p class="mt-5 max-w-2xl text-sm leading-7 text-white/70 sm:text-base">
                        {{ $blog->meta_description }}
                    </p>
                @endif

                <div class="mt-8 flex flex-wrap items-center gap-4 text-[10px] font-medium uppercase tracking-[0.25em] text-white/55">
                    <span>Luxury Travel</span>
                    <span>•</span>
                    <span>{{ $blog->created_at?->format('F j, Y') ?? now()->format('F j, Y') }}</span>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-6 py-14 lg:px-8 lg:py-20">
            <div class="grid gap-10 lg:grid-cols-[1.7fr_0.7fr]">
                <article class="rounded-[24px] border border-white/10 bg-[#10100f] p-5 shadow-[0_20px_60px_rgba(0,0,0,0.25)] sm:p-8 lg:p-10">
                    <div class="prose prose-invert prose-lg max-w-none prose-headings:font-serif prose-headings:text-white prose-p:text-white/75 prose-strong:text-[#f4cf51] prose-a:text-[#f4cf51] prose-li:text-white/75">
                        {!! $blog->content !!}
                    </div>
                </article>

                <aside class="space-y-6">
                    <div class="rounded-[24px] border border-[#d9b33f]/20 bg-[#111110] p-6">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.28em] text-[#d9b33f]">Private travel</p>
                        <h3 class="mt-4 font-serif text-2xl text-white">Luxury in motion</h3>
                        <p class="mt-4 text-sm leading-7 text-white/70">
                            Elevated chauffeur journeys designed for business travel, prestige events and quiet, effortless arrivals.
                        </p>
                    </div>

                    <div class="rounded-[24px] border border-white/10 bg-[#111110] p-6">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.28em] text-[#d9b33f]">Reserve now</p>
                        <a href="{{ route('home') }}#booking" class="mt-4 inline-flex items-center justify-center rounded-full bg-[#d9b33f] px-5 py-3 text-[10px] font-bold uppercase tracking-[0.2em] text-black transition hover:bg-white">
                            Book a ride
                        </a>
                    </div>
                </aside>
            </div>
        </main>
    </div>
@endsection
