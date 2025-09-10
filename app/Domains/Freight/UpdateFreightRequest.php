<?php

namespace App\Domains\Freight;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFreightRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            // Using 'sometimes' so a field is only validated if it's present in the request.
            'origin_location' => ['sometimes', 'required', 'string', 'max:255'],
            'destination_location' => ['sometimes', 'required', 'string', 'max:255'],
            'origin_coord' => ['sometimes', 'nullable', 'string'],
            'dest_coord' => ['sometimes', 'nullable', 'string'],
            'pickup_date' => ['sometimes', 'nullable', 'date'],
            'delivery_date' => ['sometimes', 'nullable', 'date', 'after_or_equal:pickup_date'],
            'weight' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'weight_unit' => ['sometimes', 'nullable', 'string', 'max:50'],
            'dimensions' => ['sometimes', 'nullable', 'string', 'max:255'],
            'dimension_unit' => ['sometimes', 'nullable', 'string', 'max:50'],
            'freight_type' => ['sometimes', 'nullable', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'desired_price' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'special_instructions' => ['sometimes', 'nullable', 'string'],
            'bid_deadline' => ['sometimes', 'nullable', 'date'],
            'status' => ['sometimes', 'nullable', 'string', 'in:open,bidding_closed,assigned,in_transit,delivered,cancelled,draft'],
            'required_equipment' => ['sometimes', 'nullable', 'array'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'origin_location.required' => 'The origin location is required.',
            'origin_location.string' => 'The origin location must be a string.',
            'destination_location.required' => 'The destination location is required.',
            'destination_location.string' => 'The destination location must be a string.',
            'delivery_date.after_or_equal' => 'The delivery date must be on or after the pickup date.',
            'weight.numeric' => 'The weight must be a number.',
            'weight.min' => 'The weight must be a positive number.',
            'dimensions.string' => 'The dimensions must be a string.',
            'desired_price.numeric' => 'The desired price must be a number.',
            'desired_price.min' => 'The desired price must be a positive number.',
            'status.in' => 'The selected status is invalid.',
            'required_equipment.array' => 'The required equipment must be an array.',
        ];
    }
}
