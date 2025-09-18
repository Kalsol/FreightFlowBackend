<?php


namespace App\Domains\Bids;

use App\Domains\Bids\Actions\CreateBidAction;
use App\Domains\Bids\Actions\DeleteBidAction;
use App\Domains\Bids\Actions\UpdateBidAction;
use App\Domains\Bids\Actions\GetBidAction;
use App\Domains\Bids\Actions\GetBidsAction;
use App\Domains\Bids\Actions\GetBidsByFreightAction;
use App\Domains\Bids\Actions\GetBidsByUserAction;
use App\Domains\Bids\Actions\GetBidsByFreightAndUserAction;
use App\Domains\Bids\BidRequest;
use Illuminate\Http\JsonResponse;

class BidsController
{
    public function create(BidRequest $request)
    {
        return CreateBidAction::execute($request->all());
    }

    public function update(UpdateBidRequest $request, string $uuid)
    {
        return UpdateBidAction::execute($request->all(), $uuid);
    }

    public function delete(string $uuid)
    {
        $deleteBidAction = new DeleteBidAction();
        return $deleteBidAction->execute($uuid);
    }

    public function get(string $uuid) : JsonResponse|array
    {
        $getBidAction = new GetBidAction();
        return $getBidAction->execute($uuid);
    }

    public function getAll()
    {
        $getBidsAction = new GetBidsAction();
        return $getBidsAction->execute();
    }

    public function getByFreight($freight_id)
    {
        $getBidsByFreightAction = new GetBidsByFreightAction();
        return $getBidsByFreightAction->execute($freight_id);
    }

    public function getByUser($user_id)
    {
        $getBidsByUserAction = new GetBidsByUserAction();
        return $getBidsByUserAction->execute($user_id);
    }

    public function getByFreightAndUser($freight_id, $user_id)
    {
        $getBidsByFreightAndUserAction = new GetBidsByFreightAndUserAction();
        return $getBidsByFreightAndUserAction->execute($freight_id, $user_id);
    }
}
