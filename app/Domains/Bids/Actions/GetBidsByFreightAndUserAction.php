<?php 


namespace App\Domains\Bids\Actions;


use App\Domains\Bids\Bid;


class GetBidsByFreightAndUserAction
{
    public function execute(int $freightId, int $userId): array
    {
        return Bid::where('freight_id', $freightId)->where('user_id', $userId)->get()->toArray();
    }
}