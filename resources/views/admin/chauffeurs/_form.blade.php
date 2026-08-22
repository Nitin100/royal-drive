@php
    $isEdit = isset($chauffeur);
    $availabilityStatuses = $availabilityStatuses ?? [
        'available' => 'Available',
        'unavailable' => 'Unavailable',
        'on-trip' => 'On Trip',
        'resting' => 'Resting',
    ];
    $availableLanguages = [
        'English',
        'Arabic',
        'Hindi',
        'French',
        'German',
        'Spanish',
        'Urdu',
        'Russian',
        'Portuguese',
        'Chinese',
    ];
    $availabilityCalendar = old('availability_calendar', isset($chauffeur) && is_array($chauffeur->availability_calendar) ? json_encode($chauffeur->availability_calendar, JSON_PRETTY_PRINT) : '[]');
@endphp

<div class="space-y-6">
    <div class="grid gap-6 md:grid-cols-1">
        <div>
            <label for="name" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Name</label>
            <input id="name" name="name" type="text" value="{{ old('name', $chauffeur->name ?? '') }}" required class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
            @error('name')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <label for="contact_number" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Contact Number</label>
            <input id="contact_number" name="contact_number" type="text" value="{{ old('contact_number', $chauffeur->contact_number ?? '') }}" required class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
            @error('contact_number')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="photo" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">License Photo</label>
            <input id="photo" name="photo" type="file" accept="image/png,image/jpeg,image/webp" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm file:mr-3 file:rounded-md file:border-0 file:bg-gray-100 file:px-3 file:py-1.5 file:text-xs file:font-semibold focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 dark:file:bg-gray-700" />
            @error('photo')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            @if ($isEdit && $chauffeur->photo_path)
                <div class="mt-3 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
                    <img src="{{ asset('storage/' . $chauffeur->photo_path) }}" alt="Chauffeur license photo" class="h-40 w-full object-cover" />
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

    <div class="grid gap-6 md:grid-cols-1">
        <div>
            <label for="languages_spoken" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Languages Spoken</label>
            <select id="languages_spoken" name="languages_spoken" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                <option value="">Select language</option>
                @foreach ($availableLanguages as $language)
                    <option value="{{ $language }}" @selected(old('languages_spoken', $chauffeur->languages_spoken ?? '') === $language)>{{ $language }}</option>
                @endforeach
            </select>
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

        if (!nameInput) {
            return;
        }
    });
</script>
