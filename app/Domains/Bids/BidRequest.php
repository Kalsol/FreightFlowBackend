<?php

namespace App\Domains\Bids;

use Illuminate\Foundation\Http\FormRequest;

class BidRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'freight_id' => 'required|exists:freights,id',
            'user_id' => 'required|exists:users,id',
            'bid_price' => 'required|numeric|between:0.01,999999.99',
            'estimated_delivery_time' => 'nullable|string',
            'notes' => 'nullable|string',
            'bid_strategy' => 'nullable|string',
            'fuel_surcharge_model' => 'nullable|string',
        ];
    }
    public function messages()
    {
        return [
            'freight_id.required' => 'Freight does not exist.',
            'freight_id.exists' => 'The selected Freight does not exist.',
            'user_id.required' => 'User does not exist.',
            'user_id.exists' => 'The selected User does not exist.',
            'bid_price.required' => 'Bid price is required.',
            'bid_price.numeric' => 'Bid price must be a number.',
            'bid_price.between' => 'Bid price must be between 0.01 and 999999.99.',
            'estimated_delivery_time.string' => 'Estimated delivery time must be a string.',
            'notes.string' => 'Notes must be a string.',
            'bid_strategy.string' => 'Bid strategy must be a string.',
            'fuel_surcharge_model.string' => 'Fuel surcharge model must be a string.',
        ];
    }
}