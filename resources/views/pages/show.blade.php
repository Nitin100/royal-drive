<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $page->meta_title ?: $page->title }}</title>
        <meta name="description" content="{{ $page->meta_description ?: '' }}">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-100 text-gray-900 antialiased dark:bg-gray-900 dark:text-gray-100">
        <main class="mx-auto max-w-4xl p-6 lg:p-10">
            <article class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                @if ($page->banner_image_path)
                    <img src="{{ asset('storage/' . $page->banner_image_path) }}" alt="{{ $page->title }} banner" class="h-64 w-full object-cover lg:h-80" />
                @endif

                <div class="p-6 lg:p-8">
                    <h1 class="text-3xl font-semibold tracking-tight">{{ $page->title }}</h1>

                    @if ($page->meta_description)
                        <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">{{ $page->meta_description }}</p>
                    @endif

                    <div class="prose prose-gray mt-6 max-w-none dark:prose-invert">
                        {!! $page->content !!}
                    </div>
                </div>
            </article>
        </main>
    </body>
</html>
