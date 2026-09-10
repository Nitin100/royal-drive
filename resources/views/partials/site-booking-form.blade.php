<div id="booking" class="mx-auto max-w-6xl px-6 pb-10 lg:px-8">
    <form class="rounded-2xl border border-white/10 bg-white/95 p-2 shadow-2xl backdrop-blur md:flex md:items-end md:gap-2">
        <div class="flex-1 px-4 py-3">
            <label for="from" class="block text-[9px] font-bold uppercase tracking-[0.2em] text-black/45">From</label>
            <input id="from" name="from" type="text" placeholder="Pickup location"
                   class="mt-1 w-full border-0 bg-transparent p-0 text-sm text-black outline-none placeholder:text-black/30">
        </div>
        <div class="hidden h-10 w-px bg-black/10 md:block"></div>
        <div class="flex-1 px-4 py-3">
            <label for="to" class="block text-[9px] font-bold uppercase tracking-[0.2em] text-black/45">To</label>
            <input id="to" name="to" type="text" placeholder="Destination"
                   class="mt-1 w-full border-0 bg-transparent p-0 text-sm text-black outline-none placeholder:text-black/30">
        </div>
        <div class="hidden h-10 w-px bg-black/10 md:block"></div>
        <div class="px-4 py-3">
            <label for="date" class="block text-[9px] font-bold uppercase tracking-[0.2em] text-black/45">Date & Time</label>
            <input id="date" name="date" type="datetime-local"
                   class="mt-1 w-full border-0 bg-transparent p-0 text-sm text-black outline-none">
        </div>
        <div class="hidden h-10 w-px bg-black/10 md:block"></div>
        <div class="px-4 py-3">
            <label for="passengers" class="block text-[9px] font-bold uppercase tracking-[0.2em] text-black/45">Passengers</label>
            <select id="passengers" name="passengers"
                    class="mt-1 w-full border-0 bg-transparent p-0 text-sm text-black outline-none">
                <option>1 Passenger</option>
                <option>2 Passengers</option>
                <option>3 Passengers</option>
                <option>4 Passengers</option>
                <option>5+ Passengers</option>
            </select>
        </div>
        <button type="submit"
                class="rounded-xl bg-[#d9b33f] px-7 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-black transition hover:bg-black hover:text-[#d9b33f]">
            Check Availability
        </button>
    </form>
</div>
