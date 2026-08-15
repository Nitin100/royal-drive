<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Tour Management</h2>
    </x-slot>

    <div class="p-6 lg:p-8">
        <div class="mx-auto max-w-7xl">
            <div class="mb-6 flex items-center justify-between gap-3">
                <div>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Manage Tours</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Create and maintain tours with categories, tags, featured status, and SEO settings.</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.tours.index') }}" class="rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700 {{ $featuredOnly ? '' : 'bg-gray-100 dark:bg-gray-700' }}">All Tours</a>
                    <a href="{{ route('admin.tours.featured') }}" class="rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700 {{ $featuredOnly ? 'bg-gray-100 dark:bg-gray-700' : '' }}">Featured Posts</a>
                    <a href="{{ route('admin.tours.create') }}" class="rounded-lg bg-gray-800 px-4 py-2 text-sm font-semibold text-white transition hover:bg-gray-700 dark:bg-gray-100 dark:text-gray-900 dark:hover:bg-gray-200">Add Tour</a>
                </div>
            </div>

            @if (session('status'))
                <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-700/40 dark:bg-emerald-900/20 dark:text-emerald-300">
                    {{ session('status') }}
                </div>
            @endif

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="overflow-x-auto">
                    <table id="toursTable" class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
                        <thead>
                            <tr class="text-left text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                <th class="px-4 py-3">Title</th>
                                <th class="px-4 py-3">Categories</th>
                                <th class="px-4 py-3">Tags</th>
                                <th class="px-4 py-3">Featured</th>
                                <th class="px-4 py-3">SEO Title</th>
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
            $('#toursTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ $featuredOnly ? route('admin.tours.featured') : route('admin.tours.index') }}',
                    data: function (d) {
                        d.featured = '{{ $featuredOnly ? 1 : 0 }}';
                    }
                },
                order: [[5, 'desc']],
                columns: [
                    { data: 'title', name: 'title' },
                    { data: 'categories', name: 'categories', orderable: false },
                    { data: 'tags', name: 'tags', orderable: false },
                    { data: 'featured', name: 'featured' },
                    { data: 'meta_title', name: 'meta_title' },
                    { data: 'updated', name: 'updated' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false },
                ],
            });
        });
    </script>
</x-app-layout>