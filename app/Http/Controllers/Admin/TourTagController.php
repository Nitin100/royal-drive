<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTourTagRequest;
use App\Http\Requests\UpdateTourTagRequest;
use App\Models\TourTag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TourTagController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->datatable($request);
        }

        return view('admin.tour-tags.index', [
            'tags' => TourTag::query()->withCount('tours')->latest()->get(),
            'tagToEdit' => null,
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

        $query = TourTag::query()->withCount('tours');
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

        $tags = $query->skip($start)
            ->take($length > 0 ? $length : 10)
            ->get();

        $data = $tags->map(function (TourTag $tag) {
            $editUrl = route('admin.tour-tags.edit', $tag);
            $deleteUrl = route('admin.tour-tags.destroy', $tag);
            $csrf = csrf_token();

            return [
                'name' => e($tag->name),
                'slug' => e($tag->slug),
                'tours' => (string) $tag->tours_count,
                'actions' => "<div class=\"flex items-center gap-3\">"
                    . "<a href=\"{$editUrl}\" class=\"text-indigo-600 hover:text-indigo-500 dark:text-indigo-300\">Edit</a>"
                    . "<form method=\"POST\" action=\"{$deleteUrl}\" onsubmit=\"return confirm('Delete this tag?');\">"
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

    public function store(StoreTourTagRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->normalizeSlug($data['slug'] ?? null, $data['name']);

        TourTag::create($data);

        return redirect()->route('admin.tour-tags.index')->with('status', 'Tag created successfully.');
    }

    public function edit(TourTag $tourTag): View
    {
        return view('admin.tour-tags.index', [
            'tags' => TourTag::query()->withCount('tours')->latest()->get(),
            'tagToEdit' => $tourTag,
        ]);
    }

    public function update(UpdateTourTagRequest $request, TourTag $tourTag): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->normalizeSlug($data['slug'] ?? null, $data['name']);

        $tourTag->update($data);

        return redirect()->route('admin.tour-tags.index')->with('status', 'Tag updated successfully.');
    }

    public function destroy(TourTag $tourTag): RedirectResponse
    {
        $tourTag->delete();

        return redirect()->route('admin.tour-tags.index')->with('status', 'Tag deleted successfully.');
    }

    private function normalizeSlug(?string $slug, string $fallbackName): string
    {
        return Str::slug(filled($slug) ? $slug : $fallbackName);
    }
}