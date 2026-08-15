@php
    $isEdit = isset($location);
@endphp

<div class="space-y-6">
    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <label for="name" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Location Name</label>
            <input id="name" name="name" type="text" required value="{{ old('name', $location->name ?? '') }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
            @error('name')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="type" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Location Type</label>
            <select id="type" name="type" required class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                <option value="">Select type</option>
                @foreach ($typeOptions as $value => $label)
                    <option value="{{ $value }}" @selected(old('type', $location->type ?? '') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('type')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <label for="slug" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Slug</label>
            <div class="flex items-center rounded-lg border border-gray-300 bg-white dark:border-gray-600 dark:bg-gray-800">
                <span class="px-3 text-xs text-gray-500 dark:text-gray-400">/locations/</span>
                <input id="slug" name="slug" type="text" value="{{ old('slug', $location->slug ?? '') }}" placeholder="location-slug" class="w-full rounded-r-lg border-0 bg-transparent px-3 py-2 text-sm text-gray-900 focus:outline-none dark:text-gray-100" />
            </div>
            @error('slug')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900/30">
        <label class="inline-flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-200">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $isEdit ? $location->is_active : true)) class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
            Active Location
        </label>
        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Only active locations should be used in public booking forms.</p>
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-gray-700 dark:bg-gray-100 dark:text-gray-900 dark:hover:bg-gray-200">
            {{ $isEdit ? 'Update Location' : 'Create Location' }}
        </button>
        <a href="{{ route('admin.locations.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
            Cancel
        </a>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');

        if (!nameInput || !slugInput) {
            return;
        }

        nameInput.addEventListener('input', function () {
            if (slugInput.dataset.manual === '1') {
                return;
            }

            slugInput.value = nameInput.value
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
        });

        slugInput.addEventListener('input', function () {
            slugInput.dataset.manual = slugInput.value.length > 0 ? '1' : '0';
        });
    });
</script>
