<?php

namespace App\Http\Controllers;

use App\Mail\ContactEnquiryMail;
use App\Models\Booking;
use App\Models\Fleet;
use App\Models\Location;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FrontController extends Controller
{
    public function fleet_details(Request $request, Fleet $fleet)
    {
        $firstPriceOption = collect($fleet->pricing_config ?? [])->first() ?? [];
        $price = $firstPriceOption['price'] ?? $firstPriceOption['amount'] ?? 0;

        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json([
                'id' => $fleet->id,
                'slug' => $fleet->slug,
                'name' => $fleet->name,
                'category' => $fleet->category ?? 'Executive Sedan',
                'image' => $fleet->banner_image_path
                    ? Storage::disk('public')->url($fleet->banner_image_path)
                    : asset('images/vehicles/mercedes-s-class.jpg'),
                'price' => (float) $price,
                'passengers' => (int) ($fleet->passenger_capacity ?? 4),
                'luggage' => (int) ($fleet->luggage_capacity ?? 3),
            ]);
        }

        $fleet->load(['amenities', 'amenityFleet.amenity', 'images']);

        $features = $fleet->amenities
            ->map(function ($amenity) {
                return $amenity->toArray();
            })
            ->all();

        return view('fleet-details', [
            'fleet' => $fleet,
            'fleetFeatures' => $features,
            'fleetPrice' => (float) $price,
        ]);
    }

    public function service_details(Service $service)
    {
        $service->load('images');

        $matchingFleets = Fleet::query()
            ->orderBy('name')
            ->get()
            ->filter(function ($fleet) use ($service) {
                $serviceTitle = trim((string) ($service->title ?? ''));

                if ($serviceTitle === '') {
                    return false;
                }

                $plans = is_array($fleet->pricing_config ?? null) ? $fleet->pricing_config : [];

                return collect($plans)->contains(function ($plan) use ($serviceTitle) {
                    $planName = trim((string) ($plan['name'] ?? ''));

                    return strtolower($planName) === strtolower($serviceTitle);
                });
            })
            ->values();

        $matchingFleets = $matchingFleets->map(function ($fleet) use ($service) {
            $plans = is_array($fleet->pricing_config ?? null) ? $fleet->pricing_config : [];
            $matchedPlan = collect($plans)->first(function ($plan) use ($service) {
                $planName = trim((string) ($plan['name'] ?? ''));

                return strtolower($planName) === strtolower(trim((string) ($service->title ?? '')));
            });

            $fleet->matched_price = (float) ($matchedPlan['price'] ?? $matchedPlan['amount'] ?? 0);
            $fleet->matched_service_type = $service->service_type ?? 'Daily';

            return $fleet;
        });

        return view('service-details', [
            'service' => $service,
            'matchingFleets' => $matchingFleets,
        ]);
    }

    public function store_enquiry(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
        ]);

        $enquiry = \App\Models\Enquiry::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'subject' => $validated['subject'] ?? 'General enquiry',
            'message' => $validated['message'],
            'status' => 'new',
            'source' => 'contact',
        ]);

        Mail::to(env('ENQUIRY_TO_EMAIL', 'hello@royaledrive.com'))
            ->send(new ContactEnquiryMail(
                $enquiry->name,
                $enquiry->email,
                $enquiry->phone,
                $enquiry->subject,
                $enquiry->message,
            ));

        return redirect()->route('contact.us')->with('status', 'Your enquiry has been received. Our team will be in touch shortly.');
    }

    public function fleet_booking(Request $request): RedirectResponse
    {
        $returnTripEnabled = $request->boolean('return_trip');

        $validated = $request->validate([
            'pickup_location' => ['required', 'string', 'max:255'],
            'dropoff_location' => ['required', 'string', 'max:255'],
            'pickup_date' => ['required', 'date'],
            'pickup_time' => ['required', 'date_format:H:i'],
            'return_date' => ['required', 'date', 'after_or_equal:pickup_date'],
            'return_time' => ['required', 'date_format:H:i'],
            'passengers' => ['required', 'integer', 'min:1'],
            'luggage' => ['nullable', 'integer', 'min:0'],
            'fleet_id' => ['nullable', 'integer', 'exists:fleets,id'],
            'vehicle' => ['nullable', 'string', 'max:255'],
            'service_id' => ['nullable', 'integer', 'exists:services,id'],
            'flight_number' => ['nullable', 'string', 'max:50'],
            'special_requirements' => ['nullable', 'string', 'max:2000'],
        ]);

        if (! $returnTripEnabled) {
            $validated['return_date'] = null;
            $validated['return_time'] = null;
        }

        $pickupLocation = $this->resolveLocation($validated['pickup_location'], $validated['service_type'] ?? null, 'pickup');
        $dropoffLocation = $this->resolveLocation($validated['dropoff_location'], $validated['service_type'] ?? null, 'dropoff');

        $fleet = $this->resolveFleet($validated['fleet_id'] ?? $validated['vehicle'] ?? null);
        //$service = $this->resolveService($validated['service_type'] ?? null);

        $bookingNumber = $this->generateBookingNumber();

        $booking = Booking::create([
            'booking_number' => $bookingNumber,
            'name' => $request->input('name') ?: 'Guest Booking',
            'email' => $request->input('email') ?: null,
            'contact' => $request->input('contact') ?: null,
            'pickup_location_id' => $pickupLocation->id,
            'dropoff_location_id' => $dropoffLocation->id,
            'pickup_date' => $validated['pickup_date'],
            'pickup_time' => $validated['pickup_time'],
            'return_date' => $validated['return_date'] ?? null,
            'return_time' => $validated['return_time'] ?? null,
            'is_return_trip' => $returnTripEnabled,
            'passenger_count' => (int) $validated['passengers'],
            'luggage_count' => (int) ($validated['luggage'] ?? 0),
            'fleet_id' => $fleet?->id,
            'service_id' => $validated['service_id'] ?? 0,
            'status' => 'pending',
            'flight_number' => $validated['flight_number'] ?? null,
            'special_requests' => $validated['special_requirements'] ?? null,
        ]);

        return redirect()->route('booking.success', ['bookingNumber' => $booking->booking_number])
            ->with('status', 'Your booking request has been received.');
    }

    public function search_locations(Request $request)
    {
        $query = trim((string) $request->query('q', ''));
        $limit = max(5, min(12, (int) $request->query('limit', 10)));

        $locations = Location::query()
            ->where('is_active', true)
            ->when($query !== '', fn ($locationQuery) => $locationQuery->where('name', 'like', "%{$query}%"))
            ->orderBy('type')
            ->orderBy('name')
            ->limit($limit * 4)
            ->get();

        $grouped = [];

        foreach ($locations as $location) {
            $type = $location->type;
            $label = Location::typeOptions()[$type] ?? ucfirst(str_replace('_', ' ', $type));

            $grouped[$label][] = [
                'value' => $location->name,
                'name' => $location->name,
                'type' => $location->type,
            ];
        }

        return response()->json($grouped);
    }

    public function fleet_search(Request $request)
    {
        $passengers = max(1, (int) $request->query('passengers', 1));
        $pickupLocation = trim((string) $request->query('pickup_location', ''));
        $dropoffLocation = trim((string) $request->query('dropoff_location', ''));

        $fleets = Fleet::query()
            ->where('passenger_capacity', '>=', $passengers)
            ->when($request->filled('vehicle'), function ($query) use ($request) {
                $query->where(function ($innerQuery) use ($request) {
                    $innerQuery->where('name', 'like', '%' . trim((string) $request->query('vehicle')) . '%')
                        ->orWhere('category', 'like', '%' . trim((string) $request->query('vehicle')) . '%');
                });
            })
            ->where('availability_status', '!=', 'unavailable')
            ->orderBy('passenger_capacity')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('fleet-search', [
            'fleets' => $fleets,
            'pickupLocation' => $pickupLocation,
            'dropoffLocation' => $dropoffLocation,
            'passengers' => $passengers,
            'searchCriteria' => [
                'pickup_location' => $pickupLocation,
                'dropoff_location' => $dropoffLocation,
                'passengers' => $passengers,
            ],
        ]);
    }

    public function booking_success($bookingNumber = null)
    {
        $bookingNumber = $bookingNumber ?: session('booking_number');

        return view('booking-success', [
            'bookingNumber' => $bookingNumber,
        ]);
    }

    protected function generateBookingNumber(): string
    {
        do {
            $number = 'RD-' . date('Ymd') . '-' . str_pad((string) random_int(1000, 9999), 4, '0', STR_PAD_LEFT);
        } while (Booking::query()->where('booking_number', $number)->exists());

        return $number;
    }

    protected function resolveLocation(string $name, ?string $serviceType, string $context): Location
    {
        $cleanName = trim($name);
        $type = $this->detectLocationType($serviceType, $context);

        return Location::query()->firstOrCreate(
            ['name' => $cleanName],
            [
                'slug' => Str::slug($cleanName ?: $context . '-' . time()),
                'type' => $type,
                'is_active' => true,
            ]
        );
    }

    protected function resolveFleet($fleetIdentifier): ?Fleet
    {
        if (blank($fleetIdentifier)) {
            return Fleet::query()->first();
        }

        if (is_numeric($fleetIdentifier)) {
            return Fleet::query()->find((int) $fleetIdentifier) ?? Fleet::query()->first();
        }

        return Fleet::query()
            ->where('slug', $fleetIdentifier)
            ->orWhere('name', 'like', '%' . $fleetIdentifier . '%')
            ->first() ?? Fleet::query()->first();
    }

    protected function resolveService(?string $serviceType): ?Service
    {
        if (blank($serviceType)) {
            return Service::query()->first();
        }

        return Service::query()
            ->where('slug', $serviceType)
            ->orWhere('title', 'like', '%' . $serviceType . '%')
            ->first() ?? Service::query()->first();
    }

    protected function detectLocationType(?string $serviceType, string $context): string
    {
        $combined = strtolower((string) ($serviceType ?? ''));

        if (str_contains($combined, 'airport')) {
            return 'airport';
        }

        if ($context === 'dropoff') {
            return 'city';
        }

        return 'city';
    }
}
