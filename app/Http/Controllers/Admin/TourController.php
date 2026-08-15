<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTourRequest;
use App\Http\Requests\UpdateTourRequest;
use App\Models\Tour;
use App\Models\TourCategory;
use App\Models\TourTag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TourController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->datatable($request);
        }

        return view('admin.tours.index', [
            'featuredOnly' => $request->boolean('featured'),
        ]);
    }

    public function featured(Request $request): View|JsonResponse
    {
        $request->merge(['featured' => '1']);

        return $this->index($request);
    }

    public function create(): View
    {
        return view('admin.tours.create', [
            'categories' => TourCategory::query()->orderBy('name')->get(),
            'tags' => TourTag::query()->orderBy('name')->get(),
        ]);
    }

    public function store(StoreTourRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $tour = Tour::create($this->preparePayload($validated, $request->file('featured_image')));
        $tour->categories()->sync($validated['category_ids'] ?? []);
        $tour->tags()->sync($validated['tag_ids'] ?? []);

        return redirect()->route('admin.tours.index')->with('status', 'Tour created successfully.');
    }

    public function edit(Tour $tour): View
    {
        $tour->load(['categories:id', 'tags:id']);

        return view('admin.tours.edit', [
            'tour' => $tour,
            'categories' => TourCategory::query()->orderBy('name')->get(),
            'tags' => TourTag::query()->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateTourRequest $request, Tour $tour): RedirectResponse
    {
        $validated = $request->validated();

        if (! empty($validated['remove_featured_image']) && $tour->featured_image_path) {
            Storage::disk('public')->delete($tour->featured_image_path);
            $validated['featured_image_path'] = null;
        }

        if ($request->hasFile('featured_image')) {
            if ($tour->featured_image_path) {
                Storage::disk('public')->delete($tour->featured_image_path);
            }

            $validated['featured_image_path'] = $request->file('featured_image')->store('tours/featured', 'public');
        }

        unset($validated['featured_image'], $validated['remove_featured_image']);

        $tour->update($this->preparePayload($validated, null));
        $tour->categories()->sync($validated['category_ids'] ?? []);
        $tour->tags()->sync($validated['tag_ids'] ?? []);

        return redirect()->route('admin.tours.index')->with('status', 'Tour updated successfully.');
    }

    public function destroy(Tour $tour): RedirectResponse
    {
        if ($tour->featured_image_path) {
            Storage::disk('public')->delete($tour->featured_image_path);
        }

        $tour->delete();

        return redirect()->route('admin.tours.index')->with('status', 'Tour deleted successfully.');
    }

    private function datatable(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = max(0, (int) $request->input('start', 0));
        $length = (int) $request->input('length', 10);
        $searchValue = trim((string) $request->input('search.value', ''));
        $orderColumnIndex = (int) $request->input('order.0.column', 5);
        $orderDirection = strtolower((string) $request->input('order.0.dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        $columns = ['title', null, null, 'is_featured', 'meta_title', 'updated_at'];
        $orderColumn = $columns[$orderColumnIndex] ?? 'updated_at';

        $query = Tour::query()->with(['categories:id,name', 'tags:id,name']);

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        $recordsTotal = (clone $query)->count();

        if ($searchValue !== '') {
            $query->where(function ($builder) use ($searchValue) {
                $builder->where('title', 'like', "%{$searchValue}%")
                    ->orWhere('slug', 'like', "%{$searchValue}%")
                    ->orWhere('meta_title', 'like', "%{$searchValue}%")
                    ->orWhereHas('categories', function ($categoryQuery) use ($searchValue) {
                        $categoryQuery->where('name', 'like', "%{$searchValue}%");
                    })
                    ->orWhereHas('tags', function ($tagQuery) use ($searchValue) {
                        $tagQuery->where('name', 'like', "%{$searchValue}%");
                    });
            });
        }

        $recordsFiltered = (clone $query)->count();

        if ($orderColumn !== null) {
            $query->orderBy($orderColumn, $orderDirection);
        } else {
            $query->latest();
        }

        $tours = $query->skip($start)
            ->take($length > 0 ? $length : 10)
            ->get();

        $data = $tours->map(function (Tour $tour) {
            $editUrl = route('admin.tours.edit', $tour);
            $deleteUrl = route('admin.tours.destroy', $tour);
            $csrf = csrf_token();

            $categoriesHtml = $tour->categories->isNotEmpty()
                ? $tour->categories->map(fn ($category) => '<span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-200">' . e($category->name) . '</span>')->implode(' ')
                : '<span class="text-xs text-gray-500 dark:text-gray-400">-</span>';

            $tagsHtml = $tour->tags->isNotEmpty()
                ? $tour->tags->map(fn ($tag) => '<span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-200">' . e($tag->name) . '</span>')->implode(' ')
                : '<span class="text-xs text-gray-500 dark:text-gray-400">-</span>';

            return [
                'title' => e($tour->title),
                'categories' => '<div class="flex flex-wrap gap-1">' . $categoriesHtml . '</div>',
                'tags' => '<div class="flex flex-wrap gap-1">' . $tagsHtml . '</div>',
                'featured' => $tour->is_featured
                    ? '<span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-medium text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300">Featured</span>'
                    : '<span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-300">Normal</span>',
                'meta_title' => e($tour->meta_title ?: '-'),
                'updated' => e($tour->updated_at?->diffForHumans() ?? '-'),
                'actions' => "<div class=\"flex items-center gap-3\">"
                    . "<a href=\"{$editUrl}\" class=\"text-indigo-600 hover:text-indigo-500 dark:text-indigo-300\">Edit</a>"
                    . "<form method=\"POST\" action=\"{$deleteUrl}\" onsubmit=\"return confirm('Delete this tour?');\">"
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

    private function preparePayload(array $payload, $featuredImage = null): array
    {
        $payload['slug'] = $this->normalizeSlug($payload['slug'] ?? null, $payload['title']);
        $payload['is_featured'] = (bool) ($payload['is_featured'] ?? false);
        unset($payload['featured_image'], $payload['remove_featured_image'], $payload['category_ids'], $payload['tag_ids']);

        if ($featuredImage) {
            $payload['featured_image_path'] = $featuredImage->store('tours/featured', 'public');
        }

        return $payload;
    }

    private function normalizeSlug(?string $slug, string $fallbackTitle): string
    {
        return Str::slug(filled($slug) ? $slug : $fallbackTitle);
    }
}