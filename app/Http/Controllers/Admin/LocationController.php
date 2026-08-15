<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLocationRequest;
use App\Http\Requests\UpdateLocationRequest;
use App\Models\Location;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LocationController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->datatable($request);
        }

        return view('admin.locations.index');
    }

    public function create(): View
    {
        return view('admin.locations.create', [
            'typeOptions' => Location::typeOptions(),
        ]);
    }

    public function store(StoreLocationRequest $request): RedirectResponse
    {
        Location::create($this->preparePayload($request->validated()));

        return redirect()->route('admin.locations.index')->with('status', 'Location created successfully.');
    }

    public function edit(Location $location): View
    {
        return view('admin.locations.edit', [
            'location' => $location,
            'typeOptions' => Location::typeOptions(),
        ]);
    }

    public function update(UpdateLocationRequest $request, Location $location): RedirectResponse
    {
        $location->update($this->preparePayload($request->validated()));

        return redirect()->route('admin.locations.index')->with('status', 'Location updated successfully.');
    }

    public function destroy(Location $location): RedirectResponse
    {
        $location->delete();

        return redirect()->route('admin.locations.index')->with('status', 'Location deleted successfully.');
    }

    private function datatable(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = max(0, (int) $request->input('start', 0));
        $length = (int) $request->input('length', 10);
        $searchValue = trim((string) $request->input('search.value', ''));
        $orderColumnIndex = (int) $request->input('order.0.column', 3);
        $orderDirection = strtolower((string) $request->input('order.0.dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        $columns = ['name', 'type', 'is_active', 'updated_at', null];
        $orderColumn = $columns[$orderColumnIndex] ?? 'updated_at';

        $query = Location::query();
        $recordsTotal = (clone $query)->count();

        if ($searchValue !== '') {
            $query->where(function ($builder) use ($searchValue) {
                $builder->where('name', 'like', "%{$searchValue}%")
                    ->orWhere('slug', 'like', "%{$searchValue}%")
                    ->orWhere('type', 'like', "%{$searchValue}%");
            });
        }

        $recordsFiltered = (clone $query)->count();

        if ($orderColumn !== null) {
            $query->orderBy($orderColumn, $orderDirection);
        } else {
            $query->latest();
        }

        $locations = $query->skip($start)
            ->take($length > 0 ? $length : 10)
            ->get();

        $typeOptions = Location::typeOptions();

        $data = $locations->map(function (Location $location) use ($typeOptions) {
            $editUrl = route('admin.locations.edit', $location);
            $deleteUrl = route('admin.locations.destroy', $location);
            $csrf = csrf_token();

            return [
                'name' => e($location->name),
                'type' => e($typeOptions[$location->type] ?? Str::headline($location->type)),
                'status' => $location->is_active
                    ? '<span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-medium text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300">Active</span>'
                    : '<span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-300">Inactive</span>',
                'updated' => e($location->updated_at?->diffForHumans() ?? '-'),
                'actions' => "<div class=\"flex items-center gap-3\">"
                    . "<a href=\"{$editUrl}\" class=\"text-indigo-600 hover:text-indigo-500 dark:text-indigo-300\">Edit</a>"
                    . "<form method=\"POST\" action=\"{$deleteUrl}\" onsubmit=\"return confirm('Delete this location?');\">"
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

    private function preparePayload(array $payload): array
    {
        $payload['slug'] = $this->normalizeSlug($payload['slug'] ?? null, $payload['name']);
        $payload['is_active'] = (bool) ($payload['is_active'] ?? false);

        return $payload;
    }

    private function normalizeSlug(?string $slug, string $fallbackName): string
    {
        return Str::slug(filled($slug) ? $slug : $fallbackName);
    }
}
