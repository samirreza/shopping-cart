<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use App\Services\OrderService;
use App\Formatters\OrderFormatter;
use App\Http\Requests\OrderRequest;

class OrderController
{
    public function __construct(
        private OrderRequest $orderRequest,
        private OrderService $orderService,
        private OrderFormatter $orderFormatter
    )
    {}

    public function __invoke()
    {
        $orderData = $this->orderRequest->validated();
        $orderedProductIds = Arr::pluck($orderData['products'], 'id');
        $order = $this->orderService->order($orderedProductIds);
        dd($order, $this->orderService->getTotalPrice($order));
    }
}
