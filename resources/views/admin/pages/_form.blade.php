@php
    $isEdit = isset($page);
@endphp

<div class="space-y-6">
    <div>
        <label for="title" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Page Title</label>
        <input id="title"
               name="title"
               type="text"
               value="{{ old('title', $page->title ?? '') }}"
               required
               class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
        @error('title')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="slug" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">URL Slug</label>
        <div class="flex items-center rounded-lg border border-gray-300 bg-white dark:border-gray-600 dark:bg-gray-800">
            <span class="px-3 text-xs text-gray-500 dark:text-gray-400">/pages/</span>
            <input id="slug"
                   name="slug"
                   type="text"
                   value="{{ old('slug', $page->slug ?? '') }}"
                   placeholder="about-us"
                   class="w-full rounded-r-lg border-0 bg-transparent px-3 py-2 text-sm text-gray-900 focus:outline-none dark:text-gray-100" />
        </div>
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Lowercase letters, numbers, and hyphens only.</p>
        @error('slug')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <label for="meta_title" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">SEO Meta Title</label>
            <input id="meta_title"
                   name="meta_title"
                   type="text"
                   maxlength="255"
                   value="{{ old('meta_title', $page->meta_title ?? '') }}"
                   class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
            @error('meta_title')
                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="meta_description" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">SEO Description</label>
            <textarea id="meta_description"
                      name="meta_description"
                      maxlength="160"
                      rows="3"
                      class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">{{ old('meta_description', $page->meta_description ?? '') }}</textarea>
            @error('meta_description')
                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="banner_image" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Banner Image</label>
        <input id="banner_image"
               name="banner_image"
               type="file"
               accept="image/png,image/jpeg,image/webp"
               class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm file:mr-3 file:rounded-md file:border-0 file:bg-gray-100 file:px-3 file:py-1.5 file:text-xs file:font-semibold focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 dark:file:bg-gray-700" />
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Max 2MB. JPG, PNG, or WEBP.</p>
        @error('banner_image')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror

        @if ($isEdit && $page->banner_image_path)
            <div class="mt-3 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
                <img src="{{ asset('storage/' . $page->banner_image_path) }}" alt="Current banner" class="h-36 w-full object-cover" />
            </div>
            <label class="mt-3 inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
                <input type="checkbox" name="remove_banner_image" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                Remove existing banner image
            </label>
        @endif
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Page Content</label>
        <input id="content" type="hidden" name="content" value="{{ old('content', $page->content ?? '') }}" />
        <trix-editor input="content" class="trix-content rounded-lg border border-gray-300 bg-white text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"></trix-editor>
        @error('content')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-gray-700 dark:bg-gray-100 dark:text-gray-900 dark:hover:bg-gray-200">
            {{ $isEdit ? 'Update Page' : 'Create Page' }}
        </button>
        <a href="{{ route('admin.pages.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
            Cancel
        </a>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/trix@2.1.8/dist/trix.css">
<script src="https://unpkg.com/trix@2.1.8/dist/trix.umd.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const titleInput = document.getElementById('title');
        const slugInput = document.getElementById('slug');

        if (!titleInput || !slugInput) {
            return;
        }

        titleInput.addEventListener('input', function () {
            if (slugInput.dataset.manual === '1') {
                return;
            }

            slugInput.value = titleInput.value
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
