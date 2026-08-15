<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Services</h2>
    </x-slot>

    <div class="p-6 lg:p-8">
        <div class="mx-auto max-w-7xl">
            <div class="mb-6 flex items-center justify-between gap-3">
                <div>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Manage Services</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Handle service pages, galleries, pricing, and SEO settings.</p>
                </div>
                <a href="{{ route('admin.services.create') }}" class="rounded-lg bg-gray-800 px-4 py-2 text-sm font-semibold text-white transition hover:bg-gray-700 dark:bg-gray-100 dark:text-gray-900 dark:hover:bg-gray-200">
                    Add Service
                </a>
            </div>

            @if (session('status'))
                <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-700/40 dark:bg-emerald-900/20 dark:text-emerald-300">
                    {{ session('status') }}
                </div>
            @endif

            <div class="overflow-hidden rounded-xl border p-6 border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="overflow-x-auto">
                    <table id="servicesTable" class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
                        <thead>
                            <tr class="text-left text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                <th class="px-4 py-3">Title</th>
                                <th class="px-4 py-3">URL</th>
                                <th class="px-4 py-3">Pricing</th>
                                <th class="px-4 py-3">Gallery</th>
                                <th class="px-4 py-3">Updated</th>
                                <th class="px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('admin.partials.datatable-assets')
    <script>
        $(function () {
            $('#servicesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.services.index') }}',
                order: [[4, 'desc']],
                columns: [
                    { data: 'title', name: 'title' },
                    { data: 'url', name: 'url' },
                    { data: 'pricing', name: 'pricing', orderable: false },
                    { data: 'gallery', name: 'gallery', orderable: false },
                    { data: 'updated', name: 'updated' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false },
                ],
            });
        });
    </script>
</x-app-layout>
