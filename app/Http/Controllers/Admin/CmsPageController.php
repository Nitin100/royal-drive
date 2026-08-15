<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePageRequest;
use App\Http\Requests\UpdatePageRequest;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CmsPageController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->datatable($request);
        }

        $pages = Page::query()->latest()->paginate(10);

        return view('admin.pages.index', [
            'pages' => $pages,
        ]);
    }

    private function datatable(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = max(0, (int) $request->input('start', 0));
        $length = (int) $request->input('length', 10);
        $searchValue = trim((string) $request->input('search.value', ''));
        $orderColumnIndex = (int) $request->input('order.0.column', 3);
        $orderDirection = strtolower((string) $request->input('order.0.dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        $columns = ['title', 'slug', 'meta_title', 'updated_at'];
        $orderColumn = $columns[$orderColumnIndex] ?? 'updated_at';

        $query = Page::query();
        $recordsTotal = (clone $query)->count();

        if ($searchValue !== '') {
            $query->where(function ($builder) use ($searchValue) {
                $builder->where('title', 'like', "%{$searchValue}%")
                    ->orWhere('slug', 'like', "%{$searchValue}%")
                    ->orWhere('meta_title', 'like', "%{$searchValue}%");
            });
        }

        $recordsFiltered = (clone $query)->count();

        $pages = $query->orderBy($orderColumn, $orderDirection)
            ->skip($start)
            ->take($length > 0 ? $length : 10)
            ->get();

        $data = $pages->map(function (Page $page) {
            $editUrl = route('admin.pages.edit', $page);
            $viewUrl = route('pages.show', $page->slug);
            $deleteUrl = route('admin.pages.destroy', $page);
            $csrf = csrf_token();

            return [
                'title' => e($page->title),
                'url' => '/pages/' . e($page->slug),
                'meta_title' => e($page->meta_title ?: '-'),
                'updated' => e($page->updated_at?->diffForHumans() ?? '-'),
                'actions' => "<div class=\"flex items-center gap-3\">"
                    . "<a href=\"{$editUrl}\" class=\"text-indigo-600 hover:text-indigo-500 dark:text-indigo-300\">Edit</a>"
                    . "<a href=\"{$viewUrl}\" target=\"_blank\" class=\"text-gray-600 hover:text-gray-500 dark:text-gray-300\">View</a>"
                    . "<form method=\"POST\" action=\"{$deleteUrl}\" onsubmit=\"return confirm('Delete this page?');\">"
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
        return view('admin.pages.create');
    }

    public function store(StorePageRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->normalizeSlug($data['slug'] ?? null, $data['title']);

        if ($request->hasFile('banner_image')) {
            $data['banner_image_path'] = $request->file('banner_image')->store('pages/banners', 'public');
        }

        unset($data['banner_image']);

        Page::create($data);

        return redirect()->route('admin.pages.index')->with('status', 'Page created successfully.');
    }

    public function edit(Page $page): View
    {
        return view('admin.pages.edit', [
            'page' => $page,
        ]);
    }

    public function update(UpdatePageRequest $request, Page $page): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->normalizeSlug($data['slug'] ?? null, $data['title']);

        if (! empty($data['remove_banner_image']) && $page->banner_image_path) {
            Storage::disk('public')->delete($page->banner_image_path);
            $data['banner_image_path'] = null;
        }

        if ($request->hasFile('banner_image')) {
            if ($page->banner_image_path) {
                Storage::disk('public')->delete($page->banner_image_path);
            }

            $data['banner_image_path'] = $request->file('banner_image')->store('pages/banners', 'public');
        }

        unset($data['banner_image'], $data['remove_banner_image']);

        $page->update($data);

        return redirect()->route('admin.pages.index')->with('status', 'Page updated successfully.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        if ($page->banner_image_path) {
            Storage::disk('public')->delete($page->banner_image_path);
        }

        $page->delete();

        return redirect()->route('admin.pages.index')->with('status', 'Page deleted successfully.');
    }

    private function normalizeSlug(?string $slug, string $fallbackTitle): string
    {
        $source = filled($slug) ? $slug : $fallbackTitle;

        return Str::slug($source);
    }
}
