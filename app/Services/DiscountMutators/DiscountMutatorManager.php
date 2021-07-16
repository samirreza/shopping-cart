<?php

namespace App\Services\DiscountMutators;

use App\DTO\Order;

class DiscountMutatorManager
{
    private array $discountMutators;

    public function __construct(DiscountMutatorInterface ...$discountMutators)
    {
        $this->discountMutators = $discountMutators;
    }

    public function mutate(Order $order)
    {
        foreach ($this->discountMutators as $discountMutator) {
            $discountMutator->mutate($order);
        }
    }
}
