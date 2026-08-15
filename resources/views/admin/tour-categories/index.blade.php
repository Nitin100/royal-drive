<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Tour Categories</h2>
    </x-slot>

    <div class="p-6 lg:p-8">
        <div class="mx-auto max-w-7xl grid gap-6 lg:grid-cols-3">
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800 lg:col-span-1">
                <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $categoryToEdit ? 'Edit Category' : 'Add Category' }}</h3>
                <form class="mt-4 space-y-4" method="POST" action="{{ $categoryToEdit ? route('admin.tour-categories.update', $categoryToEdit) : route('admin.tour-categories.store') }}">
                    @csrf
                    @if ($categoryToEdit)
                        @method('PUT')
                    @endif

                    <div>
                        <label for="name" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Name</label>
                        <input id="name" name="name" type="text" required value="{{ old('name', $categoryToEdit->name ?? '') }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
                    </div>

                    <div>
                        <label for="slug" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Slug</label>
                        <input id="slug" name="slug" type="text" value="{{ old('slug', $categoryToEdit->slug ?? '') }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="submit" class="rounded-lg bg-gray-800 mt-3 px-4 py-2 text-sm font-semibold text-white transition hover:bg-gray-700 dark:bg-gray-100 dark:text-gray-900 dark:hover:bg-gray-200">{{ $categoryToEdit ? 'Update' : 'Create' }}</button>
                        @if ($categoryToEdit)
                            <a href="{{ route('admin.tour-categories.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">Cancel</a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="rounded-xl border p-6 border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800 lg:col-span-2">
                @if (session('status'))
                    <div class="border-b border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-700/40 dark:bg-emerald-900/20 dark:text-emerald-300">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table id="tourCategoriesTable" class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
                        <thead>
                            <tr class="text-left text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                <th class="px-4 py-3">Name</th>
                                <th class="px-4 py-3">Slug</th>
                                <th class="px-4 py-3">Tours</th>
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
            $('#tourCategoriesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.tour-categories.index') }}',
                order: [[0, 'asc']],
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'slug', name: 'slug' },
                    { data: 'tours', name: 'tours', orderable: false, searchable: false },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false },
                ],
            });
        });
    </script>
</x-app-layout>