<?php

namespace App\Services\DiscountMutators;

use App\DTO\Order;

interface DiscountMutatorInterface
{
    public function mutate(Order $order): void;
}
