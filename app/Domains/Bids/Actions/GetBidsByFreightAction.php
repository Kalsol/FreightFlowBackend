<?php

namespace App\Domains\Bids\Actions;

use App\Domains\Bids\Bid;

class GetBidsByFreightAction
{
    public function execute(int $freightId): array
    {
        return Bid::where('freight_id', $freightId)->get()->toArray();
    }
}