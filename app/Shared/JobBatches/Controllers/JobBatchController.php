<?php


namespace App\Shared\JobBatches\Controllers;

use App\Shared\ApiResponderService;
use App\Shared\Controllers\Controller;
use App\Shared\JobBatches\Interfaces\JobBatchServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class JobBatchController extends Controller
{

    public function __construct(
        protected JobBatchServiceInterface $jobBatchService
    ){}

    public function jobBatchStatus(Request $request)
    {
        $jobBatchID = $request->route('job_batch_id');

        $BatchStatus = $this->jobBatchService->getBatchStatus(batchID: $jobBatchID);

        return (new ApiResponderService)->successResponse('Job batch status', Response::HTTP_OK, ['batch_status' => $BatchStatus]);
    }
}
