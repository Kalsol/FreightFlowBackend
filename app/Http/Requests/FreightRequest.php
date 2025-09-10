<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FreightRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Removed 'load_owner_id' from the validation rules as it will be set by the authenticated user.
            'origin_location' => 'required|string|max:255',
            'destination_location' => 'required|string|max:255',
            'origin_coord' => '|string|max:255',
            'dest_coord' => '|string|max:255',
            'pickup_date' => 'required|date',
            'delivery_date' => 'required|date',
            'weight' => 'required|integer',
            'weight_unit' => 'required|string|in:kg,lb',
            'dimensions' => 'required|string|max:255',
            'dimension_unit' => 'required|string|in:cm,in',
            'freight_type' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'desired_price' => 'required|integer',
            'special_instructions' => 'nullable|string|max:255',
            'bid_deadline' => 'required|date',
            'status' => 'required|string|in:open,closed,assigned,in_transit,delivered,cancelled',
            'required_equipment' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'origin_location.required' => 'Origin location is required.',
            'destination_location.required' => 'Destination location is required.',
            'origin_coord.string' => 'Origin coordinates must be a string.',
            'dest_coord.string' => 'Destination coordinates must be a string.',
            'pickup_date.required' => 'Pickup date is required.',
            'delivery_date.required' => 'Delivery date is required.',
            'weight.required' => 'Weight is required.',
            'weight_unit.required' => 'Weight unit is required.',
            'dimensions.required' => 'Dimensions are required.',
            'dimension_unit.required' => 'Dimension unit is required.',
            'freight_type.required' => 'Freight type is required.',
            'description.max' => 'Description may not be greater than 255 characters.',
            'desired_price.required' => 'Desired price is required.',
            'special_instructions.max' => 'Special instructions may not be greater than 255 characters.',
            'bid_deadline.required' => 'Bid deadline is required.',
            'status.required' => 'Status is required.',
            'required_equipment.max' => 'Required equipment may not be greater than 255 characters.',
        ];
    }
}