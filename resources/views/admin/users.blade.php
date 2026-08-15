<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Users</h2>
    </x-slot>

    <div class="p-6 lg:p-8">
        <div class="mx-auto max-w-7xl">
            @if ($totalUsers === 0)
                <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-8 text-center shadow-sm dark:border-gray-600 dark:bg-gray-800">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">No users yet</h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Invite your first admin user or enable public registration to start populating this panel.</p>

                    <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-gray-700 dark:bg-gray-100 dark:text-gray-900 dark:hover:bg-gray-200">
                                Open Registration
                            </a>
                        @endif
                        <a href="{{ route('profile.edit') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                            Update My Profile
                        </a>
                    </div>
                </div>

                <div class="mt-6 grid gap-6 sm:grid-cols-3">
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Admin Role</p>
                        <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ number_format($adminCount) }}</p>
                    </div>
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Editor Role</p>
                        <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ number_format($editorCount) }}</p>
                    </div>
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Viewer Role</p>
                        <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ number_format($viewerCount) }}</p>
                    </div>
                </div>

                <div class="mt-6 rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Getting Started Checklist</h3>
                    <ul class="mt-4 space-y-3 text-sm text-gray-600 dark:text-gray-300">
                        <li>1. Seed roles and permissions for your application modules.</li>
                        <li>2. Create your first real admin/editor/viewer accounts.</li>
                        <li>3. Revisit this page to track growth and verification rates.</li>
                    </ul>
                </div>
            @else
                <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Users</p>
                        <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ number_format($totalUsers) }}</p>
                    </div>
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Verified Users</p>
                        <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ number_format($verifiedUsers) }}</p>
                    </div>
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <p class="text-sm text-gray-500 dark:text-gray-400">New Signups (30d)</p>
                        <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ number_format($newSignups) }}</p>
                    </div>
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <p class="text-sm text-gray-500 dark:text-gray-400">New This Week</p>
                        <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ number_format($newThisWeek) }}</p>
                    </div>
                </div>

                <div class="mt-6 grid gap-6 md:grid-cols-2">
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Role Highlights</h3>
                        <div class="mt-4 grid grid-cols-3 gap-3">
                            <div class="rounded-lg bg-gray-50 p-3 dark:bg-gray-700/50">
                                <p class="text-xs uppercase text-gray-500 dark:text-gray-400">Admin</p>
                                <p class="mt-1 text-xl font-semibold text-gray-900 dark:text-gray-100">{{ number_format($adminCount) }}</p>
                            </div>
                            <div class="rounded-lg bg-gray-50 p-3 dark:bg-gray-700/50">
                                <p class="text-xs uppercase text-gray-500 dark:text-gray-400">Editor</p>
                                <p class="mt-1 text-xl font-semibold text-gray-900 dark:text-gray-100">{{ number_format($editorCount) }}</p>
                            </div>
                            <div class="rounded-lg bg-gray-50 p-3 dark:bg-gray-700/50">
                                <p class="text-xs uppercase text-gray-500 dark:text-gray-400">Viewer</p>
                                <p class="mt-1 text-xl font-semibold text-gray-900 dark:text-gray-100">{{ number_format($viewerCount) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Role Distribution</h3>
                        <ul class="mt-4 space-y-3 text-sm">
                            @forelse ($roleBreakdown as $role)
                                <li class="flex items-center justify-between">
                                    <span class="text-gray-600 dark:text-gray-300">{{ $role['name'] }}</span>
                                    <span class="font-semibold text-gray-900 dark:text-gray-100">{{ number_format($role['count']) }}</span>
                                </li>
                            @empty
                                <li class="text-gray-500 dark:text-gray-400">No roles configured yet.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <div class="mt-6 rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Recent Users</h3>
                    <div class="mt-4 overflow-x-auto">
                        <table id="recentUsersTable" class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
                            <thead>
                                <tr class="text-left text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                    <th class="px-3 py-2">Name</th>
                                    <th class="px-3 py-2">Email</th>
                                    <th class="px-3 py-2">Roles</th>
                                    <th class="px-3 py-2">Joined</th>
                                    <th class="px-3 py-2">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700"></tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @include('admin.partials.datatable-assets')
    <script>
        $(function () {
            const table = $('#recentUsersTable');

            if (!table.length) {
                return;
            }

            table.DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.users') }}',
                order: [[3, 'desc']],
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'roles', name: 'roles', orderable: false },
                    { data: 'joined', name: 'joined' },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                ],
            });
        });
    </script>
</x-app-layout>
