<?php

namespace App\Services;

use App\DTO\Order;
use App\Models\Offer;
use App\DTO\OrderItem;
use App\DTO\AppliedOffer;

class OrderPriceCalculatorServiceBasedOnOffers
{
    public function calculate(Order $order)
    {
        $offers = $this->getAllowedOffersForOrderIndexedByProductId($order);
        foreach ($order->getOrderItems() as $orderItem) {
            if (isset($offers[$orderItem->getProduct()->id])) {
                $validCombinations = $this->getValidCombinations(
                    $orderItem,
                    $offers[$orderItem->getProduct()->id]
                );
                $this->manuplateOrderItem(
                    $orderItem,
                    $validCombinations,
                    $offers[$orderItem->getProduct()->id]
                );
            } else {
                $orderItem->setTotalPrice(
                    $orderItem->getProduct()->price * $orderItem->getCount()
                );
            }

            $order->addTotalPrice($orderItem->getTotalPrice());
            $order->addDiscount($orderItem->getDiscount());
        }
    }

    private function getAllowedOffersForOrderIndexedByProductId(Order $order): array
    {
        $query = Offer::query();
        foreach ($order->getOrderItems() as $orderItem) {
            $query->orWhere(function ($query) use ($orderItem) {
                $query
                    ->where('product_id', $orderItem->getProduct()->id)
                    ->where('products_number', '<=', $orderItem->getCount());
            });
        }

        $offers = [];
        foreach ($query->get() as $offer) {
            $offers[$offer->product_id][$offer->products_number] = $offer;
        }

        return $offers;
    }

    public function getValidCombinations(OrderItem $orderItem, array $productOffers): array
    {
        $productCount = $orderItem->getCount();
        $offersProductsNumbers = array_keys($productOffers);
        $possibleCombinationsForProductCount = [];
        $this->getUniquePartitionsOfAnInteger($productCount, $possibleCombinationsForProductCount);
        array_push($offersProductsNumbers, 1);
        $validCombinations = [];
        foreach ($possibleCombinationsForProductCount as $key => $possibleCombination) {
            if (!array_diff($possibleCombination, $offersProductsNumbers)) {
                $validCombinations[] = $possibleCombination;
            }
        }

        return $validCombinations;
    }

    public function getUniquePartitionsOfAnInteger($n, &$result)
    {
        // An array to store
        // a partition
        $p[$n] = array(0);

        // Index of last element
        // in a partition
        $k = 0;

        // Initialize first
        // partition as number
        // itself
        $p[$k] = $n;

        // This loop first prints
        // current partition, then
        // generates next partition.
        // The loop stops when the
        // current partition has all 1s
        $index = 0;
        while (true)
        {
            // print current partition
            for ($ii = 0; $ii < $k + 1; $ii++) {
                $result[$index][] = $p[$ii];
            }
            $index++;

            // Generate next partition

            // Find the rightmost non-one
            // value in p[]. Also, update
            // the rem_val so that we know
            // how much value can be accommodated
            $rem_val = 0;
            while ($k >= 0 && $p[$k] == 1)
            {
                $rem_val += $p[$k];
                $k--;
            }

            // if k < 0, all the values
            // are 1 so there are no
            // more partitions
            if ($k < 0) return;

            // Decrease the p[k] found
            // above and adjust the
            // rem_val
            $p[$k]--;
            $rem_val++;


            // If rem_val is more, then
            // the sorted order is violated.
            // Divide rem_val in different
            // values of size p[k] and copy
            // these values at different
            // positions after p[k]
            while ($rem_val > $p[$k])
            {
                $p[$k + 1] = $p[$k];
                $rem_val = $rem_val - $p[$k];
                $k++;
            }

            // Copy rem_val to next
            // position and increment
            // position
            $p[$k + 1] = $rem_val;
            $k++;
        }
    }

    private function manuplateOrderItem(
        OrderItem $orderItem,
        array $validCombinations,
        array $productOffers
    ) {
        $minPrice = INF;
        $appliedOffers = [];
        $totalPriceWithoutOffer = $orderItem->getProduct()->price * $orderItem->getCount();
        foreach ($validCombinations as $validCombination) {
            $totalPrice = 0;
            $tempAppliedOffers = [];
            foreach ($validCombination as $productsNumber) {
                if ($productsNumber == 1) {
                    $price = $orderItem->getProduct()->price;
                } else {
                    $offer = $productOffers[$productsNumber];
                    $price = $offer->price;
                    $tempAppliedOffers[] = new AppliedOffer(
                        $productsNumber,
                        $price
                    );
                }
                $totalPrice += $price;
            }
            if ($totalPrice < $minPrice) {
                $minPrice = $totalPrice;
                $appliedOffers = $tempAppliedOffers;
            }
        }

        $orderItem->setTotalPrice($minPrice);
        $orderItem->setAppliedOffers($appliedOffers);
        $orderItem->setDiscount($totalPriceWithoutOffer - $minPrice);
    }
}
