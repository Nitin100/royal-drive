<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreChauffeurRequest;
use App\Http\Requests\UpdateChauffeurRequest;
use App\Models\Chauffeur;
use App\Models\ChauffeurAssignment;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ChauffeurController extends Controller
{
    private const AVAILABILITY_STATUSES = [
        'available' => 'Available',
        'unavailable' => 'Unavailable',
        'on-trip' => 'On Trip',
        'resting' => 'Resting',
    ];

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->datatable($request);
        }

        $chauffeurs = Chauffeur::query()->withCount('assignments')->latest()->paginate(10);

        return view('admin.chauffeurs.index', [
            'chauffeurs' => $chauffeurs,
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
        $columns = ['name', 'contact_number', 'experience_years', 'rating', 'availability_status', null];
        $orderColumn = $columns[$orderColumnIndex] ?? 'name';

        $query = Chauffeur::query()->withCount('assignments');
        $recordsTotal = (clone $query)->count();

        if ($searchValue !== '') {
            $query->where(function ($builder) use ($searchValue) {
                $builder->where('name', 'like', "%{$searchValue}%")
                    ->orWhere('contact_number', 'like', "%{$searchValue}%")
                    ->orWhere('availability_status', 'like', "%{$searchValue}%");
            });
        }

        $recordsFiltered = (clone $query)->count();

        if ($orderColumn !== null) {
            $query->orderBy($orderColumn, $orderDirection);
        } else {
            $query->latest();
        }

        $chauffeurs = $query->skip($start)
            ->take($length > 0 ? $length : 10)
            ->get();

        $data = $chauffeurs->map(function (Chauffeur $chauffeur) {
            $editUrl = route('admin.chauffeurs.edit', $chauffeur);
            $deleteUrl = route('admin.chauffeurs.destroy', $chauffeur);
            $csrf = csrf_token();

            return [
                'chauffeur' => e($chauffeur->name),
                'contact' => e($chauffeur->contact_number),
                'experience' => $chauffeur->experience_years ? $chauffeur->experience_years . ' years' : '-',
                'rating' => number_format((float) $chauffeur->rating, 2),
                'availability' => e(ucfirst(str_replace('-', ' ', $chauffeur->availability_status))),
                'assignments' => (string) ($chauffeur->assignments_count ?? 0),
                'actions' => "<div class=\"flex items-center gap-3\">"
                    . "<a href=\"{$editUrl}\" class=\"text-indigo-600 hover:text-indigo-500 dark:text-indigo-300\">Edit</a>"
                    . "<form method=\"POST\" action=\"{$deleteUrl}\" onsubmit=\"return confirm('Delete this chauffeur?');\">"
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
        return view('admin.chauffeurs.create', [
            'availabilityStatuses' => self::AVAILABILITY_STATUSES,
        ]);
    }

    public function store(StoreChauffeurRequest $request): RedirectResponse
    {
        $chauffeur = Chauffeur::create($this->preparePayload($request->validated(), $request->file('photo')));
        $this->storeAssignmentIfProvided($chauffeur, $request);

        return redirect()->route('admin.chauffeurs.index')->with('status', 'Chauffeur created successfully.');
    }

    public function edit(Chauffeur $chauffeur): View
    {
        $chauffeur->load('assignments');

        return view('admin.chauffeurs.edit', [
            'chauffeur' => $chauffeur,
            'availabilityStatuses' => self::AVAILABILITY_STATUSES,
        ]);
    }

    public function update(UpdateChauffeurRequest $request, Chauffeur $chauffeur): RedirectResponse
    {
        $validated = $request->validated();

        if (! empty($validated['remove_photo']) && $chauffeur->photo_path) {
            Storage::disk('public')->delete($chauffeur->photo_path);
            $validated['photo_path'] = null;
        }

        if ($request->hasFile('photo')) {
            if ($chauffeur->photo_path) {
                Storage::disk('public')->delete($chauffeur->photo_path);
            }

            $validated['photo_path'] = $request->file('photo')->store('chauffeurs/photos', 'public');
        }

        unset($validated['photo'], $validated['remove_photo']);

        $validated = $this->preparePayload($validated, null);
        $chauffeur->update($validated);

        $this->storeAssignmentIfProvided($chauffeur, $request);

        return redirect()->route('admin.chauffeurs.index')->with('status', 'Chauffeur updated successfully.');
    }

    public function destroy(Chauffeur $chauffeur): RedirectResponse
    {
        if ($chauffeur->photo_path) {
            Storage::disk('public')->delete($chauffeur->photo_path);
        }

        $chauffeur->delete();

        return redirect()->route('admin.chauffeurs.index')->with('status', 'Chauffeur deleted successfully.');
    }

    public function reports(): View
    {
        $chauffeurs = Chauffeur::query()->withCount(['assignments' => function ($query) {
            $query->where('status', 'completed');
        }])->with(['assignments' => function ($query) {
            $query->latest('service_date')->limit(5);
        }])->get();

        $reportCards = $chauffeurs->map(function (Chauffeur $chauffeur) {
            $completedTrips = $chauffeur->assignments_count;
            $ratings = max(1, (int) round($completedTrips / 2));

            return [
                'name' => $chauffeur->name,
                'completed_trips' => $completedTrips,
                'rating' => $chauffeur->rating,
                'availability_status' => $chauffeur->availability_status,
                'top_assignment' => $chauffeur->assignments->first()?->booking_reference,
            ];
        });

        return view('admin.chauffeurs.reports', [
            'reportCards' => $reportCards,
        ]);
    }

    public function calendar(): View
    {
        $chauffeurs = Chauffeur::query()->with('assignments')->latest()->get();
        $monthStart = now()->startOfMonth();
        $daysInMonth = $monthStart->daysInMonth;
        $calendarDays = collect(range(1, $daysInMonth))->map(function (int $day) use ($monthStart, $chauffeurs) {
            $date = (clone $monthStart)->setDay($day);
            $bookedChauffeurs = $chauffeurs->filter(function (Chauffeur $chauffeur) use ($date) {
                return $chauffeur->assignments->contains(fn (ChauffeurAssignment $assignment) => $assignment->service_date->isSameDay($date));
            })->pluck('name')->values();

            return [
                'date' => $date,
                'label' => $date->format('j'),
                'bookedChauffeurs' => $bookedChauffeurs,
            ];
        });

        return view('admin.chauffeurs.calendar', [
            'monthLabel' => $monthStart->format('F Y'),
            'calendarDays' => $calendarDays,
            'chauffeurs' => $chauffeurs,
        ]);
    }

    private function preparePayload(array $payload, $photo = null): array
    {
        $payload['slug'] = $this->normalizeSlug($payload['slug'] ?? null, $payload['name']);
        $payload['availability_calendar'] = $this->decodeAvailabilityCalendar($payload['availability_calendar'] ?? null);
        unset($payload['photo'], $payload['remove_photo'], $payload['assignment_booking_reference'], $payload['assignment_service_date'], $payload['assignment_pickup_time'], $payload['assignment_route_name'], $payload['assignment_distance_km'], $payload['assignment_notes']);

        if ($photo) {
            $payload['photo_path'] = $photo->store('chauffeurs/photos', 'public');
        }

        return $payload;
    }

    private function storeAssignmentIfProvided(Chauffeur $chauffeur, $request): void
    {
        if (! $request->filled('assignment_booking_reference')) {
            return;
        }

        ChauffeurAssignment::create([
            'chauffeur_id' => $chauffeur->id,
            'booking_reference' => $request->input('assignment_booking_reference'),
            'assigned_at' => Carbon::now(),
            'service_date' => $request->input('assignment_service_date') ?: Carbon::now()->toDateString(),
            'pickup_time' => $request->input('assignment_pickup_time'),
            'route_name' => $request->input('assignment_route_name'),
            'distance_km' => $request->input('assignment_distance_km'),
            'status' => 'assigned',
            'notes' => $request->input('assignment_notes'),
        ]);
    }

    private function normalizeSlug(?string $slug, string $fallbackName): string
    {
        return Str::slug(filled($slug) ? $slug : $fallbackName);
    }

    private function decodeAvailabilityCalendar(?string $availabilityCalendar): ?array
    {
        if (! filled($availabilityCalendar)) {
            return null;
        }

        $decoded = json_decode($availabilityCalendar, true);

        return is_array($decoded) ? $decoded : null;
    }
}
