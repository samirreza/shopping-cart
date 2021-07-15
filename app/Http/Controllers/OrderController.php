<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use App\Services\OrderService;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;

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
        $orderedProductIds = Arr::pluck($orderData['products'], 'id');
        $order = $this->orderService->order($orderedProductIds);

        return OrderResource::make($order);
    }
}
