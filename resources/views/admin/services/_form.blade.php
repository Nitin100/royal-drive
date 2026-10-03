@php
    $isEdit = isset($service);
    $pricingPlans = old('pricing_config')
        ? json_decode(old('pricing_config'), true)
        : ($service->pricing_config ?? []);
    $pricingPlans = is_array($pricingPlans) && count($pricingPlans) ? $pricingPlans : [
        ['name' => 'Starter', 'price' => '', 'features' => ['Feature 1', 'Feature 2']],
    ];
@endphp

<div class="space-y-6" x-data="pricingBuilder(@js($pricingPlans))">
    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <label for="title" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Service Title</label>
            <input id="title" name="title" type="text" value="{{ old('title', $service->title ?? '') }}" required class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
            @error('title')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="slug" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">URL Slug</label>
            <div class="flex items-center rounded-lg border border-gray-300 bg-white dark:border-gray-600 dark:bg-gray-800">
                <span class="px-3 text-xs text-gray-500 dark:text-gray-400">/services/</span>
                <input id="slug" name="slug" type="text" value="{{ old('slug', $service->slug ?? '') }}" placeholder="service-name" class="w-full rounded-r-lg border-0 bg-transparent px-3 py-2 text-sm text-gray-900 focus:outline-none dark:text-gray-100" />
            </div>
            @error('slug')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <label for="meta_title" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">SEO Meta Title</label>
            <input id="meta_title" name="meta_title" type="text" maxlength="255" value="{{ old('meta_title', $service->meta_title ?? '') }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
            @error('meta_title')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="service_type" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Service Type</label>
            <select id="service_type" required name="service_type" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                <option value="" {{ old('service_type', $service->service_type ?? '') == '' ? 'selected' : '' }}>Select service type</option>
                <option value="Daily" {{ old('service_type', $service->service_type ?? '') == 'Daily' ? 'selected' : '' }}>Daily</option>
                <option value="Weekly" {{ old('service_type', $service->service_type ?? '') == 'Weekly' ? 'selected' : '' }}>Weekly</option>
                <option value="Monthly" {{ old('service_type', $service->service_type ?? '') == 'Monthly' ? 'selected' : '' }}>Monthly</option>
                <option value="Long Lease" {{ old('service_type', $service->service_type ?? '') == 'Long Lease' ? 'selected' : '' }}>Long Lease</option>
            </select>
            @error('service_type')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>
    </div>
    <div class="">
        <div>
            <label for="meta_description" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">SEO Description</label>
            <textarea id="meta_description" name="meta_description" maxlength="160" rows="3" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">{{ old('meta_description', $service->meta_description ?? '') }}</textarea>
            @error('meta_description')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>
    </div>

    <div>
        <label for="banner_image" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Banner Image</label>
        <input id="banner_image" name="banner_image" type="file" accept="image/png,image/jpeg,image/webp" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm file:mr-3 file:rounded-md file:border-0 file:bg-gray-100 file:px-3 file:py-1.5 file:text-xs file:font-semibold focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 dark:file:bg-gray-700" />
        @error('banner_image')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror

        @if ($isEdit && $service->banner_image_path)
            <div class="mt-3 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
                <img src="{{ asset('storage/' . $service->banner_image_path) }}" alt="Current banner" class="h-40 w-full object-cover" />
            </div>
            <label class="mt-3 inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
                <input type="checkbox" name="remove_banner_image" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                Remove existing banner image
            </label>
        @endif
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Service Description</label>
        <input id="description" type="hidden" name="description" value="{{ old('description', $service->description ?? '') }}" />
        <trix-editor input="description" class="trix-content rounded-lg border border-gray-300 bg-white text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"></trix-editor>
        @error('description')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="gallery_images" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Service Image Gallery</label>
        <input id="gallery_images" name="gallery_images[]" type="file" accept="image/png,image/jpeg,image/webp" multiple class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm file:mr-3 file:rounded-md file:border-0 file:bg-gray-100 file:px-3 file:py-1.5 file:text-xs file:font-semibold focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 dark:file:bg-gray-700" />
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Upload multiple images for the service gallery.</p>
        @error('gallery_images')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror

        @if ($isEdit && $service->images->isNotEmpty())
            <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($service->images as $image)
                    <label class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="Gallery image" class="h-36 w-full object-cover" />
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
            {{ $isEdit ? 'Update Service' : 'Create Service' }}
        </button>
        <a href="{{ route('admin.services.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
            Cancel
        </a>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/trix@2.1.8/dist/trix.css">
<script src="https://unpkg.com/trix@2.1.8/dist/trix.umd.min.js"></script>

<script>
    function pricingBuilder(initialPlans) {
        const normalizePlan = (plan) => ({
            name: plan?.name ?? '',
            price: plan?.price ?? '',
            featuresText: Array.isArray(plan?.features) ? plan.features.join('\n') : (plan?.featuresText ?? ''),
        });

        return {
            plans: Array.isArray(initialPlans) && initialPlans.length ? initialPlans.map(normalizePlan) : [normalizePlan({})],
            addPlan() {
                this.plans.push(normalizePlan({}));
            },
            removePlan(index) {
                this.plans.splice(index, 1);
                if (this.plans.length === 0) {
                    this.plans.push(normalizePlan({}));
                }
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
