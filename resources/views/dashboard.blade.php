<x-app-layout>
    @php
        $totalBookings = \App\Models\Booking::query()->count();
        $pendingBookings = \App\Models\Booking::query()->where('status', 'pending')->count();
        $confirmedBookings = \App\Models\Booking::query()->where('status', 'confirmed')->count();
        $assignedBookings = \App\Models\Booking::query()->where('status', 'assigned')->count();
        $inProgressBookings = \App\Models\Booking::query()->where('status', 'in_progress')->count();
        $completedBookings = \App\Models\Booking::query()->where('status', 'completed')->count();
        $cancelledBookings = \App\Models\Booking::query()->where('status', 'cancelled')->count();
        $activeBookings = $pendingBookings + $confirmedBookings + $assignedBookings + $inProgressBookings;

        $totalCustomers = \App\Models\User::query()->count();
        $verifiedCustomers = \App\Models\User::query()->whereNotNull('email_verified_at')->count();

        $totalEnquiries = \App\Models\Enquiry::query()->count();
        $newEnquiries = \App\Models\Enquiry::query()->where('status', 'new')->count();
        $resolvedEnquiries = \App\Models\Enquiry::query()->whereIn('status', ['resolved', 'closed'])->count();
        $recentEnquiries = \App\Models\Enquiry::query()->where('created_at', '>=', now()->subDays(30))->count();

        $buildBookingSeries = function (\Carbon\Carbon $start, \Carbon\Carbon $end, string $interval, string $keyFormat, string $labelFormat): array {
            $counts = \App\Models\Booking::query()
                ->whereBetween('created_at', [$start, $end])
                ->get(['created_at'])
                ->groupBy(fn ($booking) => $booking->created_at->format($keyFormat))
                ->map(fn ($items) => $items->count())
                ->all();

            $labels = [];
            $values = [];
            $cursor = $start->copy();

            while ($cursor->lte($end)) {
                $key = $cursor->format($keyFormat);
                $labels[] = $cursor->format($labelFormat);
                $values[] = (int) ($counts[$key] ?? 0);

                if ($interval === 'hour') {
                    $cursor->addHour();
                } elseif ($interval === 'day') {
                    $cursor->addDay();
                } else {
                    $cursor->addMonth();
                }
            }

            return [
                'labels' => $labels,
                'values' => $values,
            ];
        };

        $now = now();
        $bookingChartRanges = [
            'today' => $buildBookingSeries($now->copy()->startOfDay(), $now->copy()->endOfDay(), 'hour', 'Y-m-d H', 'H:i'),
            'week' => $buildBookingSeries($now->copy()->subDays(6)->startOfDay(), $now->copy()->endOfDay(), 'day', 'Y-m-d', 'd M'),
            'month' => $buildBookingSeries($now->copy()->subDays(29)->startOfDay(), $now->copy()->endOfDay(), 'day', 'Y-m-d', 'd M'),
            'six_months' => $buildBookingSeries($now->copy()->subMonths(5)->startOfMonth(), $now->copy()->endOfMonth(), 'month', 'Y-m', 'M Y'),
            'year' => $buildBookingSeries($now->copy()->subMonths(11)->startOfMonth(), $now->copy()->endOfMonth(), 'month', 'Y-m', 'M Y'),
        ];
    @endphp

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8 lg:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
                

                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Bookings</p>
                    <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ number_format($totalBookings) }}</p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Completed: {{ number_format($completedBookings) }}</p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Customer Statistics</p>
                    <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ number_format($totalCustomers) }}</p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Verified: {{ number_format($verifiedCustomers) }}</p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Enquiry Statistics</p>
                    <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ number_format($totalEnquiries) }}</p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">New: {{ number_format($newEnquiries) }}</p>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex items-center justify-between gap-3">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Booking Statistics</h3>
                        <select id="bookingRange" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">
                            <option value="today">Today</option>
                            <option value="week" selected>Last 1 Week</option>
                            <option value="month">Last 1 Month</option>
                            <option value="six_months">Last 6 Months</option>
                            <option value="year">Last 1 Year</option>
                        </select>
                    </div>
                    <div class="mt-4 h-72">
                        <canvas id="bookingStatsChart"></canvas>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Enquiry Statistics</h3>
                    <ul class="mt-4 space-y-3 text-sm">
                        <li class="flex items-center justify-between">
                            <span class="text-gray-600 dark:text-gray-300">Total Enquiries</span>
                            <span class="font-semibold text-gray-900 dark:text-gray-100">{{ number_format($totalEnquiries) }}</span>
                        </li>
                        <li class="flex items-center justify-between">
                            <span class="text-gray-600 dark:text-gray-300">New Enquiries</span>
                            <span class="font-semibold text-gray-900 dark:text-gray-100">{{ number_format($newEnquiries) }}</span>
                        </li>
                        <li class="flex items-center justify-between">
                            <span class="text-gray-600 dark:text-gray-300">Resolved / Closed</span>
                            <span class="font-semibold text-gray-900 dark:text-gray-100">{{ number_format($resolvedEnquiries) }}</span>
                        </li>
                        <li class="flex items-center justify-between">
                            <span class="text-gray-600 dark:text-gray-300">Recent Enquiries (30 Days)</span>
                            <span class="font-semibold text-gray-900 dark:text-gray-100">{{ number_format($recentEnquiries) }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Booking Snapshot</h3>
                <div class="mt-4 grid gap-4 sm:grid-cols-3">
                    <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-700/40">
                        <p class="text-xs uppercase text-gray-500 dark:text-gray-400">Assigned</p>
                        <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ number_format($activeBookings) }}</p>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-700/40">
                        <p class="text-xs uppercase text-gray-500 dark:text-gray-400">Completed</p>
                        <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ number_format($completedBookings) }}</p>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-700/40">
                        <p class="text-xs uppercase text-gray-500 dark:text-gray-400">Cancelled</p>
                        <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ number_format($cancelledBookings) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const rangeSelect = document.getElementById('bookingRange');
            const chartCanvas = document.getElementById('bookingStatsChart');

            if (!rangeSelect || !chartCanvas || typeof Chart === 'undefined') {
                return;
            }

            const ranges = @json($bookingChartRanges);
            const textColor = document.documentElement.classList.contains('dark') ? '#e5e7eb' : '#374151';
            const gridColor = document.documentElement.classList.contains('dark') ? 'rgba(107,114,128,0.25)' : 'rgba(209,213,219,0.7)';

            const buildDataset = function (key) {
                const selected = ranges[key] || ranges.month;

                return {
                    labels: selected.labels,
                    datasets: [{
                        label: 'Bookings',
                        data: selected.values,
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.18)',
                        fill: true,
                        tension: 0.35,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                    }],
                };
            };

            const bookingChart = new Chart(chartCanvas.getContext('2d'), {
                type: 'line',
                data: buildDataset(rangeSelect.value),
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: textColor,
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: { color: textColor },
                            grid: { color: gridColor }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: { color: textColor, precision: 0 },
                            grid: { color: gridColor }
                        }
                    }
                }
            });

            rangeSelect.addEventListener('change', function () {
                bookingChart.data = buildDataset(this.value);
                bookingChart.update();
            });
        });
    </script>
</x-app-layout>
