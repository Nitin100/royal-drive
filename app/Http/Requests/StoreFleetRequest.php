<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFleetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'unique:fleets,slug'],
            'category' => ['required', 'string', 'max:100'],
            'brand' => ['required', 'string', 'max:255'],
            'passenger_capacity' => ['nullable', 'integer', 'min:1'],
            'luggage_capacity' => ['nullable', 'integer', 'min:0'],
            'availability_status' => ['required', 'in:available,unavailable,maintenance,booked'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'banner_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'description' => ['nullable', 'string'],
            'pricing_config' => ['nullable', 'string'],
        ];
    }
}
