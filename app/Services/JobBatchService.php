<?php

namespace App\Services;


interface JobBatchService
{
    public function getBatchStatus(string $batchID): array;
}
