<?php


namespace App\Domains\Bids\Actions;


use App\Domains\Bids\Bid;

class GetBidsByUserAction
{
    public function execute($user_id)
    {
        return Bid::where('user_id', $user_id)->get();
    }
}