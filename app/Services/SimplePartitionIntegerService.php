<?php

namespace App\Services;

class SimplePartitionIntegerService implements PartitionIntegerServiceInterface
{
    public function getPartitions(int $integer): array
    {
        // An array to store
        // a partition
        $p[$integer] = array(0);

        // Index of last element
        // in a partition
        $k = 0;

        // Initialize first
        // partition as number
        // itself
        $p[$k] = $integer;

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
            if ($k < 0) return $result;

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
}
