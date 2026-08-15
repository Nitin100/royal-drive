<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Royal Dive') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="font-sans antialiased"
          x-data="{
              sidebarOpen: false,
              sidebarCollapsed: false,
              init() {
                  this.sidebarCollapsed = false;
              }
          }"
          x-init="init()">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900 lg:flex">
            <div class="hidden lg:block lg:shrink-0 w-48" style="float: left;">
                @include('layouts.sidebar')
            </div>

            <div x-show="sidebarOpen"
                 x-transition.opacity
                 class="fixed inset-0 z-40 bg-gray-900/50 lg:hidden"
                 @click="sidebarOpen = false"
                 x-cloak></div>

              <div x-show="sidebarOpen"
                  x-transition:enter="transition ease-out duration-200"
                  x-transition:enter-start="-translate-x-full"
                  x-transition:enter-end="translate-x-0"
                  x-transition:leave="transition ease-in duration-150"
                  x-transition:leave-start="translate-x-0"
                  x-transition:leave-end="-translate-x-full"
                  class="fixed inset-y-0 left-0 z-50 w-72 lg:hidden"
                  x-cloak>
                 @include('layouts.sidebar')
              </div>

            <div class="flex min-h-screen flex-1 flex-col">
                <header class="border-b border-gray-200 bg-white/90 px-4 py-3 backdrop-blur dark:border-gray-700 dark:bg-gray-800/90 lg:px-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <button type="button"
                                    class="inline-flex items-center rounded-md border border-gray-300 p-2 text-gray-600 transition hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700 lg:hidden"
                                    @click="sidebarOpen = true"
                                    aria-label="Open sidebar">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 6H20M4 12H20M4 18H20" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                                </svg>
                            </button>
                            @isset($header)
                                <div>
                                    {{ $header }}
                                </div>
                            @else
                                <h1 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Admin</h1>
                            @endisset
                        </div>
                    </div>
                </header>

                <main class="flex-1">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
