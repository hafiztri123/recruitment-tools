<?php

namespace App\Utils;


interface JobBatchServiceInterface
{
    public function getBatchStatus(string $batchID): array;
}
