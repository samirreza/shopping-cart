<?php

namespace App\Queries;

use App\DTO\Order;

interface OffersForOrderQueryInterface
{
    public function execute(Order $order): array;
}
