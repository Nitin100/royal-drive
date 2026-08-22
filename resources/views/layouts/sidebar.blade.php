@php
    $sections = [
        [
            'title' => 'Core',
            'items' => [
                ['label' => 'Dashboard', 'route' => 'dashboard', 'active' => ['dashboard'], 'icon' => 'dashboard'],
                ['label' => 'Enquiries', 'route' => 'admin.enquiries.index', 'active' => ['admin.enquiries.*'], 'icon' => 'mail'],
            ],
        ],
        [
            'title' => 'Content',
            'items' => [
                ['label' => 'CMS Pages', 'route' => 'admin.pages.index', 'active' => ['admin.pages.*'], 'icon' => 'document'],
                ['label' => 'Services', 'route' => 'admin.services.index', 'active' => ['admin.services.*'], 'icon' => 'briefcase'],
                ['label' => 'Blogs', 'route' => 'admin.blogs.index', 'active' => ['admin.blogs.*', 'admin.blog-categories.*', 'admin.blog-tags.*'], 'icon' => 'pen'],
                ['label' => 'Blog Categories', 'route' => 'admin.blog-categories.index', 'active' => ['admin.blog-categories.*'], 'icon' => 'layers'],
                ['label' => 'Blog Tags', 'route' => 'admin.blog-tags.index', 'active' => ['admin.blog-tags.*'], 'icon' => 'tag'],
            ],
        ],
        [
            'title' => 'Operations',
            'items' => [
                ['label' => 'Bookings', 'route' => 'admin.bookings.index', 'active' => ['admin.bookings.*'], 'icon' => 'calendar-list'],
                ['label' => 'Fleets', 'route' => 'admin.fleets.index', 'active' => ['admin.fleets.*'], 'icon' => 'car'],
                ['label' => 'Amenities', 'route' => 'admin.amenities.index', 'active' => ['admin.amenities.*'], 'icon' => 'sparkles'],
                ['label' => 'Sliders', 'route' => 'admin.sliders.index', 'active' => ['admin.sliders.*'], 'icon' => 'sliders'],
                ['label' => 'Promotion Popups', 'route' => 'admin.promotion-popups.index', 'active' => ['admin.promotion-popups.*'], 'icon' => 'megaphone'],
                ['label' => 'Locations', 'route' => 'admin.locations.index', 'active' => ['admin.locations.*'], 'icon' => 'map-pin'],
                ['label' => 'Chauffeurs', 'route' => 'admin.chauffeurs.index', 'active' => ['admin.chauffeurs.*'], 'icon' => 'user'],
                ['label' => 'Tours', 'route' => 'admin.tours.index', 'active' => ['admin.tours.*', 'admin.tour-categories.*', 'admin.tour-tags.*'], 'icon' => 'map'],
                ['label' => 'Tour Categories', 'route' => 'admin.tour-categories.index', 'active' => ['admin.tour-categories.*'], 'icon' => 'layers'],
                ['label' => 'Tour Tags', 'route' => 'admin.tour-tags.index', 'active' => ['admin.tour-tags.*'], 'icon' => 'tag'],
            ],
        ],
    ];
@endphp

<aside class="flex h-full w-full flex-col border-r border-gray-800 bg-black text-gray-100">
    <div class="border-b border-gray-800 px-4 py-4">
        <a href="{{ route('dashboard') }}" class="flex items-center justify-center">
            <img src="{{ asset('images/royal-drive-grey.png') }}" alt="Royal Drive" class="h-20 w-auto" />
        </a>
    </div>

    <nav class="flex-1 space-y-6 overflow-y-auto px-3 py-4">
        @foreach ($sections as $section)
            <div>
                <p class="px-2 text-xs font-semibold uppercase tracking-wider text-white-500">{{ $section['title'] }}</p>

                <ul class="mt-2 space-y-1">
                    @foreach ($section['items'] as $item)
                        @if (Route::has($item['route']))
                            @php
                                $isActive = false;
                                foreach ($item['active'] as $pattern) {
                                    if (request()->routeIs($pattern)) {
                                        $isActive = true;
                                        break;
                                    }
                                }
                            @endphp

                            <li>
                                          <a href="{{ route($item['route']) }}"
                                              class="group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium no-underline transition {{ $isActive ? 'bg-gray-100 text-gray-900' : 'text-gray-500 hover:bg-gray-900 hover:text-white' }}">
                                    <span class="h-4 w-4 shrink-0 {{ $isActive ? 'text-gray-900' : 'text-gray-500 group-hover:text-white' }}">
                                        @switch($item['icon'])
                                            @case('dashboard')
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                                                    <path d="M3 13h8V3H3v10Zm10 8h8V11h-8v10ZM3 21h8v-6H3v6Zm10-10h8V3h-8v8Z" />
                                                </svg>
                                                @break
                                            @case('users')
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                                                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                                    <circle cx="8.5" cy="7" r="3.5" />
                                                    <path d="M20 8v6M23 11h-6" />
                                                </svg>
                                                @break
                                            @case('mail')
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                                                    <rect x="3" y="5" width="18" height="14" rx="2" />
                                                    <path d="m3 7 9 6 9-6" />
                                                </svg>
                                                @break
                                            @case('document')
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                                    <path d="M14 2v6h6" />
                                                </svg>
                                                @break
                                            @case('briefcase')
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                                                    <rect x="2" y="7" width="20" height="14" rx="2" />
                                                    <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2" />
                                                </svg>
                                                @break
                                            @case('car')
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                                                    <path d="M5 11h14l-1.5-4h-11z" />
                                                    <path d="M4 11h16a2 2 0 0 1 2 2v4h-2a2 2 0 0 1-4 0H8a2 2 0 0 1-4 0H2v-4a2 2 0 0 1 2-2Z" />
                                                </svg>
                                                @break
                                            @case('user')
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                                                    <circle cx="12" cy="7" r="4" />
                                                    <path d="M5.5 21a6.5 6.5 0 0 1 13 0" />
                                                </svg>
                                                @break
                                            @case('map')
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                                                    <path d="m9 18-6 3V6l6-3 6 3 6-3v15l-6 3-6-3Z" />
                                                    <path d="M9 3v15M15 6v15" />
                                                </svg>
                                                @break
                                            @case('map-pin')
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                                                    <path d="M12 22s7-6.2 7-12a7 7 0 1 0-14 0c0 5.8 7 12 7 12Z" />
                                                    <circle cx="12" cy="10" r="2.5" />
                                                </svg>
                                                @break
                                            @case('sliders')
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                                                    <rect x="3" y="5" width="18" height="14" rx="2" />
                                                    <path d="M8 9h8M8 12h8M8 15h5" />
                                                </svg>
                                                @break
                                            @case('megaphone')
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                                                    <path d="M3 12h2l9-4v8l-9-4H3v-0Z" />
                                                    <path d="M13 9.5V14.5" />
                                                    <path d="M18 10a3.5 3.5 0 0 1 0 4" />
                                                    <path d="M20 8.5a6.5 6.5 0 0 1 0 7" />
                                                </svg>
                                                @break
                                            @case('calendar-list')
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                                                    <rect x="3" y="4" width="18" height="17" rx="2" />
                                                    <path d="M8 2v4M16 2v4M3 10h18" />
                                                    <path d="M7 14h10M7 18h6" />
                                                </svg>
                                                @break
                                            @case('layers')
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                                                    <path d="m12 3 9 5-9 5-9-5 9-5Z" />
                                                    <path d="m3 12 9 5 9-5" />
                                                    <path d="m3 16 9 5 9-5" />
                                                </svg>
                                                @break
                                            @case('tag')
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                                                    <path d="M20.6 13.4 12 22l-9-9V4h9l8.6 8.6a1.13 1.13 0 0 1 0 1.6Z" />
                                                    <circle cx="7.5" cy="8.5" r="1.5" />
                                                </svg>
                                                @break
                                            @case('pen')
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                                                    <path d="m12 20 9-9-3-3-9 9-1 4 4-1Z" />
                                                    <path d="M16 5l3 3" />
                                                </svg>
                                                @break
                                            @default
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                                                    <circle cx="12" cy="12" r="9" />
                                                </svg>
                                        @endswitch
                                    </span>
                                    <span>{{ $item['label'] }}</span>
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        @endforeach
    </nav>

    <div class="border-t border-gray-800 px-3 py-3">
        <a href="{{ route('profile.edit') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-gray-500 transition hover:bg-gray-900 hover:text-white">
            Profile Settings
        </a>

        <form method="POST" action="{{ route('logout') }}" class="mt-1">
            @csrf
            <button type="submit" class="w-50 rounded-lg px-3 py-2 text-left text-sm font-medium text-white bg-red-600 transition hover:bg-gray-900 hover:text-white">
                Logout
            </button>
        </form>
    </div>
</aside>
