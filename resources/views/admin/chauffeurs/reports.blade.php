<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Performance Reports</h2>
    </x-slot>

    <div class="p-6 lg:p-8">
        <div class="mx-auto max-w-7xl">
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($reportCards as $card)
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $card['name'] }}</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ ucfirst(str_replace('-', ' ', $card['availability_status'])) }}</p>
                            </div>
                            <span class="rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300">{{ number_format($card['rating'], 2) }}</span>
                        </div>

                        <div class="mt-4 space-y-2 text-sm text-gray-600 dark:text-gray-300">
                            <p>Completed Trips: <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $card['completed_trips'] }}</span></p>
                            <p>Top Booking: <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $card['top_assignment'] ?: '-' }}</span></p>
                        </div>
                    </div>
                @empty
                    <div class="rounded-xl border border-dashed border-gray-300 bg-white p-8 text-center text-gray-500 shadow-sm dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400">
                        No performance data yet.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
