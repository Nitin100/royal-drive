<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFleetRequest;
use App\Http\Requests\UpdateFleetRequest;
use App\Models\Amenity;
use App\Models\Fleet;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FleetController extends Controller
{
    private const CATEGORIES = [
        'Economy',
        'Business',
        'Executive',
        'Luxury',
        'SUV',
        'Van',
        'Coach',
    ];

    private const AVAILABILITY_STATUSES = [
        'available' => 'Available',
        'unavailable' => 'Unavailable',
        'maintenance' => 'Maintenance',
        'booked' => 'Booked',
    ];

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->datatable($request);
        }

        $fleets = Fleet::query()->withCount('images')->latest()->paginate(10);

        return view('admin.fleets.index', [
            'fleets' => $fleets,
        ]);
    }

    private function datatable(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = max(0, (int) $request->input('start', 0));
        $length = (int) $request->input('length', 10);
        $searchValue = trim((string) $request->input('search.value', ''));
        $orderColumnIndex = (int) $request->input('order.0.column', 0);
        $orderDirection = strtolower((string) $request->input('order.0.dir', 'asc')) === 'desc' ? 'desc' : 'asc';
        $columns = ['name', 'category', 'brand', 'availability_status', null, null];
        $orderColumn = $columns[$orderColumnIndex] ?? 'name';

        $query = Fleet::query()->withCount('images');
        $recordsTotal = (clone $query)->count();

        if ($searchValue !== '') {
            $query->where(function ($builder) use ($searchValue) {
                $builder->where('name', 'like', "%{$searchValue}%")
                    ->orWhere('slug', 'like', "%{$searchValue}%")
                    ->orWhere('category', 'like', "%{$searchValue}%")
                    ->orWhere('brand', 'like', "%{$searchValue}%")
                    ->orWhere('availability_status', 'like', "%{$searchValue}%");
            });
        }

        $recordsFiltered = (clone $query)->count();

        if ($orderColumn !== null) {
            $query->orderBy($orderColumn, $orderDirection);
        } else {
            $query->latest();
        }

        $fleets = $query->skip($start)
            ->take($length > 0 ? $length : 10)
            ->get();

        $data = $fleets->map(function (Fleet $fleet) {
            $editUrl = route('admin.fleets.edit', $fleet);
            $deleteUrl = route('admin.fleets.destroy', $fleet);
            $csrf = csrf_token();
            $pricingCount = is_array($fleet->pricing_config) ? count($fleet->pricing_config) : 0;

            return [
                'vehicle' => e($fleet->name),
                'category' => e($fleet->category),
                'brand' => e($fleet->brand),
                'status' => e(ucfirst($fleet->availability_status)),
                'pricing' => $pricingCount > 0 ? $pricingCount . ' plans' : '-',
                'gallery' => (string) ($fleet->images_count ?? 0),
                'actions' => "<div class=\"flex items-center gap-3\">"
                    . "<a href=\"{$editUrl}\" class=\"text-indigo-600 hover:text-indigo-500 dark:text-indigo-300\">Edit</a>"
                    . "<form method=\"POST\" action=\"{$deleteUrl}\" onsubmit=\"return confirm('Delete this fleet record?');\">"
                    . "<input type=\"hidden\" name=\"_token\" value=\"{$csrf}\">"
                    . "<input type=\"hidden\" name=\"_method\" value=\"DELETE\">"
                    . "<button type=\"submit\" class=\"text-rose-600 hover:text-rose-500\">Delete</button>"
                    . '</form></div>',
            ];
        })->values();

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function create(): View
    {
        return view('admin.fleets.create', [
            'categories' => self::CATEGORIES,
            'availabilityStatuses' => self::AVAILABILITY_STATUSES,
            'serviceOptions' => Service::query()->orderBy('title')->pluck('title', 'id')->all(),
            'amenities' => Amenity::query()->orderBy('name')->get(),
        ]);
    }

    public function store(StoreFleetRequest $request): RedirectResponse
    {
        $fleet = Fleet::create($this->preparePayload($request->validated(), $request->file('banner_image')));
        $this->syncGallery($fleet, $request->file('gallery_images', []));
        $this->syncAmenities($fleet, $request->input('amenities', []));

        return redirect()->route('admin.fleets.index')->with('status', 'Fleet created successfully.');
    }

    public function edit(Fleet $fleet): View
    {
        $fleet->load(['images', 'amenities']);

        return view('admin.fleets.edit', [
            'fleet' => $fleet,
            'categories' => self::CATEGORIES,
            'availabilityStatuses' => self::AVAILABILITY_STATUSES,
            'serviceOptions' => Service::query()->orderBy('title')->pluck('title', 'id')->all(),
            'amenities' => Amenity::query()->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateFleetRequest $request, Fleet $fleet): RedirectResponse
    {
        $validated = $request->validated();

        if (! empty($validated['remove_banner_image']) && $fleet->banner_image_path) {
            Storage::disk('public')->delete($fleet->banner_image_path);
            $validated['banner_image_path'] = null;
        }

        if ($request->hasFile('banner_image')) {
            if ($fleet->banner_image_path) {
                Storage::disk('public')->delete($fleet->banner_image_path);
            }

            $validated['banner_image_path'] = $request->file('banner_image')->store('fleets/banners', 'public');
        }

        if ($request->filled('delete_gallery_images')) {
            $deletedIds = array_map('intval', $request->input('delete_gallery_images', []));
            $images = $fleet->images()->whereIn('id', $deletedIds)->get();

            foreach ($images as $image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }
        }

        unset($validated['banner_image'], $validated['remove_banner_image'], $validated['delete_gallery_images']);

        $validated = $this->preparePayload($validated, null);

        $fleet->update($validated);
        $this->syncGallery($fleet, $request->file('gallery_images', []));
        $this->syncAmenities($fleet, $request->input('amenities', []));

        return redirect()->route('admin.fleets.index')->with('status', 'Fleet updated successfully.');
    }

    public function destroy(Fleet $fleet): RedirectResponse
    {
        if ($fleet->banner_image_path) {
            Storage::disk('public')->delete($fleet->banner_image_path);
        }

        foreach ($fleet->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $fleet->delete();

        return redirect()->route('admin.fleets.index')->with('status', 'Fleet deleted successfully.');
    }

    private function preparePayload(array $payload, $bannerImage = null): array
    {
        $payload['slug'] = $this->normalizeSlug($payload['slug'] ?? null, $payload['name']);
        $payload['pricing_config'] = $this->decodePricingConfig($payload['pricing_config'] ?? null);
        unset($payload['banner_image']);

        if ($bannerImage) {
            $payload['banner_image_path'] = $bannerImage->store('fleets/banners', 'public');
        }

        return $payload;
    }

    private function syncGallery(Fleet $fleet, array $galleryImages): void
    {
        if (empty($galleryImages)) {
            return;
        }

        foreach ($galleryImages as $index => $galleryImage) {
            $fleet->images()->create([
                'image_path' => $galleryImage->store('fleets/gallery', 'public'),
                'sort_order' => $index,
            ]);
        }
    }

    private function syncAmenities(Fleet $fleet, array $amenityIds): void
    {
        $ids = array_values(array_map('intval', $amenityIds));
        $fleet->amenities()->sync($ids);
    }

    private function normalizeSlug(?string $slug, string $fallbackTitle): string
    {
        $source = filled($slug) ? $slug : $fallbackTitle;

        return Str::slug($source);
    }

    private function decodePricingConfig(?string $pricingConfig): ?array
    {
        if (! filled($pricingConfig)) {
            return null;
        }

        $decoded = json_decode($pricingConfig, true);

        return is_array($decoded) ? $decoded : null;
    }
}
