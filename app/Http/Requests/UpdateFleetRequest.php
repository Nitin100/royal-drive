<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFleetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $fleetId = $this->route('fleet')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('fleets', 'slug')->ignore($fleetId),
            ],
            'category' => ['required', 'string', 'max:100'],
            'brand' => ['required', 'string', 'max:255'],
            'passenger_capacity' => ['nullable', 'integer', 'min:1'],
            'luggage_capacity' => ['nullable', 'integer', 'min:0'],
            'availability_status' => ['required', 'in:available,unavailable,maintenance,booked'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'banner_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_banner_image' => ['nullable', 'boolean'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'delete_gallery_images' => ['nullable', 'array'],
            'delete_gallery_images.*' => ['integer'],
            'description' => ['nullable', 'string'],
            'pricing_config' => ['nullable', 'string'],
            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['integer', 'exists:amenities,id'],
        ];
    }
}
