<?php

namespace App\Domains\Bids\Actions;

use App\Domains\Bids\Bid;
use Illuminate\Http\JsonResponse;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Log;

class DeleteBidAction
{
    use ApiResponse; 
    public function execute(string $uuid): JsonResponse
    {
        try{
            $bid = Bid::where('uuid', $uuid)->firstOrFail();
            $bid->delete();
            return (new self)->successResponse([],'Bid deleted successfully', 201);
        }
        catch(\Exception $e){
            Log::error('Error deleting bid: ' . $e->getMessage());
            return (new self)->errorResponse('Bid failed to delete bid', 500);
        }
    }
}