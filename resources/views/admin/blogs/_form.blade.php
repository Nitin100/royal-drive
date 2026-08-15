@php
    $isEdit = isset($blog);
    $selectedCategories = collect(old('category_ids', $isEdit ? $blog->categories->pluck('id')->all() : []))->map(fn ($id) => (int) $id)->all();
    $selectedTags = collect(old('tag_ids', $isEdit ? $blog->tags->pluck('id')->all() : []))->map(fn ($id) => (int) $id)->all();
@endphp

<div class="space-y-6">
    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <label for="title" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Title</label>
            <input id="title" name="title" type="text" required value="{{ old('title', $blog->title ?? '') }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
            @error('title')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="slug" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Slug</label>
            <div class="flex items-center rounded-lg border border-gray-300 bg-white dark:border-gray-600 dark:bg-gray-800">
                <span class="px-3 text-xs text-gray-500 dark:text-gray-400">/blog/</span>
                <input id="slug" name="slug" type="text" value="{{ old('slug', $blog->slug ?? '') }}" placeholder="blog-post-slug" class="w-full rounded-r-lg border-0 bg-transparent px-3 py-2 text-sm text-gray-900 focus:outline-none dark:text-gray-100" />
            </div>
            @error('slug')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <label for="meta_title" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">SEO Meta Title</label>
            <input id="meta_title" name="meta_title" type="text" maxlength="255" value="{{ old('meta_title', $blog->meta_title ?? '') }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
        </div>
        <div>
            <label for="meta_description" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">SEO Description</label>
            <textarea id="meta_description" name="meta_description" maxlength="160" rows="3" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">{{ old('meta_description', $blog->meta_description ?? '') }}</textarea>
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-3">
        <div class="md:col-span-2">
            <label for="featured_image" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Featured Image</label>
            <input id="featured_image" name="featured_image" type="file" accept="image/png,image/jpeg,image/webp" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm file:mr-3 file:rounded-md file:border-0 file:bg-gray-100 file:px-3 file:py-1.5 file:text-xs file:font-semibold focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 dark:file:bg-gray-700" />
            @error('featured_image')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            @if ($isEdit && $blog->featured_image_path)
                <div class="mt-3 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
                    <img src="{{ asset('storage/' . $blog->featured_image_path) }}" alt="Blog featured image" class="h-40 w-full object-cover" />
                </div>
                <label class="mt-3 inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
                    <input type="checkbox" name="remove_featured_image" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                    Remove existing image
                </label>
            @endif
        </div>

        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900/30">
            <label class="inline-flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $blog->is_featured ?? false)) class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                Featured Post
            </label>
            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Featured posts can be highlighted in your homepage or listing.</p>
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <label for="category_ids" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Categories</label>
            <select id="category_ids" name="category_ids[]" multiple class="blog-multiselect w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" data-placeholder="Select categories">
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(in_array($category->id, $selectedCategories, true))>{{ $category->name }}</option>
                @endforeach
            </select>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Hold Ctrl/Cmd to select multiple.</p>
            @error('category_ids')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="tag_ids" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Tags</label>
            <select id="tag_ids" name="tag_ids[]" multiple class="blog-multiselect w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" data-placeholder="Select tags">
                @foreach ($tags as $tag)
                    <option value="{{ $tag->id }}" @selected(in_array($tag->id, $selectedTags, true))>{{ $tag->name }}</option>
                @endforeach
            </select>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Hold Ctrl/Cmd to select multiple.</p>
            @error('tag_ids')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Content</label>
        <input id="content" type="hidden" name="content" value="{{ old('content', $blog->content ?? '') }}" />
        <trix-editor input="content" class="trix-content rounded-lg border border-gray-300 bg-white text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"></trix-editor>
        @error('content')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-gray-700 dark:bg-gray-100 dark:text-gray-900 dark:hover:bg-gray-200">
            {{ $isEdit ? 'Update Blog' : 'Create Blog' }}
        </button>
        <a href="{{ route('admin.blogs.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
            Cancel
        </a>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<link rel="stylesheet" href="https://unpkg.com/trix@2.1.8/dist/trix.css">
<script src="https://unpkg.com/trix@2.1.8/dist/trix.umd.min.js"></script>

<style>
    .select2-container {
        width: 100% !important;
    }

    .select2-container--default .select2-selection--multiple {
        min-height: 1rem;
        border-radius: 0.5rem;
        border-color: rgb(209 213 219);
        padding: 0.35rem;
    }

    .dark .select2-container--default .select2-selection--multiple {
        background-color: rgb(31 41 55);
        border-color: rgb(75 85 99);
    }

    .dark .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: rgb(55 65 81);
        border-color: rgb(75 85 99);
        color: rgb(243 244 246);
    }

    .dark .select2-container--default .select2-search--inline .select2-search__field {
        color: rgb(243 244 246);
    }

    .dark .select2-dropdown {
        background-color: rgb(31 41 55);
        border-color: rgb(75 85 99);
    }

    .dark .select2-results__option {
        color: rgb(243 244 246);
    }

    .dark .select2-container--default .select2-results__option--selected {
        background-color: rgb(55 65 81);
    }
</style>

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

        if (window.jQuery && $.fn.select2) {
            $('.blog-multiselect').select2({
                width: '100%',
                closeOnSelect: false,
                placeholder: function () {
                    return $(this).data('placeholder');
                }
            });
        }
    });
</script>
