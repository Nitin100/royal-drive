<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePromotionPopupRequest;
use App\Http\Requests\UpdatePromotionPopupRequest;
use App\Models\PromotionPopup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PromotionPopupController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->datatable($request);
        }

        return view('admin.promotion-popups.index', [
            'popupToEdit' => null,
        ]);
    }

    public function store(StorePromotionPopupRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('promotion-popups', 'public');
        }

        unset($data['image']);

        PromotionPopup::create($data);

        return redirect()->route('admin.promotion-popups.index')->with('status', 'Promotion popup created successfully.');
    }

    public function edit(PromotionPopup $promotionPopup): View
    {
        return view('admin.promotion-popups.index', [
            'popupToEdit' => $promotionPopup,
            'promotionPopups' => PromotionPopup::query()->latest()->get(),
        ]);
    }

    public function update(UpdatePromotionPopupRequest $request, PromotionPopup $promotionPopup): RedirectResponse
    {
        $data = $request->validated();

        if (! empty($data['remove_image']) && $promotionPopup->image_path) {
            Storage::disk('public')->delete($promotionPopup->image_path);
            $data['image_path'] = null;
        }

        if ($request->hasFile('image')) {
            if ($promotionPopup->image_path) {
                Storage::disk('public')->delete($promotionPopup->image_path);
            }

            $data['image_path'] = $request->file('image')->store('promotion-popups', 'public');
        }

        unset($data['image'], $data['remove_image']);

        $promotionPopup->update($data);

        return redirect()->route('admin.promotion-popups.index')->with('status', 'Promotion popup updated successfully.');
    }

    public function destroy(PromotionPopup $promotionPopup): RedirectResponse
    {
        if ($promotionPopup->image_path) {
            Storage::disk('public')->delete($promotionPopup->image_path);
        }

        $promotionPopup->delete();

        return redirect()->route('admin.promotion-popups.index')->with('status', 'Promotion popup deleted successfully.');
    }

    private function datatable(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = max(0, (int) $request->input('start', 0));
        $length = (int) $request->input('length', 10);
        $searchValue = trim((string) $request->input('search.value', ''));
        $orderColumnIndex = (int) $request->input('order.0.column', 2);
        $orderDirection = strtolower((string) $request->input('order.0.dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        $columns = ['title', 'is_active', 'updated_at'];
        $orderColumn = $columns[$orderColumnIndex] ?? 'updated_at';

        $query = PromotionPopup::query();
        $recordsTotal = (clone $query)->count();

        if ($searchValue !== '') {
            $query->where(function ($builder) use ($searchValue) {
                $builder->where('title', 'like', "%{$searchValue}%")
                    ->orWhere('subtitle', 'like', "%{$searchValue}%")
                    ->orWhere('body', 'like', "%{$searchValue}%");
            });
        }

        $recordsFiltered = (clone $query)->count();

        $promotionPopups = $query->orderBy($orderColumn, $orderDirection)
            ->skip($start)
            ->take($length > 0 ? $length : 10)
            ->get();

        $data = $promotionPopups->map(function (PromotionPopup $popup) {
            $editUrl = route('admin.promotion-popups.edit', $popup);
            $deleteUrl = route('admin.promotion-popups.destroy', $popup);
            $csrf = csrf_token();

            return [
                'title' => e($popup->title),
                'status' => $popup->is_active
                    ? '<span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-medium text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300">Active</span>'
                    : '<span class="rounded-full bg-slate-200 px-2.5 py-1 text-xs font-medium text-slate-700 dark:bg-slate-700 dark:text-slate-200">Inactive</span>',
                'updated' => e($popup->updated_at?->diffForHumans() ?? '-'),
                'actions' => "<div class=\"flex items-center gap-3\">"
                    . "<a href=\"{$editUrl}\" class=\"text-indigo-600 hover:text-indigo-500 dark:text-indigo-300\">Edit</a>"
                    . "<form method=\"POST\" action=\"{$deleteUrl}\" onsubmit=\"return confirm('Delete this promotion popup?');\">"
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
