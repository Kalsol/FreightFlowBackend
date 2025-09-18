<?php

namespace App\Domains\Bids;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBidRequest extends FormRequest
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
            'freight_id' => 'sometimes|requred|exists:freights,id',
            'user_id' => 'sometimes|required|exists:users,id',
            'bid_price' => 'sometimes|required|numeric|between:0.01,999999.99',
            'estimated_delivery_time' => 'sometimes|nullable|string',
            'notes' => 'sometimes|nullable|string',
            'bid_strategy' => 'sometimes|nullable|string',
            'fuel_surcharge_model' => 'sometimes|nullable|string',
        ];
    }
    public function messages()
    {
        return [
            'freight_id.' => 'Freight does not exist.',
            'freight_id.exists' => 'The selected Freight does not exist.',
            'user_id.' => 'User does not exist.',
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