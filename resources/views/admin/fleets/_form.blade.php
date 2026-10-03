@php
    $isEdit = isset($fleet);
    $serviceOptions = $serviceOptions ?? \App\Models\Service::query()->orderBy('title')->pluck('title', 'id')->all();
    $serviceTypeMap = \App\Models\Service::query()->orderBy('title')->pluck('service_type', 'id')->all();
    $serviceTitleMap = \App\Models\Service::query()->orderBy('title')->pluck('id', 'title')->all();
    $serviceTypeByTitle = \App\Models\Service::query()->orderBy('title')->pluck('service_type', 'title')->all();
    $pricingPlans = old('pricing_config')
        ? json_decode(old('pricing_config'), true)
        : (@$fleet?->pricing_config ?? []);
    $pricingPlans = is_array($pricingPlans) && count($pricingPlans) ? $pricingPlans : [
        ['name' => 'Standard', 'price' => '', 'features' => ['Feature 1', 'Feature 2']],
    ];
    $statusOptions = $availabilityStatuses ?? [
        'available' => 'Available',
        'unavailable' => 'Unavailable',
        'maintenance' => 'Maintenance',
        'booked' => 'Booked',
    ];
@endphp

<div class="space-y-6" x-data="fleetPricingBuilder(@js($pricingPlans), @js($serviceTypeMap), @js($serviceTitleMap), @js($serviceTypeByTitle))">
    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <label for="name" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Vehicle Name</label>
            <input id="name" name="name" type="text" value="{{ old('name', @$fleet?->name ?? '') }}" required class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
            @error('name')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="slug" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">URL Slug</label>
            <div class="flex items-center rounded-lg border border-gray-300 bg-white dark:border-gray-600 dark:bg-gray-800">
                <span class="px-3 text-xs text-gray-500 dark:text-gray-400">/fleets/</span>
                <input id="slug" name="slug" type="text" value="{{ old('slug', @$fleet?->slug ?? '') }}" placeholder="vehicle-name" class="w-full rounded-r-lg border-0 bg-transparent px-3 py-2 text-sm text-gray-900 focus:outline-none dark:text-gray-100" />
            </div>
            @error('slug')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <label for="category" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Vehicle Category</label>
            <select id="category" name="category" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                @foreach ($categories as $category)
                    <option value="{{ $category }}" @selected(old('category', $fleet?->category ?? '') === $category)>{{ $category }}</option>
                @endforeach
            </select>
            @error('category')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="brand" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Vehicle Brand</label>
            <input id="brand" name="brand" type="text" value="{{ old('brand', @$fleet?->brand ?? '') }}" required class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
            @error('brand')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-3">
        <div>
            <label for="passenger_capacity" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Passenger Capacity</label>
            <select id="passenger_capacity" name="passenger_capacity" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                @for ($i = 1; $i <= 6; $i++)
                    <option value="{{ $i }}" @selected(old('passenger_capacity', @$fleet?->passenger_capacity ?? 1) == $i)>{{ $i }}</option>
                @endfor
            </select>
            @error('passenger_capacity')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="luggage_capacity" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Luggage Capacity</label>
            <select id="luggage_capacity" name="luggage_capacity" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                @for ($i = 0; $i <= 4; $i++)
                    <option value="{{ $i }}" @selected(old('luggage_capacity', $fleet?->luggage_capacity ?? 0) == $i)>{{ $i }}</option>
                @endfor
            </select>
            @error('luggage_capacity')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="availability_status" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Vehicle Availability Status</label>
            <select id="availability_status" name="availability_status" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                @foreach ($statusOptions as $value => $label)
                    <option value="{{ $value }}" @selected(old('availability_status', @$fleet?->availability_status ?? 'available') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('availability_status')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <label for="meta_title" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">SEO Meta Title</label>
            <input id="meta_title" name="meta_title" type="text" maxlength="255" value="{{ old('meta_title', @$fleet?->meta_title ?? '') }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
            @error('meta_title')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="meta_description" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">SEO Description</label>
            <textarea id="meta_description" name="meta_description" maxlength="160" rows="3" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">{{ old('meta_description', $fleet?->meta_description ?? '') }}</textarea>
            @error('meta_description')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>
    </div>

    <div>
        <label for="banner_image" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Vehicle Banner Image</label>
        <input id="banner_image" name="banner_image" type="file" accept="image/png,image/jpeg,image/webp" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm file:mr-3 file:rounded-md file:border-0 file:bg-gray-100 file:px-3 file:py-1.5 file:text-xs file:font-semibold focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 dark:file:bg-gray-700" />
        @error('banner_image')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror

        @if ($isEdit && @$fleet && $fleet?->banner_image_path)
            <div class="mt-3 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
                <img src="{{ asset('storage/' . $fleet?->banner_image_path) }}" alt="Current banner" class="h-40 w-full object-cover" />
            </div>
            <label class="mt-3 inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
                <input type="checkbox" name="remove_banner_image" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                Remove existing banner image
            </label>
        @endif
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Amenities</label>
        <div class="grid gap-3 rounded-xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900/40 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($amenities as $amenity)
                @php
                    $selected = old('amenities', @$fleet?->amenities?->pluck('id')->toArray() ?? []);
                @endphp
                <label class="flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-700 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200">
                    <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}" @checked(in_array((string) $amenity->id, array_map('strval', $selected), true)) class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                    <span>{{ $amenity->name }}</span>
                </label>
            @endforeach
        </div>
        @if ($amenities->isEmpty())
            <p class="mt-2 text-sm text-amber-600 dark:text-amber-400">No amenities are available yet. Add some from the Amenities module.</p>
        @endif
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Features & Amenities</label>
        <input id="description" type="hidden" name="description" value="{{ old('description', @$fleet?->description ?? '') }}" />
        <trix-editor input="description" class="trix-content rounded-lg border border-gray-300 bg-white text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"></trix-editor>
        @error('description')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div class="rounded-xl border border-gray-200 bg-gray-50 p-5 dark:border-gray-700 dark:bg-gray-900/40">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Vehicle Pricing</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Build pricing plans with features.</p>
            </div>
            <button type="button" class="rounded-lg bg-gray-900 px-3 py-2 text-sm font-semibold text-white dark:bg-gray-100 dark:text-gray-900" @click="addPlan()">Add Plan</button>
        </div>

        <input type="hidden" name="pricing_config" :value="serializedPlans">

        <div class="mt-4 space-y-4">
            <template x-for="(plan, index) in plans" :key="index">
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="grid gap-4 md:grid-cols-3">
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Service</label>
                            <select x-model="plan.service_id" @change="plan.name = $event.target.selectedOptions[0]?.text || ''" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                                <option value="">Select a service</option>
                                @foreach ($serviceOptions as $id => $title)
                                    <option value="{{ $id }}">{{ $title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Price</label>
                            <input type="text" x-model="plan.price" x-bind:placeholder="getPricePlaceholder(plan.service_id, plan.name)" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                        </div>
                        <div class="flex items-end justify-end">
                            <button type="button" class="rounded-lg border border-rose-200 px-3 py-2 text-sm font-semibold text-rose-600" @click="removePlan(index)">Remove</button>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="mb-1 block text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Pricing Features</label>
                        <textarea x-model="plan.featuresText" rows="3" placeholder="One feature per line" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"></textarea>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <div>
        <label for="gallery_images" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Vehicle Images</label>
        <input id="gallery_images" name="gallery_images[]" type="file" accept="image/png,image/jpeg,image/webp" multiple class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm file:mr-3 file:rounded-md file:border-0 file:bg-gray-100 file:px-3 file:py-1.5 file:text-xs file:font-semibold focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 dark:file:bg-gray-700" />
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Upload multiple vehicle images for the gallery.</p>
        @error('gallery_images')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror

        @if ($isEdit && @$fleet && $fleet?->images?->isNotEmpty())
            <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($fleet?->images ?? [] as $image)
                    <label class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="Vehicle image" class="h-36 w-full object-cover" />
                        <span class="flex items-center gap-2 bg-white px-3 py-2 text-sm text-gray-700 dark:bg-gray-800 dark:text-gray-200">
                            <input type="checkbox" name="delete_gallery_images[]" value="{{ $image->id }}" class="rounded border-gray-300 text-rose-600 focus:ring-rose-500" />
                            Delete
                        </span>
                    </label>
                @endforeach
            </div>
        @endif
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="rounded-lg bg-gray-800 px-4 py-2 text-sm font-semibold text-white transition hover:bg-gray-700 dark:bg-gray-100 dark:text-gray-900 dark:hover:bg-gray-200">
            {{ $isEdit ? 'Update Fleet' : 'Create Fleet' }}
        </button>
        <a href="{{ route('admin.fleets.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
            Cancel
        </a>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/trix@2.1.8/dist/trix.css">
<script src="https://unpkg.com/trix@2.1.8/dist/trix.umd.min.js"></script>

<script>
    function fleetPricingBuilder(initialPlans, serviceTypeMap = {}, serviceTitleMap = {}, serviceTypeByTitle = {}) {
        const normalizePlan = (plan) => {
            const mappedServiceId = plan?.service_id ?? (plan?.name ? (serviceTitleMap[plan.name] ?? '') : '');

            return {
                service_id: mappedServiceId,
                name: plan?.name ?? '',
                price: plan?.price ?? '',
                featuresText: Array.isArray(plan?.features) ? plan.features.join('\n') : (plan?.featuresText ?? ''),
            };
        };

        const getUnitForType = (serviceType) => {
            const unitMap = {
                Daily: 'day',
                Weekly: 'week',
                Monthly: 'Month',
                'Long Lease': 'Lease',
            };

            return unitMap[serviceType] || 'day';
        };

        return {
            plans: Array.isArray(initialPlans) && initialPlans.length ? initialPlans.map(normalizePlan) : [normalizePlan({})],
            serviceTypeMap,
            serviceTypeByTitle,
            addPlan() {
                this.plans.push(normalizePlan({}));
            },
            removePlan(index) {
                this.plans.splice(index, 1);
                if (this.plans.length === 0) {
                    this.plans.push(normalizePlan({}));
                }
            },
            getPricePlaceholder(serviceId, serviceName = '') {
                const serviceType = this.serviceTypeMap[serviceId] || this.serviceTypeByTitle[serviceName] || 'Daily';

                return `OMR 99 / ${getUnitForType(serviceType)}`;
            },
            get serializedPlans() {
                return JSON.stringify(this.plans.map((plan) => ({
                    name: plan.name,
                    price: plan.price,
                    features: plan.featuresText
                        .split('\n')
                        .map((feature) => feature.trim())
                        .filter(Boolean),
                })));
            },
        };
    }

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
