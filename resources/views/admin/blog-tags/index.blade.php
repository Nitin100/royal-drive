<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Blog Tags</h2>
    </x-slot>

    <div class="p-6 lg:p-8">
        <div class="mx-auto max-w-7xl grid gap-6 lg:grid-cols-3">
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800 lg:col-span-1">
                <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $tagToEdit ? 'Edit Tag' : 'Add Tag' }}</h3>
                <form class="mt-4 space-y-4" method="POST" action="{{ $tagToEdit ? route('admin.blog-tags.update', $tagToEdit) : route('admin.blog-tags.store') }}">
                    @csrf
                    @if ($tagToEdit)
                        @method('PUT')
                    @endif

                    <div>
                        <label for="name" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Name</label>
                        <input id="name" name="name" type="text" required value="{{ old('name', $tagToEdit->name ?? '') }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
                    </div>

                    <div>
                        <label for="slug" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Slug</label>
                        <input id="slug" name="slug" type="text" value="{{ old('slug', $tagToEdit->slug ?? '') }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="submit" class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-gray-700 dark:bg-gray-100 dark:text-gray-900 dark:hover:bg-gray-200">{{ $tagToEdit ? 'Update' : 'Create' }}</button>
                        @if ($tagToEdit)
                            <a href="{{ route('admin.blog-tags.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">Cancel</a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800 lg:col-span-2">
                @if (session('status'))
                    <div class="border-b border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-700/40 dark:bg-emerald-900/20 dark:text-emerald-300">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
                        <thead>
                            <tr class="text-left text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                <th class="px-4 py-3">Name</th>
                                <th class="px-4 py-3">Slug</th>
                                <th class="px-4 py-3">Posts</th>
                                <th class="px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse ($tags as $tag)
                                <tr>
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ $tag->name }}</td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $tag->slug }}</td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $tag->blogs_count }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('admin.blog-tags.edit', $tag) }}" class="text-indigo-600 hover:text-indigo-500 dark:text-indigo-300">Edit</a>
                                            <form method="POST" action="{{ route('admin.blog-tags.destroy', $tag) }}" onsubmit="return confirm('Delete this tag?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-rose-600 hover:text-rose-500">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">No tags yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
