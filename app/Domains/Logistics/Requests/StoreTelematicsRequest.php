<?php

namespace App\Domains\Logistics\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTelematicsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vehicle_id' => ['required', 'integer', 'exists:vehicles,id'],
            'timestamp' => ['required', 'date'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'speed' => ['nullable', 'numeric'],
            'heading' => ['nullable', 'numeric'],
            'altitude' => ['nullable', 'numeric'],
            'engine_temp' => ['nullable', 'numeric'],
            'fuel_level' => ['nullable', 'numeric'],
            'can_bus_data' => ['nullable', 'array'],
            'tire_pressure_data' => ['nullable', 'array'],
        ];
    }
}


