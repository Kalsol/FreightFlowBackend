<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FreightUpdateRequest extends FormRequest
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
            // The rules are now optional, allowing for partial updates
            'origin_location' => 'nullable|string|max:255',
            'destination_location' => 'nullable|string|max:255',
            'origin_coord' => 'nullable|string|max:255',
            'dest_coord' => 'nullable|string|max:255',
            'pickup_date' => 'nullable|date',
            'delivery_date' => 'nullable|date',
            'weight' => 'nullable|integer',
            'weight_unit' => 'nullable|string|in:kg,lb',
            'dimensions' => 'nullable|string|max:255',
            'dimension_unit' => 'nullable|string|in:cm,in',
            'freight_type' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'desired_price' => 'nullable|integer',
            'special_instructions' => 'nullable|string|max:255',
            'bid_deadline' => 'nullable|date',
            'status' => 'nullable|string|in:open,closed,assigned,in_transit,delivered,cancelled',
            'required_equipment' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        // Removed messages for 'required' as all fields are now nullable
        return [
            'origin_coord.string' => 'Origin coordinates must be a string.',
            'dest_coord.string' => 'Destination coordinates must be a string.',
            'description.max' => 'Description may not be greater than 255 characters.',
            'special_instructions.max' => 'Special instructions may not be greater than 255 characters.',
            'required_equipment.max' => 'Required equipment may not be greater than 255 characters.',
        ];
    }
}
