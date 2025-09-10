<?php

namespace App\Enum;

enum FreightStatus
{
    //
    const OPEN = 'open'; 
    const CLOSED = 'bidding_closed';
    const ASSIGNED = 'assigned';
    const IN_TRANSIT = 'in_transit';
    const DELIVERED = 'delivered';
    const CANCELLED = 'cancelled';
    const DRAFT = 'draft';
}
