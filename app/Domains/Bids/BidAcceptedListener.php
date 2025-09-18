<?php

namespace App\Domains\Bids;

use App\Domains\Bids\BidAccepted;
use App\Domains\Shipments\Actions\CreateShipment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;

class BidAcceptedListener
{
    use Dispatchable, InteractsWithQueue, SerializesModels, ShouldQueue;

    public function handle(BidAccepted $event)
    {
        CreateShipment::dispatch($event->bid);
    }
}
