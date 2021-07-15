<?php

namespace App\Services;

interface PartitionIntegerServiceInterface
{
    public function getPartitions(int $integer): array;
}
