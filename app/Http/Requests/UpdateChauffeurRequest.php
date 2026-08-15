<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateChauffeurRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $chauffeurId = $this->route('chauffeur')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'contact_number' => ['required', 'string', 'max:50'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('chauffeurs', 'slug')->ignore($chauffeurId),
            ],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_photo' => ['nullable', 'boolean'],
            'license_number' => ['nullable', 'string', 'max:100'],
            'license_expiry_date' => ['nullable', 'date'],
            'experience_years' => ['nullable', 'integer', 'min:0', 'max:80'],
            'languages_spoken' => ['nullable', 'string', 'max:255'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'availability_status' => ['required', 'in:available,unavailable,on-trip,resting'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'bio' => ['nullable', 'string'],
            'availability_calendar' => ['nullable', 'string'],
            'assignment_booking_reference' => ['nullable', 'string', 'max:100'],
            'assignment_service_date' => ['nullable', 'date'],
            'assignment_pickup_time' => ['nullable', 'date_format:H:i'],
            'assignment_route_name' => ['nullable', 'string', 'max:255'],
            'assignment_distance_km' => ['nullable', 'numeric', 'min:0'],
            'assignment_notes' => ['nullable', 'string'],
        ];
    }
}
