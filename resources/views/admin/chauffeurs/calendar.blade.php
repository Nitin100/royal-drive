<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Availability Calendar</h2>
    </x-slot>

    <div class="p-6 lg:p-8">
        <div class="mx-auto max-w-7xl">
            <div class="mb-6 rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $monthLabel }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Booked chauffeur names are shown on occupied days.</p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($calendarDays as $day)
                    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Day {{ $day['label'] }}</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $day['date']->format('D, M j') }}</p>
                            </div>
                            <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $day['bookedChauffeurs']->isNotEmpty() ? 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-300' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300' }}">
                                {{ $day['bookedChauffeurs']->isNotEmpty() ? 'Booked' : 'Available' }}
                            </span>
                        </div>

                        @if ($day['bookedChauffeurs']->isNotEmpty())
                            <ul class="mt-3 space-y-1 text-sm text-gray-600 dark:text-gray-300">
                                @foreach ($day['bookedChauffeurs'] as $name)
                                    <li>{{ $name }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">No assignments on this date.</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
