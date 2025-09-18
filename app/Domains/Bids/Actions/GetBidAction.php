<?php

namespace App\Domains\Bids\Actions;

use App\Domains\Bids\Bid;
use Illuminate\Http\JsonResponse;
use App\Traits\ApiResponse;

class GetBidAction
{
    use ApiResponse;
    public function execute(string $uuid): JsonResponse|array
    {
       $bid =  Bid::where('uuid', $uuid)->first();
       if(!$bid){
            return $this->errorResponse('Bid not found', 404); 
       }
       return Bid::where('uuid', $uuid)->first()->toArray();
    }
}