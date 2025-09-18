<?php 

namespace App\Domains\Bids\Actions;


use App\Domains\Bids\Bid;

class GetBidsAction
{
    public function execute(): array
    {
        return Bid::all()->toArray();
    }
}

