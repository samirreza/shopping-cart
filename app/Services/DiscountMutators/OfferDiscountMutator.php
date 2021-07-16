<?php

namespace App\Services\DiscountMutators;

use App\DTO\Order;
use App\DTO\OrderItem;
use App\DTO\AppliedOffer;
use App\Queries\OffersForOrderQueryInterface;
use App\Services\PartitionIntegerServiceInterface;

class OfferDiscountMutator implements DiscountMutatorInterface
{
    public function __construct(
        private PartitionIntegerServiceInterface $partitionIntegerService,
        private OffersForOrderQueryInterface $offersForOrderQuery
    )
    {}

    public function mutate(Order $order): void
    {
        $offers = $this->offersForOrderQuery->execute($order);
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
            if ($partitionPrice < $minPrice) {
                $minPrice = $partitionPrice;
                $bestAppliedOffers = $this->getPartitionAppliedOffers(
                    $partition,
                    $offers
                );
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
