<?php

namespace App\Domains\Shipments\Enums;

enum ShipmentPriorityEnum: string
{
    case Low = 'low';
    case Normal = 'normal';
    case High = 'high';
    case Critical = 'critical';
}


