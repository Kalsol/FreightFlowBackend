<?php

namespace App\Domains\Bids\Actions;


use App\Domains\Bids\Bid;
use Illuminate\Http\JsonResponse;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Log;


class UpdateBidAction
{
    use ApiResponse;
    public static function execute(array $data, string $uuid): JsonResponse
    {
        try {
            $bid = Bid::where('uuid', $uuid)->firstOrFail();
            $bid->update($data);
            return (new self)->successResponse($bid, 'Bid updated successfully', 201);
        } catch (\Exception $e) {
            Log::error('Error updating bid: ' . $e->getMessage());
            return (new self)->errorResponse('Failed to updated bid', 500);
        }
    }
}
