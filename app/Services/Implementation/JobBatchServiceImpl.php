<?php

namespace App\Services\Implementation;

use App\Services\JobBatchService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Bus;

class JobBatchServiceImpl implements JobBatchService
{
    public function getBatchStatus(string $batchID): array
    {
        $batch = Bus::findBatch($batchID);

        if (!$batch) {
            throw new ModelNotFoundException('Job batch not found', 404);
        }

        return [
            'id' => $batch->id,
            'total_jobs' => $batch->totalJobs,
            'pending_jobs' => $batch->pendingJobs,
            'failed_jobs' => $batch->failedJobs,
            'progress' => $batch->progress(),
            'finished' => $batch->finished(),
            'has_failures' => $batch->hasFailures()
        ];
    }
}
