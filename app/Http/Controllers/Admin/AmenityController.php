<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAmenityRequest;
use App\Http\Requests\UpdateAmenityRequest;
use App\Models\Amenity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AmenityController extends Controller
{
    public function index(): View
    {
        return view('admin.amenities.index', [
            'amenities' => Amenity::query()->withCount('fleets')->latest()->get(),
            'amenityToEdit' => null,
        ]);
    }

    public function store(StoreAmenityRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->normalizeSlug($data['slug'] ?? null, $data['name']);

        Amenity::create($data);

        return redirect()->route('admin.amenities.index')->with('status', 'Amenity created successfully.');
    }

    public function edit(Amenity $amenity): View
    {
        return view('admin.amenities.index', [
            'amenities' => Amenity::query()->withCount('fleets')->latest()->get(),
            'amenityToEdit' => $amenity,
        ]);
    }

    public function update(UpdateAmenityRequest $request, Amenity $amenity): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->normalizeSlug($data['slug'] ?? null, $data['name']);

        $amenity->update($data);

        return redirect()->route('admin.amenities.index')->with('status', 'Amenity updated successfully.');
    }

    public function destroy(Amenity $amenity): RedirectResponse
    {
        $amenity->delete();

        return redirect()->route('admin.amenities.index')->with('status', 'Amenity deleted successfully.');
    }

    private function normalizeSlug(?string $slug, string $fallbackName): string
    {
        return Str::slug(filled($slug) ? $slug : $fallbackName);
    }
}
