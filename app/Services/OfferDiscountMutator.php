<?php

namespace App\Services;

use App\DTO\Order;
use App\Models\Offer;
use App\DTO\OrderItem;
use App\DTO\AppliedOffer;

class OfferDiscountMutator implements DiscountMutatorInterface
{
    public function __construct(
        private PartitionIntegerServiceInterface $partitionIntegerService
    )
    {}

    public function mutate(Order $order): void
    {
        $offers = $this->getOffersForOrderIndexedByProductId($order);
        foreach ($order->getOrderItems() as $orderItem) {
            if (isset($offers[$orderItem->getProductId()])) {
                $validPartitions = $this->getValidPartitionsOfProductCount(
                    $orderItem->getProductCount(),
                    $offers[$orderItem->getProductId()]
                );
                if ($validPartitions) {
                    $this->setOfferDiscount(
                        $orderItem,
                        $offers[$orderItem->getProductId()],
                        $validPartitions
                    );
                }
            }
        }
    }

    private function getOffersForOrderIndexedByProductId(Order $order): array
    {
        $query = Offer::query();
        foreach ($order->getOrderItems() as $orderItem) {
            $query->orWhere(function ($query) use ($orderItem) {
                $query
                    ->where('product_id', $orderItem->getProductId())
                    ->where('products_number', '<=', $orderItem->getProductCount());
            });
        }

        $offers = [];
        foreach ($query->get() as $offer) {
            $offers[$offer->product_id][$offer->products_number] = $offer;
        }

        return $offers;
    }

    private function getValidPartitionsOfProductCount(int $productCount, array $offers): array
    {
        $allPartitions = $this->partitionIntegerService->getPartitions($productCount);
        $allowedSlices = array_keys($offers);
        array_push($allowedSlices, 1);

        $validPartitions = [];
        foreach ($allPartitions as $partition) {
            if (!array_diff($partition, $allowedSlices)) {
                $validPartitions[] = $partition;
            }
        }

        return $validPartitions;
    }

    private function setOfferDiscount(OrderItem $orderItem, array $offers, array $partitions): void
    {
        $minPrice = INF;
        $bestAppliedOffers = [];
        foreach ($partitions as $partition) {
            $partitionPrice = $this->getPartionPrice(
                $partition,
                $offers,
                $orderItem->getProductPrice()
            );
            $partitionAppliedOffers = $this->getPartitionAppliedOffers(
                $partition,
                $offers
            );
            if ($partitionPrice < $minPrice) {
                $minPrice = $partitionPrice;
                $bestAppliedOffers = $partitionAppliedOffers;
            }
        }

        $priceWithoutOffer = $orderItem->getProductCount() * $orderItem->getProductPrice();
        $orderItem->addDiscount($priceWithoutOffer - $minPrice);
        $orderItem->setAppliedOffers($bestAppliedOffers);
    }

    private function getPartionPrice(array $partition, array $offers, int $productPrice): int
    {
        $price = 0;
        foreach ($partition as $productNumber) {
            if ($productNumber == 1) {
                $price += $productPrice;
            } else {
                $price += ($offers[$productNumber])->price;
            }
        }

        return $price;
    }

    private function getPartitionAppliedOffers(array $partition, array $offers): array
    {
        $appliedOffers = [];
        foreach ($partition as $productNumber) {
            if ($productNumber != 1) {
                $appliedOffers[] = new AppliedOffer(
                    $productNumber,
                    ($offers[$productNumber])->price
                );
            }
        }

        return $appliedOffers;
    }
}
