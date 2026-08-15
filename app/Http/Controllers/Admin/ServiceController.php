<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->datatable($request);
        }

        $services = Service::query()->withCount('images')->latest()->paginate(10);

        return view('admin.services.index', [
            'services' => $services,
        ]);
    }

    private function datatable(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = max(0, (int) $request->input('start', 0));
        $length = (int) $request->input('length', 10);
        $searchValue = trim((string) $request->input('search.value', ''));
        $orderColumnIndex = (int) $request->input('order.0.column', 4);
        $orderDirection = strtolower((string) $request->input('order.0.dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        $columns = ['title', 'slug', null, null, 'updated_at'];
        $orderColumn = $columns[$orderColumnIndex] ?? 'updated_at';

        $query = Service::query()->withCount('images');
        $recordsTotal = (clone $query)->count();

        if ($searchValue !== '') {
            $query->where(function ($builder) use ($searchValue) {
                $builder->where('title', 'like', "%{$searchValue}%")
                    ->orWhere('slug', 'like', "%{$searchValue}%")
                    ->orWhere('meta_title', 'like', "%{$searchValue}%")
                    ->orWhere('meta_description', 'like', "%{$searchValue}%");
            });
        }

        $recordsFiltered = (clone $query)->count();

        if ($orderColumn !== null) {
            $query->orderBy($orderColumn, $orderDirection);
        } else {
            $query->latest();
        }

        $services = $query->skip($start)
            ->take($length > 0 ? $length : 10)
            ->get();

        $data = $services->map(function (Service $service) {
            $editUrl = route('admin.services.edit', $service);
            $deleteUrl = route('admin.services.destroy', $service);
            $csrf = csrf_token();
            $pricingCount = is_array($service->pricing_config) ? count($service->pricing_config) : 0;

            return [
                'title' => e($service->title),
                'url' => '/services/' . e($service->slug),
                'pricing' => $pricingCount > 0 ? $pricingCount . ' plans' : '-',
                'gallery' => (string) ($service->images_count ?? 0),
                'updated' => e($service->updated_at?->diffForHumans() ?? '-'),
                'actions' => "<div class=\"flex items-center gap-3\">"
                    . "<a href=\"{$editUrl}\" class=\"text-indigo-600 hover:text-indigo-500 dark:text-indigo-300\">Edit</a>"
                    . "<form method=\"POST\" action=\"{$deleteUrl}\" onsubmit=\"return confirm('Delete this service?');\">"
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
        return view('admin.services.create');
    }

    public function store(StoreServiceRequest $request): RedirectResponse
    {
        $service = Service::create($this->preparePayload($request->validated(), $request->file('banner_image')));
        $this->syncGallery($service, $request->file('gallery_images', []));

        return redirect()->route('admin.services.index')->with('status', 'Service created successfully.');
    }

    public function edit(Service $service): View
    {
        $service->load('images');

        return view('admin.services.edit', [
            'service' => $service,
        ]);
    }

    public function update(UpdateServiceRequest $request, Service $service): RedirectResponse
    {
        $validated = $request->validated();

        if (! empty($validated['remove_banner_image']) && $service->banner_image_path) {
            Storage::disk('public')->delete($service->banner_image_path);
            $validated['banner_image_path'] = null;
        }

        if ($request->hasFile('banner_image')) {
            if ($service->banner_image_path) {
                Storage::disk('public')->delete($service->banner_image_path);
            }

            $validated['banner_image_path'] = $request->file('banner_image')->store('services/banners', 'public');
        }

        if ($request->filled('delete_gallery_images')) {
            $deletedIds = array_map('intval', $request->input('delete_gallery_images', []));
            $images = $service->images()->whereIn('id', $deletedIds)->get();

            foreach ($images as $image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }
        }

        unset($validated['banner_image'], $validated['remove_banner_image'], $validated['delete_gallery_images']);

        $validated = $this->preparePayload($validated, null);
        if (array_key_exists('banner_image_path', $validated) && $validated['banner_image_path'] === null) {
            $service->banner_image_path = null;
        }
        $service->update($validated);
        $this->syncGallery($service, $request->file('gallery_images', []));

        return redirect()->route('admin.services.index')->with('status', 'Service updated successfully.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        if ($service->banner_image_path) {
            Storage::disk('public')->delete($service->banner_image_path);
        }

        foreach ($service->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $service->delete();

        return redirect()->route('admin.services.index')->with('status', 'Service deleted successfully.');
    }

    private function preparePayload(array $payload, $bannerImage = null): array
    {
        $payload['slug'] = $this->normalizeSlug($payload['slug'] ?? null, $payload['title']);
        $payload['pricing_config'] = $this->decodePricingConfig($payload['pricing_config'] ?? null);
        unset($payload['banner_image']);

        if ($bannerImage) {
            $payload['banner_image_path'] = $bannerImage->store('services/banners', 'public');
        }

        return $payload;
    }

    private function syncGallery(Service $service, array $galleryImages): void
    {
        if (empty($galleryImages)) {
            return;
        }

        foreach ($galleryImages as $index => $galleryImage) {
            $service->images()->create([
                'image_path' => $galleryImage->store('services/gallery', 'public'),
                'sort_order' => $index,
            ]);
        }
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
