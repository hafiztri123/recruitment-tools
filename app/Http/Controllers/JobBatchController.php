<?php

namespace App\Http\Controllers;

use App\Services\ApiResponderService;
use App\Services\JobBatchService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class JobBatchController extends Controller
{

    public function __construct(
        protected JobBatchService $jobBatchService
    ){}

    public function jobBatchStatus(Request $request)
    {
        $jobBatchID = $request->route('job_batch_id');

        $BatchStatus = $this->jobBatchService->getBatchStatus(batchID: $jobBatchID);

        return (new ApiResponderService)->successResponse('Job batch status', Response::HTTP_OK, ['batch_status' => $BatchStatus]);
    }
}
