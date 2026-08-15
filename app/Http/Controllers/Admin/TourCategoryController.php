<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTourCategoryRequest;
use App\Http\Requests\UpdateTourCategoryRequest;
use App\Models\TourCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TourCategoryController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->datatable($request);
        }

        return view('admin.tour-categories.index', [
            'categories' => TourCategory::query()->withCount('tours')->latest()->get(),
            'categoryToEdit' => null,
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
        $columns = ['name', 'slug', null];
        $orderColumn = $columns[$orderColumnIndex] ?? 'name';

        $query = TourCategory::query()->withCount('tours');
        $recordsTotal = (clone $query)->count();

        if ($searchValue !== '') {
            $query->where(function ($builder) use ($searchValue) {
                $builder->where('name', 'like', "%{$searchValue}%")
                    ->orWhere('slug', 'like', "%{$searchValue}%");
            });
        }

        $recordsFiltered = (clone $query)->count();

        if ($orderColumn !== null) {
            $query->orderBy($orderColumn, $orderDirection);
        } else {
            $query->latest();
        }

        $categories = $query->skip($start)
            ->take($length > 0 ? $length : 10)
            ->get();

        $data = $categories->map(function (TourCategory $category) {
            $editUrl = route('admin.tour-categories.edit', $category);
            $deleteUrl = route('admin.tour-categories.destroy', $category);
            $csrf = csrf_token();

            return [
                'name' => e($category->name),
                'slug' => e($category->slug),
                'tours' => (string) $category->tours_count,
                'actions' => "<div class=\"flex items-center gap-3\">"
                    . "<a href=\"{$editUrl}\" class=\"text-indigo-600 hover:text-indigo-500 dark:text-indigo-300\">Edit</a>"
                    . "<form method=\"POST\" action=\"{$deleteUrl}\" onsubmit=\"return confirm('Delete this category?');\">"
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

    public function store(StoreTourCategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->normalizeSlug($data['slug'] ?? null, $data['name']);

        TourCategory::create($data);

        return redirect()->route('admin.tour-categories.index')->with('status', 'Category created successfully.');
    }

    public function edit(TourCategory $tourCategory): View
    {
        return view('admin.tour-categories.index', [
            'categories' => TourCategory::query()->withCount('tours')->latest()->get(),
            'categoryToEdit' => $tourCategory,
        ]);
    }

    public function update(UpdateTourCategoryRequest $request, TourCategory $tourCategory): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->normalizeSlug($data['slug'] ?? null, $data['name']);

        $tourCategory->update($data);

        return redirect()->route('admin.tour-categories.index')->with('status', 'Category updated successfully.');
    }

    public function destroy(TourCategory $tourCategory): RedirectResponse
    {
        $tourCategory->delete();

        return redirect()->route('admin.tour-categories.index')->with('status', 'Category deleted successfully.');
    }

    private function normalizeSlug(?string $slug, string $fallbackName): string
    {
        return Str::slug(filled($slug) ? $slug : $fallbackName);
    }
}