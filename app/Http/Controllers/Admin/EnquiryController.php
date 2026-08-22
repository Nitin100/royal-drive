<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EnquiryController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->datatable($request);
        }

        return view('admin.enquiries.index');
    }

    private function datatable(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = max(0, (int) $request->input('start', 0));
        $length = (int) $request->input('length', 10);
        $searchValue = trim((string) $request->input('search.value', ''));
        $orderColumnIndex = (int) $request->input('order.0.column', 5);
        $orderDirection = strtolower((string) $request->input('order.0.dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        $columns = ['name', 'email', 'phone', 'subject', 'source', 'status', 'created_at'];
        $orderColumn = $columns[$orderColumnIndex] ?? 'created_at';

        $query = Enquiry::query();
        $recordsTotal = (clone $query)->count();

        if ($searchValue !== '') {
            $query->where(function ($builder) use ($searchValue) {
                $builder->where('name', 'like', "%{$searchValue}%")
                    ->orWhere('email', 'like', "%{$searchValue}%")
                    ->orWhere('phone', 'like', "%{$searchValue}%")
                    ->orWhere('subject', 'like', "%{$searchValue}%")
                    ->orWhere('source', 'like', "%{$searchValue}%")
                    ->orWhere('message', 'like', "%{$searchValue}%")
                    ->orWhere('status', 'like', "%{$searchValue}%");
            });
        }

        $recordsFiltered = (clone $query)->count();

        $enquiries = $query->orderBy($orderColumn, $orderDirection)
            ->skip($start)
            ->take($length > 0 ? $length : 10)
            ->get();

        $data = $enquiries->map(function (Enquiry $enquiry) {
            $sourceText = $enquiry->source ? ucwords(str_replace(['-', '_'], ' ', $enquiry->source)) : 'Unknown';

            return [
                'name' => e($enquiry->name),
                'email' => e($enquiry->email),
                'phone' => e($enquiry->phone ?: '-'),
                'subject' => e($enquiry->subject ?: '-'),
                'source' => e($sourceText),
                'status' => '<span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-200">' . e(ucfirst($enquiry->status)) . '</span>',
                'received_at' => e($enquiry->created_at?->diffForHumans() ?? '-'),
                'message' => e($enquiry->message),
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
