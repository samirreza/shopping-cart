<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use App\Services\OrderService;
use App\Http\Requests\OrderRequest;

class OrderController
{
    public function __construct(
        private OrderRequest $orderRequest,
        private OrderService $orderService
    )
    {}

    public function __invoke()
    {
        $orderData = $this->orderRequest->validated();
        $productIds = Arr::pluck($orderData['products'], 'id');
        $order = $this->orderService->order($productIds);
    }
}
