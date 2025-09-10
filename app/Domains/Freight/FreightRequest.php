<?php

namespace App\Domains\Freight;

use Illuminate\Foundation\Http\FormRequest;

class FreightRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Set this to true to allow all users to make this request.
        // You can add more complex authorization logic here if needed,
        // for example, checking if the authenticated user has a specific role.
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
            'origin_location' => ['required', 'string', 'max:255'],
            'destination_location' => ['required', 'string', 'max:255'],
            'origin_coord' => ['nullable', 'string'],
            'dest_coord' => ['nullable', 'string'],
            'pickup_date' => ['nullable', 'date'],
            'delivery_date' => ['nullable', 'date', 'after_or_equal:pickup_date'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'weight_unit' => ['nullable', 'string', 'max:50'],
            'dimensions' => ['nullable', 'string', 'max:255'],
            'dimension_unit' => ['nullable', 'string', 'max:50'],
            'freight_type' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'desired_price' => ['nullable', 'numeric', 'min:0'],
            'special_instructions' => ['nullable', 'string'],
            'bid_deadline' => ['nullable', 'date'],
            'status' => ['nullable', 'string', 'in:open,bidding_closed,assigned,in_transit,delivered,cancelled,draft'],
            'required_equipment' => ['nullable', 'array'],
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
            'load_owner_id.required' => 'The load owner ID is required.',
            'load_owner_id.integer' => 'The load owner ID must be an integer.',
            'load_owner_id.exists' => 'The selected load owner does not exist.',
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
