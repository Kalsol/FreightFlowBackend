<?php

namespace App\Domains\Bids;

use App\Domains\Bids\Bid;

class BidAccepted
{
    public $bid;


    public function __construct(Bid $bid)
    {
        $this->bid = $bid;
    }
}
