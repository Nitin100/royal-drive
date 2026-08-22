<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Promotion Popups</h2>
    </x-slot>

    <div class="p-6 lg:p-8">
        <div class="mx-auto max-w-7xl">
            <div class="mb-6 flex items-center justify-between gap-3">
                <div>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Manage Frontend Promotions</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Create promotion banners or popups that appear on the public website.</p>
                </div>
            </div>

            @if (session('status'))
                <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-700/40 dark:bg-emerald-900/20 dark:text-emerald-300">
                    {{ session('status') }}
                </div>
            @endif

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <form method="POST" action="{{ isset($popupToEdit) ? route('admin.promotion-popups.update', $popupToEdit) : route('admin.promotion-popups.store') }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @if(isset($popupToEdit))
                        @method('PUT')
                    @endif

                    <div class="grid gap-5 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label for="title" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Title</label>
                            <input id="title" name="title" type="text" value="{{ old('title', $popupToEdit->title ?? '') }}" class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100" required>
                            @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="subtitle" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Subtitle</label>
                            <input id="subtitle" name="subtitle" type="text" value="{{ old('subtitle', $popupToEdit->subtitle ?? '') }}" class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                        </div>

                        <div>
                            <label for="button_label" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Button Label</label>
                            <input id="button_label" name="button_label" type="text" value="{{ old('button_label', $popupToEdit->button_label ?? '') }}" class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                        </div>

                        <div class="md:col-span-2">
                            <label for="button_url" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Button Link</label>
                            <input id="button_url" name="button_url" type="url" value="{{ old('button_url', $popupToEdit->button_url ?? '') }}" class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100" placeholder="https://example.com/offers">
                            @error('button_url') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="body" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Body</label>
                            <textarea id="body" name="body" rows="4" class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">{{ old('body', $popupToEdit->body ?? '') }}</textarea>
                        </div>

                        <div>
                            <label for="image" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Image</label>
                            <input id="image" name="image" type="file" accept="image/*" class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 shadow-sm file:mr-3 file:rounded file:border-0 file:bg-gray-100 file:px-3 file:py-2 file:text-sm file:font-medium dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 dark:file:bg-gray-800">
                            @if(!empty($popupToEdit?->image_path))
                                <div class="mt-3">
                                    <img src="{{ Storage::disk('public')->url($popupToEdit->image_path) }}" alt="Current popup image" class="h-20 w-auto rounded-lg border border-gray-200 dark:border-gray-700">
                                    <label class="mt-2 inline-flex items-center gap-2 text-xs text-gray-600 dark:text-gray-300">
                                        <input type="checkbox" name="remove_image" value="1"> Remove image
                                    </label>
                                </div>
                            @endif
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Status</label>
                            <label class="mt-1 flex items-center gap-3 rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-700 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-200">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $popupToEdit->is_active ?? false) ? 'checked' : '' }}>
                                <span>Active immediately</span>
                            </label>
                        </div>

                        <div>
                            <label for="starts_at" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Starts At</label>
                            <input id="starts_at" name="starts_at" type="datetime-local" value="{{ old('starts_at', $popupToEdit?->starts_at ? $popupToEdit->starts_at->format('Y-m-d\TH:i') : '') }}" class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                        </div>

                        <div>
                            <label for="ends_at" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Ends At</label>
                            <input id="ends_at" name="ends_at" type="datetime-local" value="{{ old('ends_at', $popupToEdit?->ends_at ? $popupToEdit->ends_at->format('Y-m-d\TH:i') : '') }}" class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="submit" class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-gray-700 dark:bg-gray-100 dark:text-gray-900 dark:hover:bg-gray-200">
                            {{ isset($popupToEdit) ? 'Update Popup' : 'Save Popup' }}
                        </button>
                    </div>
                </form>
            </div>

            <div class="mt-8 overflow-hidden rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="mb-4 flex items-center justify-between gap-3">
                    <h4 class="text-base font-semibold text-gray-900 dark:text-gray-100">Existing Popups</h4>
                </div>
                <div class="overflow-x-auto">
                    <table id="promotionPopupsTable" class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
                        <thead>
                            <tr class="text-left text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                <th class="px-4 py-3">Title</th>
                                <th class="px-4 py-3">Status</th>
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
            $('#promotionPopupsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.promotion-popups.index') }}',
                order: [[2, 'desc']],
                columns: [
                    { data: 'title', name: 'title' },
                    { data: 'status', name: 'is_active', orderable: true },
                    { data: 'updated', name: 'updated_at' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false },
                ],
            });
        });
    </script>
</x-app-layout>
