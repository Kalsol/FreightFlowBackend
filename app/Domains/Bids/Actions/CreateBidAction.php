<?php

namespace App\Domains\Bids\Actions;

use App\Domains\Bids\Bid;
use Illuminate\Http\JsonResponse;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Log;

class CreateBidAction
{
    use ApiResponse;

    public static function execute(array $data) : JsonResponse
    {

        try {
            $bid = Bid::create($data);
            return (new self)->successResponse($bid, 'Bid created successfully', 201);
        }
        catch (\Exception $e) {
            Log::error('Error creating bid: ' . $e->getMessage());
            return (new self)->errorResponse('Failed to create bid', 500);
        }
    }

}
