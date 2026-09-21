@extends('layouts.front-app')

@section('title', 'Contact Us | RoyaleDrive')

@section('content')
<div class="min-h-screen bg-[#050505] text-white">
    @include('partials.site-header')

    @if (session('status'))
        <div class="mx-auto mt-8 max-w-7xl px-6 lg:px-8">
            <div class="rounded-[14px] border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                {{ session('status') }}
            </div>
        </div>
    @endif

    <main class="mx-auto max-w-7xl px-6 py-16 lg:px-8">
        <div class="grid gap-10 lg:grid-cols-[1.2fr_0.8fr]">
            <section class="space-y-8">
                <div class="rounded-[20px] border border-white/10 bg-[#111110] p-6 sm:p-8">
                    <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-[#d9b33f]">Contact Us</p>
                    <h1 class="mt-4 font-serif text-4xl sm:text-5xl lg:text-6xl">Let’s plan your next luxury journey.</h1>
                    <p class="mt-5 max-w-2xl text-sm leading-7 text-white/70">
                        Whether you're planning a private airport transfer, a corporate arrival, or a special occasion drive, our team is ready to help craft a seamless experience around your schedule.
                    </p>
                </div>

                <div class="rounded-[20px] border border-white/10 bg-[#111110] p-6 sm:p-8">
                    <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-[#d9b33f]">Get in Touch</p>

                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        <div class="rounded-[12px] border border-white/10 bg-white/[0.02] p-4">
                            <p class="text-[9px] uppercase tracking-[0.2em] text-white/45">Call us</p>
                            <a href="tel:+440000000000" class="mt-2 block text-base font-medium text-white">+44 00 0000 0000</a>
                        </div>

                        <div class="rounded-[12px] border border-white/10 bg-white/[0.02] p-4">
                            <p class="text-[9px] uppercase tracking-[0.2em] text-white/45">Email</p>
                            <a href="mailto:hello@royaledrive.com" class="mt-2 block text-base font-medium text-white">hello@royaledrive.com</a>
                        </div>

                        <div class="rounded-[12px] border border-white/10 bg-white/[0.02] p-4 sm:col-span-2">
                            <p class="text-[9px] uppercase tracking-[0.2em] text-white/45">Head Office</p>
                            <p class="mt-2 text-base text-white">London, United Kingdom</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-[20px] border border-white/10 bg-[#111110] p-6 sm:p-8">
                    <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-[#d9b33f]">Why Clients Choose Us</p>
                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        <div class="flex items-start gap-3 rounded-[12px] border border-white/10 bg-white/[0.02] p-4">
                            <span class="mt-1 text-[#d9b33f]">✦</span>
                            <span class="text-sm text-white/80">24/7 concierge support</span>
                        </div>
                        <div class="flex items-start gap-3 rounded-[12px] border border-white/10 bg-white/[0.02] p-4">
                            <span class="mt-1 text-[#d9b33f]">✦</span>
                            <span class="text-sm text-white/80">Executive-grade fleet</span>
                        </div>
                        <div class="flex items-start gap-3 rounded-[12px] border border-white/10 bg-white/[0.02] p-4">
                            <span class="mt-1 text-[#d9b33f]">✦</span>
                            <span class="text-sm text-white/80">Discreet, punctual service</span>
                        </div>
                        <div class="flex items-start gap-3 rounded-[12px] border border-white/10 bg-white/[0.02] p-4">
                            <span class="mt-1 text-[#d9b33f]">✦</span>
                            <span class="text-sm text-white/80">Tailored travel planning</span>
                        </div>
                    </div>
                </div>
            </section>

            <aside class="rounded-[20px] border border-[#292929] bg-[#121211] p-5 text-white shadow-[0_20px_60px_rgba(0,0,0,0.15)] sm:p-6 lg:p-7">
                <div class="border-b border-white/10 pb-5">
                    <div class="text-[8px] font-semibold uppercase tracking-[0.25em] text-[#d9b83c]">Send a Message</div>
                    <h2 class="mt-3 font-serif text-3xl text-white">Request a callback</h2>
                </div>

                <form action="{{ route('contact.store') }}" method="POST" class="mt-6 space-y-4">
                    @csrf

                    @if ($errors->any())
                        <div class="rounded-[10px] border border-rose-500/30 bg-rose-500/10 p-3 text-sm text-rose-200">
                            <ul class="space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div>
                        <label for="contact_name" class="mb-2 block text-[12px] font-semibold uppercase tracking-[0.08em] text-white/80">Full Name</label>
                        <input id="contact_name" name="name" type="text" value="{{ old('name') }}" placeholder="Your name" required class="h-[38px] w-full rounded-[7px] border border-white/10 bg-[#1b1b1a] px-3 text-[12px] text-white outline-none placeholder:text-white/25 focus:border-[#d9b83c]/50">
                    </div>

                    <div>
                        <label for="contact_email" class="mb-2 block text-[12px] font-semibold uppercase tracking-[0.08em] text-white/80">Email Address</label>
                        <input id="contact_email" name="email" type="email" value="{{ old('email') }}" placeholder="Your email" required class="h-[38px] w-full rounded-[7px] border border-white/10 bg-[#1b1b1a] px-3 text-[12px] text-white outline-none placeholder:text-white/25 focus:border-[#d9b83c]/50">
                    </div>

                    <div>
                        <label for="contact_phone" class="mb-2 block text-[12px] font-semibold uppercase tracking-[0.08em] text-white/80">Phone</label>
                        <input id="contact_phone" name="phone" type="tel" value="{{ old('phone') }}" placeholder="Your phone number" required  class="h-[38px] w-full rounded-[7px] border border-white/10 bg-[#1b1b1a] px-3 text-[12px] text-white outline-none placeholder:text-white/25 focus:border-[#d9b83c]/50">
                    </div>

                    <div>
                        <label for="contact_subject" class="mb-2 block text-[12px] font-semibold uppercase tracking-[0.08em] text-white/80">Subject</label>
                        <input id="contact_subject" required name="subject" type="text" value="{{ old('subject') }}" placeholder="Journey / booking / enquiry" class="h-[38px] w-full rounded-[7px] border border-white/10 bg-[#1b1b1a] px-3 text-[12px] text-white outline-none placeholder:text-white/25 focus:border-[#d9b83c]/50">
                    </div>

                    <div>
                        <label for="contact_message" class="mb-2 block text-[12px] font-semibold uppercase tracking-[0.08em] text-white/80">Message</label>
                        <textarea id="contact_message" name="message" rows="4" placeholder="Tell us about your trip or requirements..." required class="w-full resize-none rounded-[7px] border border-white/10 bg-[#1b1b1a] px-3 py-3 text-[12px] leading-[1.5] text-white outline-none placeholder:text-white/25 focus:border-[#d9b83c]/50">{{ old('message') }}</textarea>
                    </div>

                    <button id="submit" type="submit" class="mt-2 flex h-[42px] w-full items-center justify-center gap-2 rounded-[7px] bg-gradient-to-r from-[#f4cf51] to-[#d9a900] text-[16px] font-bold uppercase text-[#17130a] shadow-[0_5px_20px_rgba(217,169,0,0.15)]">
                        <span>◆</span>
                        <span>Send Enquiry</span>
                    </button>
                </form>
            </aside>
        </div>
    </main>
</div>
@endsection
