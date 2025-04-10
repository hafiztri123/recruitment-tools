<?php

namespace App\Domain\RecruitmentBatch\Controllers;

use App\Domain\RecruitmentBatch\Interfaces\RecruitmentBatchServiceInterface;
use App\Domain\RecruitmentBatch\Models\RecruitmentBatch;
use App\Domain\RecruitmentBatch\Requests\CreateRecruitmentBatchRequest;
use App\Shared\Controllers\Controller;
use App\Shared\Services\ApiResponderService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class RecruitmentBatchController extends Controller
{
    public function __construct(protected RecruitmentBatchServiceInterface $recruitmentBatchService)
    {

    }


    public function createRecruitmentBatch(CreateRecruitmentBatchRequest $request)
    {
        Gate::authorize('create', RecruitmentBatch::class);
        $positionID = $request->route(param: 'position_id', default: null);
        $recruitmentBatchID = $this->recruitmentBatchService->createRecruitmentBatch(request: $request, positionID: $positionID);

        return (new ApiResponderService)->successResponse('Recruitment batch created', Response::HTTP_CREATED, ['recruitment_batch_id' => $recruitmentBatchID]);
    }


}
