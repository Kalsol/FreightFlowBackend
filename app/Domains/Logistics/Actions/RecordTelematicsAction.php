<?php

namespace App\Domains\Logistics\Actions;

use App\Domains\Logistics\Models\Telematics;

class RecordTelematicsAction
{
    public function execute(array $payload): Telematics
    {
        return Telematics::create($payload);
    }
}


