<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->datatable($request);
        }

        return view('admin.bookings.index', [
            'statusOptions' => Booking::statusOptions(),
        ]);
    }

    public function updateStatus(Request $request, Booking $booking): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(Booking::STATUSES)],
        ]);

        $booking->update([
            'status' => $validated['status'],
        ]);

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'message' => 'Booking status updated successfully.',
                'status' => $booking->status,
                'status_label' => Booking::statusOptions()[$booking->status] ?? Str::headline($booking->status),
            ]);
        }

        return redirect()->route('admin.bookings.index')->with('status', 'Booking status updated successfully.');
    }

    private function datatable(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = max(0, (int) $request->input('start', 0));
        $length = (int) $request->input('length', 10);
        $searchValue = trim((string) $request->input('search.value', ''));
        $orderColumnIndex = (int) $request->input('order.0.column', 2);
        $orderDirection = strtolower((string) $request->input('order.0.dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        $columns = ['pickup_date', 'pickup_time', 'status', 'flight_number', null, null, null, null, null, null, null];
        $orderColumn = $columns[$orderColumnIndex] ?? 'pickup_date';

        $query = Booking::query()->with(['pickupLocation:id,name', 'dropoffLocation:id,name', 'fleet:id,name', 'service:id,title']);
        $recordsTotal = (clone $query)->count();

        if ($searchValue !== '') {
            $query->where(function ($builder) use ($searchValue) {
                $builder->whereHas('pickupLocation', function ($locationQuery) use ($searchValue) {
                    $locationQuery->where('name', 'like', "%{$searchValue}%");
                })->orWhereHas('dropoffLocation', function ($locationQuery) use ($searchValue) {
                    $locationQuery->where('name', 'like', "%{$searchValue}%");
                })->orWhereHas('fleet', function ($fleetQuery) use ($searchValue) {
                    $fleetQuery->where('name', 'like', "%{$searchValue}%");
                })->orWhereHas('service', function ($serviceQuery) use ($searchValue) {
                    $serviceQuery->where('title', 'like', "%{$searchValue}%");
                })->orWhere('status', 'like', "%{$searchValue}%")
                ->orWhere('flight_number', 'like', "%{$searchValue}%");
            });
        }

        $recordsFiltered = (clone $query)->count();

        if ($orderColumn !== null) {
            $query->orderBy($orderColumn, $orderDirection);
        } else {
            $query->latest();
        }

        $bookings = $query->skip($start)
            ->take($length > 0 ? $length : 10)
            ->get();

        $statusOptions = Booking::statusOptions();

        $data = $bookings->map(function (Booking $booking) use ($statusOptions) {
            $status = $booking->status ?: 'pending';
            $statusLabel = $statusOptions[$status] ?? Str::headline($status);
            $statusClass = match ($status) {
                'confirmed', 'assigned', 'in_progress' => 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300',
                'completed' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300',
                'cancelled' => 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-300',
                default => 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-300',
            };

            return [
                'pickup_location' => e($booking->pickupLocation?->name ?? '-'),
                'dropoff_location' => e($booking->dropoffLocation?->name ?? '-'),
                'pickup_date' => e(optional($booking->pickup_date)->format('d M Y')),
                'pickup_time' => e($booking->pickup_time),
                'status' => '<span class="rounded-full px-2.5 py-1 text-xs font-medium ' . $statusClass . '">' . e($statusLabel) . '</span>',
                'fleet' => e($booking->fleet?->name ?? '-'),
                'service' => e($booking->service?->title ?? '-'),
                'summary' => e($booking->passenger_count . ' pax | ' . $booking->luggage_count . ' luggage'),
                'return_trip' => $booking->is_return_trip
                    ? '<span class="rounded-full bg-indigo-100 px-2.5 py-1 text-xs font-medium text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300">Yes</span>'
                    : '<span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-300">No</span>',
                'flight_number' => e($booking->flight_number ?: '-'),
                'special_requests' => e(Str::limit($booking->special_requests ?: '-', 60)),
                'actions' => '<button type="button"'
                    . ' class="rounded-lg bg-gray-900 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-gray-700 dark:bg-gray-100 dark:text-gray-900 dark:hover:bg-gray-200"'
                    . ' data-booking-status-trigger="true"'
                    . ' data-booking-id="' . $booking->id . '"'
                    . ' data-booking-label="' . e($booking->pickupLocation?->name ?? 'Booking #' . $booking->id) . '"'
                    . ' data-current-status="' . e($status) . '"'
                    . '>Update Status</button>',
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
