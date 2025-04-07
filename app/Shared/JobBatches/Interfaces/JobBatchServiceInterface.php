<?php

namespace App\Shared\JobBatches\Interfaces;

interface JobBatchServiceInterface
{
    public function getBatchStatus(string $batchID): array;
}
