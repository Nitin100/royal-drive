<div id="booking" x-data="dateRangePicker()" class="relative z-30 mx-auto max-w-6xl overflow-visible px-6 pb-10 lg:px-8">
    <form method="GET" action="{{ route('fleet.search') }}" class="rounded-2xl border border-white/10 bg-white/95 p-2 shadow-2xl backdrop-blur md:flex md:items-end md:gap-2">
        <div class="relative flex-1 px-4 py-3">
            <label for="from" class="block text-[9px] font-bold uppercase tracking-[0.2em] text-black/45">From</label>
            <input id="from" name="pickup_location" type="text" placeholder="Pickup location" autocomplete="off"
                   class="location-search mt-1 w-full border-0 bg-transparent p-0 text-sm text-black outline-none placeholder:text-black/30 focus:border-0 focus:ring-0">
            <div class="location-results absolute left-0 right-0 top-[calc(100%+8px)] z-50 hidden max-h-72 overflow-y-auto rounded-xl border border-black/10 bg-white shadow-xl"></div>
        </div>
        <div class="hidden h-10 w-px bg-black/10 md:block"></div>
        <div class="relative flex-1 px-4 py-3">
            <label for="to" class="block text-[9px] font-bold uppercase tracking-[0.2em] text-black/45">To</label>
            <input id="to" name="dropoff_location" type="text" placeholder="Destination" autocomplete="off"
                   class="location-search mt-1 w-full border-0 bg-transparent p-0 text-sm text-black outline-none placeholder:text-black/30 focus:border-0 focus:ring-0">
            <div class="location-results absolute left-0 right-0 top-[calc(100%+8px)] z-50 hidden max-h-72 overflow-y-auto rounded-xl border border-black/10 bg-white shadow-xl"></div>
        </div>
        <div class="hidden h-10 w-px bg-black/10 md:block"></div>
        <div class="relative px-4 py-3">
            <label class="block text-[9px] font-bold uppercase tracking-[0.2em] text-black/45">Pickup / Drop-off Date</label>

            <div class="relative mt-2">
                <div @click="showPicker = !showPicker" class="flex cursor-pointer items-center gap-3 rounded-xl border border-black/10 bg-[#f7f3e7] px-3 py-2.5 shadow-sm focus-within:border-0">
                    <svg class="h-4 w-4 text-black/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <rect x="3.5" y="5" width="17" height="15.5" rx="2"/>
                        <path d="M8 3.5v3M16 3.5v3M3.5 9.5h17"/>
                    </svg>
                    <input id="date_range" type="text" readonly x-model="displayValue"
                           placeholder="Select date range"
                           class="w-full border-0 bg-transparent p-0 text-sm text-black outline-none placeholder:text-black/35 focus:border-0 focus:ring-0">
                </div>

                <input type="hidden" name="pickup_date" x-model="pickupDate">
                <input type="hidden" name="dropoff_date" x-model="dropoffDate">

                <div x-show="showPicker" @click.outside="showPicker = false" x-transition class="absolute left-0 top-[calc(100%+10px)] z-[60] w-[300px] overflow-hidden rounded-2xl border border-black/10 bg-white shadow-2xl">
                    <div class="flex items-center justify-between border-b border-black/5 bg-[#f7f3e7] px-3 py-2">
                        <button type="button" @click.stop="changeMonth(-1)" class="flex h-7 w-7 items-center justify-center rounded-full text-base text-black/70 hover:bg-black/5">&#8249;</button>
                        <div class="text-[10px] font-bold uppercase tracking-[0.2em] text-black/60" x-text="monthLabel"></div>
                        <button type="button" @click.stop="changeMonth(1)" class="flex h-7 w-7 items-center justify-center rounded-full text-base text-black/70 hover:bg-black/5">&#8250;</button>
                    </div>

                    <div class="grid grid-cols-7 gap-1 p-3 text-center text-[10px] font-semibold uppercase tracking-[0.08em] text-black/50">
                        <template x-for="day in ['Su','Mo','Tu','We','Th','Fr','Sa']" :key="day">
                            <div x-text="day"></div>
                        </template>
                    </div>

                    <div class="grid grid-cols-7 gap-1 px-3 pb-3 text-center text-sm">
                        <template x-for="date in calendarDays" :key="date.key">
                            <button
                                type="button"
                                @click.stop="pickDate(date.value)"
                                :class="{
                                    'text-black/35': !date.currentMonth,
                                    'bg-[#d9b33f] text-black font-bold': isSelected(date.value),
                                    'bg-[#f7f3e7] text-black font-medium': isInRange(date.value) && !isSelected(date.value),
                                    'text-black': date.currentMonth && !isSelected(date.value) && !isInRange(date.value),
                                    'rounded-full border border-black/10': true,
                                    'h-8 w-8': true,
                                    'mx-auto': true
                                }"
                                x-text="date.label"
                            ></button>
                        </template>
                    </div>
                </div>
            </div>
        </div>
        <div class="hidden h-10 w-px bg-black/10 md:block"></div>
        <div class="px-4 py-3">
            <label for="passengers" class="block text-[9px] font-bold uppercase tracking-[0.2em] text-black/45">Passengers</label>
            <select id="passengers" name="passengers"
                    class="mt-1 w-full border-0 bg-transparent p-0 text-sm text-black outline-none focus:border-0 focus:ring-0">
                <option value="1">1 Passenger</option>
                <option value="2">2 Passengers</option>
                <option value="3">3 Passengers</option>
                <option value="4">4 Passengers</option>
                <option value="5">5 Passengers</option>
                <option value="6">6 Passengers</option>
                <option value="7">7 Passengers</option>
                <option value="8">8+ Passengers</option>
            </select>
        </div>
        <button type="submit"
                class="rounded-xl bg-[#d9b33f] px-7 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-black transition hover:bg-black hover:text-[#d9b33f]">
            Check Availability
        </button>
    </form>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('dateRangePicker', () => ({
            showPicker: false,
            pickupDate: '',
            dropoffDate: '',
            monthCursor: new Date(new Date().getFullYear(), new Date().getMonth(), 1),
            get displayValue() {
                if (!this.pickupDate && !this.dropoffDate) return '';
                if (this.pickupDate && !this.dropoffDate) return this.formatDate(this.pickupDate);
                return this.formatDate(this.pickupDate) + ' - ' + this.formatDate(this.dropoffDate);
            },
            get monthLabel() {
                return new Intl.DateTimeFormat('en-US', { month: 'long', year: 'numeric' }).format(this.monthCursor);
            },
            get calendarDays() {
                const year = this.monthCursor.getFullYear();
                const month = this.monthCursor.getMonth();
                const firstDay = new Date(year, month, 1);
                const lastDay = new Date(year, month + 1, 0);
                const startingDay = firstDay.getDay();
                const totalDays = lastDay.getDate();
                const prevMonthLast = new Date(year, month, 0).getDate();
                const days = [];

                for (let i = startingDay - 1; i >= 0; i--) {
                    const day = prevMonthLast - i;
                    days.push({
                        key: 'prev-' + day,
                        label: day,
                        value: this.formatISO(new Date(year, month - 1, day)),
                        currentMonth: false,
                    });
                }

                for (let day = 1; day <= totalDays; day++) {
                    days.push({
                        key: 'current-' + day,
                        label: day,
                        value: this.formatISO(new Date(year, month, day)),
                        currentMonth: true,
                    });
                }

                while (days.length % 7 !== 0) {
                    const nextDay = days.length % 7 === 0 ? 1 : days.length - Math.floor(days.length / 7) * 7 + 1;
                    days.push({
                        key: 'next-' + nextDay,
                        label: nextDay,
                        value: this.formatISO(new Date(year, month + 1, nextDay)),
                        currentMonth: false,
                    });
                }

                return days;
            },
            pickDate(dateValue) {
                const selected = new Date(dateValue + 'T00:00:00');

                if (!this.pickupDate || (this.pickupDate && this.dropoffDate)) {
                    this.pickupDate = this.formatISO(selected);
                    this.dropoffDate = '';
                    this.showPicker = true;
                    return;
                }

                if (this.pickupDate && !this.dropoffDate) {
                    if (selected < new Date(this.pickupDate + 'T00:00:00')) {
                        this.dropoffDate = this.pickupDate;
                        this.pickupDate = this.formatISO(selected);
                    } else {
                        this.dropoffDate = this.formatISO(selected);
                    }
                    this.showPicker = false;
                }
            },
            changeMonth(step) {
                this.monthCursor = new Date(this.monthCursor.getFullYear(), this.monthCursor.getMonth() + step, 1);
            },
            isSelected(dateValue) {
                if (!dateValue) return false;
                return dateValue === this.pickupDate || dateValue === this.dropoffDate;
            },
            isInRange(dateValue) {
                if (!dateValue || !this.pickupDate || !this.dropoffDate) return false;
                const current = new Date(dateValue + 'T00:00:00');
                const start = new Date(this.pickupDate + 'T00:00:00');
                const end = new Date(this.dropoffDate + 'T00:00:00');
                return current > start && current < end;
            },
            formatISO(date) {
                const y = date.getFullYear();
                const m = String(date.getMonth() + 1).padStart(2, '0');
                const d = String(date.getDate()).padStart(2, '0');
                return `${y}-${m}-${d}`;
            },
            formatDate(dateString) {
                if (!dateString) return '';
                return new Intl.DateTimeFormat('en-US', { month: 'short', day: 'numeric', year: 'numeric' }).format(new Date(dateString + 'T00:00:00'));
            }
        }));
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
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
                if (!items || items.length === 0) return '';

                return `
                    <div class="border-b border-black/5 last:border-b-0">
                        <div class="bg-[#f7f3e7] px-3 py-2 text-[9px] font-bold uppercase tracking-[0.2em] text-black/50">
                            ${groupName}
                        </div>
                        ${items.map((item) => `
                            <button
                                type="button"
                                class="location-option block w-full px-3 py-2 text-left text-sm text-black transition hover:bg-[#f6f2d6]"
                                data-value="${item.value}"
                            >
                                ${item.name}
                            </button>
                        `).join('')}
                    </div>
                `;
            }).join('');

            resultsBox.innerHTML = html;
            resultsBox.classList.remove('hidden');

            resultsBox.querySelectorAll('.location-option').forEach((option) => {
                option.addEventListener('click', function () {
                    input.value = this.dataset.value;
                    hideResults();
                });
            });
        };

        input.addEventListener('input', function () {
            const query = this.value.trim();
            clearTimeout(searchTimer);

            if (query.length < 1) {
                hideResults();
                return;
            }

            searchTimer = setTimeout(() => {
                fetch(`{{ route('locations.search') }}?q=${encodeURIComponent(query)}`)
                    .then((response) => response.json())
                    .then((data) => renderResults(data))
                    .catch(() => hideResults());
            }, 180);
        });

        input.addEventListener('focus', function () {
            if (this.value.trim().length > 1) {
                this.dispatchEvent(new Event('input'));
            }
        });

        document.addEventListener('click', function (event) {
            if (!input.parentElement.contains(event.target)) {
                hideResults();
            }
        });
    });
});
</script>

