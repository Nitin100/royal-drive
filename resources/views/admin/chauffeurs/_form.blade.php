@php
    $isEdit = isset($chauffeur);
    $availabilityStatuses = $availabilityStatuses ?? [
        'available' => 'Available',
        'unavailable' => 'Unavailable',
        'on-trip' => 'On Trip',
        'resting' => 'Resting',
    ];
    $availabilityCalendar = old('availability_calendar', isset($chauffeur) && is_array($chauffeur->availability_calendar) ? json_encode($chauffeur->availability_calendar, JSON_PRETTY_PRINT) : '[]');
@endphp

<div class="space-y-6">
    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <label for="name" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Name</label>
            <input id="name" name="name" type="text" value="{{ old('name', $chauffeur->name ?? '') }}" required class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
            @error('name')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="slug" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">URL Slug</label>
            <div class="flex items-center rounded-lg border border-gray-300 bg-white dark:border-gray-600 dark:bg-gray-800">
                <span class="px-3 text-xs text-gray-500 dark:text-gray-400">/chauffeurs/</span>
                <input id="slug" name="slug" type="text" value="{{ old('slug', $chauffeur->slug ?? '') }}" placeholder="chauffeur-name" class="w-full rounded-r-lg border-0 bg-transparent px-3 py-2 text-sm text-gray-900 focus:outline-none dark:text-gray-100" />
            </div>
            @error('slug')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <label for="contact_number" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Contact Number</label>
            <input id="contact_number" name="contact_number" type="text" value="{{ old('contact_number', $chauffeur->contact_number ?? '') }}" required class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
            @error('contact_number')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="photo" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Photo</label>
            <input id="photo" name="photo" type="file" accept="image/png,image/jpeg,image/webp" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm file:mr-3 file:rounded-md file:border-0 file:bg-gray-100 file:px-3 file:py-1.5 file:text-xs file:font-semibold focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 dark:file:bg-gray-700" />
            @error('photo')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            @if ($isEdit && $chauffeur->photo_path)
                <div class="mt-3 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
                    <img src="{{ asset('storage/' . $chauffeur->photo_path) }}" alt="Chauffeur photo" class="h-40 w-full object-cover" />
                </div>
                <label class="mt-3 inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
                    <input type="checkbox" name="remove_photo" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                    Remove existing photo
                </label>
            @endif
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-3">
        <div>
            <label for="license_number" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Driving License Details</label>
            <input id="license_number" name="license_number" type="text" value="{{ old('license_number', $chauffeur->license_number ?? '') }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
        </div>
        <div>
            <label for="license_expiry_date" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">License Expiry</label>
            <input id="license_expiry_date" name="license_expiry_date" type="date" value="{{ old('license_expiry_date', optional($chauffeur->license_expiry_date ?? null)->format('Y-m-d')) }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
        </div>
        <div>
            <label for="experience_years" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Experience</label>
            <input id="experience_years" name="experience_years" type="number" min="0" value="{{ old('experience_years', $chauffeur->experience_years ?? '') }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-3">
        <div>
            <label for="languages_spoken" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Languages Spoken</label>
            <input id="languages_spoken" name="languages_spoken" type="text" value="{{ old('languages_spoken', $chauffeur->languages_spoken ?? '') }}" placeholder="English, Hindi" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
        </div>
        <div>
            <label for="rating" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Rating</label>
            <input id="rating" name="rating" type="number" min="0" max="5" step="0.1" value="{{ old('rating', $chauffeur->rating ?? 0) }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
        </div>
        <div>
            <label for="availability_status" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Availability</label>
            <select id="availability_status" name="availability_status" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                @foreach ($availabilityStatuses as $value => $label)
                    <option value="{{ $value }}" @selected(old('availability_status', $chauffeur->availability_status ?? 'available') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <label for="meta_title" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">SEO Meta Title</label>
            <input id="meta_title" name="meta_title" type="text" maxlength="255" value="{{ old('meta_title', $chauffeur->meta_title ?? '') }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
        </div>
        <div>
            <label for="meta_description" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">SEO Description</label>
            <textarea id="meta_description" name="meta_description" maxlength="160" rows="3" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">{{ old('meta_description', $chauffeur->meta_description ?? '') }}</textarea>
        </div>
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Chauffeur Profile</label>
        <input id="bio" type="hidden" name="bio" value="{{ old('bio', $chauffeur->bio ?? '') }}" />
        <trix-editor input="bio" class="trix-content rounded-lg border border-gray-300 bg-white text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"></trix-editor>
    </div>


    <div class="rounded-xl border border-gray-200 bg-gray-50 p-5 dark:border-gray-700 dark:bg-gray-900/40">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Assign Booking</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Create a booking assignment for this chauffeur.</p>
            </div>
        </div>

        <div class="mt-4 grid gap-4 md:grid-cols-2">
            <div>
                <label for="assignment_booking_reference" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Booking Reference</label>
                <input id="assignment_booking_reference" name="assignment_booking_reference" type="text" value="{{ old('assignment_booking_reference') }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
            </div>
            <div>
                <label for="assignment_service_date" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Service Date</label>
                <input id="assignment_service_date" name="assignment_service_date" type="date" value="{{ old('assignment_service_date') }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
            </div>
            <div>
                <label for="assignment_pickup_time" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Pickup Time</label>
                <input id="assignment_pickup_time" name="assignment_pickup_time" type="time" value="{{ old('assignment_pickup_time') }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
            </div>
            <div>
                <label for="assignment_route_name" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Route Name</label>
                <input id="assignment_route_name" name="assignment_route_name" type="text" value="{{ old('assignment_route_name') }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
            </div>
            <div>
                <label for="assignment_distance_km" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Distance (km)</label>
                <input id="assignment_distance_km" name="assignment_distance_km" type="number" min="0" step="0.1" value="{{ old('assignment_distance_km') }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
            </div>
        </div>

        <div class="mt-4">
            <label for="assignment_notes" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Assignment Notes</label>
            <textarea id="assignment_notes" name="assignment_notes" rows="3" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">{{ old('assignment_notes') }}</textarea>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="rounded-lg bg-gray-800 px-4 py-2 text-sm font-semibold text-white transition hover:bg-gray-700 dark:bg-gray-100 dark:text-gray-900 dark:hover:bg-gray-200">
            {{ $isEdit ? 'Update Chauffeur' : 'Create Chauffeur' }}
        </button>
        <a href="{{ route('admin.chauffeurs.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
            Cancel
        </a>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/trix@2.1.8/dist/trix.css">
<script src="https://unpkg.com/trix@2.1.8/dist/trix.umd.min.js"></script>

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
