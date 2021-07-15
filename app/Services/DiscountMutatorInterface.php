<?php

namespace App\Services;

use App\DTO\Order;

interface DiscountMutatorInterface
{
    public function mutate(Order $order): void;
}
