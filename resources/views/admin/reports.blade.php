<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Reports</h2>
    </x-slot>

    <div class="p-6 lg:p-8">
        <div class="mx-auto max-w-7xl">
            <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Analytics Window</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Use filters to inspect signup activity over different time ranges.</p>
                </div>
                <div class="inline-flex rounded-lg border border-gray-200 bg-white p-1 dark:border-gray-700 dark:bg-gray-800">
                    @foreach ($rangeOptions as $key => $option)
                        <a href="{{ route('admin.reports', ['range' => $key]) }}"
                           class="rounded-md px-3 py-1.5 text-xs font-semibold transition {{ $activeRange === $key ? 'bg-gray-900 text-white dark:bg-gray-100 dark:text-gray-900' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            {{ $option['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            @if ($totalUsers === 0)
                <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-8 text-center shadow-sm dark:border-gray-600 dark:bg-gray-800">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">No analytics yet</h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Create users first, then this report page will automatically show signup trends and verification breakdowns.</p>

                    <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-gray-700 dark:bg-gray-100 dark:text-gray-900 dark:hover:bg-gray-200">
                                Add First User
                            </a>
                        @endif
                        <a href="{{ route('admin.users') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                            Go to Users Panel
                        </a>
                    </div>
                </div>
            @else
            <div class="grid gap-6 md:grid-cols-2">
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Monthly User Signups</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ $isDaily ? 'Daily' : 'Weekly' }} signup totals for the selected range.
                    </p>
                    <div class="mt-5 grid items-end gap-2" style="grid-template-columns: repeat({{ $chartData->count() }}, minmax(0, 1fr));">
                        @foreach ($chartData as $bar)
                            @php
                                $height = 16 + (int) round(($bar['count'] / $maxSignups) * 96);
                            @endphp
                            <div class="space-y-2 text-center">
                                <div class="mx-auto w-full max-w-8 rounded bg-indigo-200 dark:bg-indigo-500/40" style="height: {{ $height }}px;"></div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $bar['label'] }}</p>
                                <p class="text-xs font-medium text-gray-700 dark:text-gray-200">{{ $bar['count'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Account Verification</h3>
                    <ul class="mt-4 space-y-3 text-sm">
                        <li class="flex items-center justify-between">
                            <span class="text-gray-600 dark:text-gray-300">Verified Users</span>
                            <span class="font-semibold text-gray-900 dark:text-gray-100">{{ number_format($verifiedUsers) }} ({{ $verifiedPercent }}%)</span>
                        </li>
                        <li class="flex items-center justify-between">
                            <span class="text-gray-600 dark:text-gray-300">Unverified Users</span>
                            <span class="font-semibold text-gray-900 dark:text-gray-100">{{ number_format($unverifiedUsers) }} ({{ $unverifiedPercent }}%)</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="mt-6 rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Latest Signups</h3>
                <ul class="mt-4 divide-y divide-gray-100 text-sm dark:divide-gray-700">
                    @forelse ($latestUsers as $user)
                        <li class="flex items-center justify-between py-3">
                            <span>
                                <span class="block text-gray-700 dark:text-gray-200">{{ $user['name'] }}</span>
                                <span class="block text-xs text-gray-500 dark:text-gray-400">{{ $user['email'] }}</span>
                            </span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $user['joined'] }}</span>
                        </li>
                    @empty
                        <li class="py-3 text-gray-500 dark:text-gray-400">No signup data available yet.</li>
                    @endforelse
                </ul>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
