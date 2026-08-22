<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSliderRequest;
use App\Http\Requests\UpdateSliderRequest;
use App\Models\Slider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SliderController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->datatable($request);
        }

        return view('admin.sliders.index');
    }

    public function create(): View
    {
        return view('admin.sliders.index');
    }

    public function store(StoreSliderRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('sliders', 'public');
        }

        unset($data['image']);

        Slider::create($data);

        return redirect()->route('admin.sliders.index')->with('status', 'Slider created successfully.');
    }

    public function edit(Slider $slider): View
    {
        return view('admin.sliders.index', [
            'sliderToEdit' => $slider,
        ]);
    }

    public function update(UpdateSliderRequest $request, Slider $slider): RedirectResponse
    {
        $data = $request->validated();

        if (! empty($data['remove_image']) && $slider->image_path) {
            Storage::disk('public')->delete($slider->image_path);
            $data['image_path'] = null;
        }

        if ($request->hasFile('image')) {
            if ($slider->image_path) {
                Storage::disk('public')->delete($slider->image_path);
            }

            $data['image_path'] = $request->file('image')->store('sliders', 'public');
        }

        unset($data['image'], $data['remove_image']);

        $slider->update($data);

        return redirect()->route('admin.sliders.index')->with('status', 'Slider updated successfully.');
    }

    public function destroy(Slider $slider): RedirectResponse
    {
        if ($slider->image_path) {
            Storage::disk('public')->delete($slider->image_path);
        }

        $slider->delete();

        return redirect()->route('admin.sliders.index')->with('status', 'Slider deleted successfully.');
    }

    private function datatable(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = max(0, (int) $request->input('start', 0));
        $length = (int) $request->input('length', 10);
        $searchValue = trim((string) $request->input('search.value', ''));
        $orderColumnIndex = (int) $request->input('order.0.column', 1);
        $orderDirection = strtolower((string) $request->input('order.0.dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        $columns = ['title', 'is_active', 'updated_at'];
        $orderColumn = $columns[$orderColumnIndex] ?? 'updated_at';

        $query = Slider::query();
        $recordsTotal = (clone $query)->count();

        if ($searchValue !== '') {
            $query->where(function ($builder) use ($searchValue) {
                $builder->where('title', 'like', "%{$searchValue}%")
                    ->orWhere('subtitle', 'like', "%{$searchValue}%")
                    ->orWhere('description', 'like', "%{$searchValue}%");
            });
        }

        $recordsFiltered = (clone $query)->count();

        $sliders = $query->orderBy($orderColumn, $orderDirection)
            ->skip($start)
            ->take($length > 0 ? $length : 10)
            ->get();

        $data = $sliders->map(function (Slider $slider) {
            $editUrl = route('admin.sliders.edit', $slider);
            $deleteUrl = route('admin.sliders.destroy', $slider);
            $csrf = csrf_token();

            return [
                'title' => e($slider->title),
                'status' => $slider->is_active
                    ? '<span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-medium text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300">Active</span>'
                    : '<span class="rounded-full bg-slate-200 px-2.5 py-1 text-xs font-medium text-slate-700 dark:bg-slate-700 dark:text-slate-200">Inactive</span>',
                'updated' => e($slider->updated_at?->diffForHumans() ?? '-'),
                'actions' => "<div class=\"flex items-center gap-3\">"
                    . "<a href=\"{$editUrl}\" class=\"text-indigo-600 hover:text-indigo-500 dark:text-indigo-300\">Edit</a>"
                    . "<form method=\"POST\" action=\"{$deleteUrl}\" onsubmit=\"return confirm('Delete this slider?');\">"
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
}
