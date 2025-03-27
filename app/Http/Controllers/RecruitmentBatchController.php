<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRecruitmentBatchRequest;
use App\Models\RecruitmentBatch;
use App\Services\ApiResponderService;
use App\Services\RecruitmentBatchService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class RecruitmentBatchController extends Controller
{
    public function __construct(protected RecruitmentBatchService $recruitmentBatchService)
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
