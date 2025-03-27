<?php

namespace App\Services\Implementation;

use App\Http\Requests\CreateRecruitmentBatchRequest;
use App\Models\RecruitmentBatch;
use App\Repositories\PositionRepository;
use App\Repositories\RecruitmentBatchRepository;
use App\Services\RecruitmentBatchService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class RecruitmentBatchServiceImpl implements RecruitmentBatchService
{
    public function __construct(
        protected RecruitmentBatchRepository $recruitmentBatchRepository,
        protected PositionRepository $positionRepository
    ){}

    public function createRecruitmentBatch(CreateRecruitmentBatchRequest $request, int $positionID): int
    {
        Gate::authorize('create', RecruitmentBatch::class);

        if(!$this->positionRepository->positionExists(positionID: $positionID)){
            throw new ModelNotFoundException('Position not found', 404);
        }

        $recruitmentBatch = $this->makeRecruitmentBatch(request: $request, positionID: $positionID);

        return $this->recruitmentBatchRepository->create($recruitmentBatch);
    }

    private function makeRecruitmentBatch(CreateRecruitmentBatchRequest $request, int $positionID): RecruitmentBatch
    {
        $recruitmentBatch = RecruitmentBatch::make([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
            'description' => $request->description
        ]);

        $recruitmentBatch->created_by = Auth::user()->id;
        $recruitmentBatch->position_id = $positionID;

        return $recruitmentBatch;
    }


}
