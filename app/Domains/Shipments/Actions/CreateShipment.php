<?php

namespace App\Domains\Shipments\Actions;

use App\Domains\Shipments\Shipment;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateShipment
{
    use Dispatchable;

    protected $bid;

    public function __construct($bid)
    {
        $this->bid = $bid;
    }

    public function handle()
    {
        return Shipment::create([
            'bid_id' => $this->bid->id,
            'user_id' => $this->bid->user->id,
            'status' => Shipment::STATUS_PENDING,
        ]);
    }
}
