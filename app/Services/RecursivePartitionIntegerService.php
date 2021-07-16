<?php

namespace App\Services;

class RecursivePartitionIntegerService implements PartitionIntegerServiceInterface
{
    public function getPartitions(int $integer): array
    {
        $result = [];
        $this->partitionRecursively($integer, $integer, $result);

        return $result;
    }

    private function partitionRecursively(int $integer, int $maxValue, &$result, $rowResult = [])
    {
        if ($integer == 0) {
            $result[] = $rowResult;
        } else {
            if ($maxValue > 1) {
                $this->partitionRecursively($integer, $maxValue - 1, $result, $rowResult);
            }
            if ($maxValue <= $integer) {
                array_push($rowResult, $maxValue);
                $this->partitionRecursively($integer - $maxValue, $maxValue, $result, $rowResult);
            }
        }
    }
}
