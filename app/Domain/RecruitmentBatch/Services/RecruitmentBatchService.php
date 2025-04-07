<?php

namespace App\Domain\RecruitmentBatch\Services;

use App\Domain\Position\Exceptions\PositionNotFoundException;
use App\Domain\Position\Interfaces\PositionRepositoryInterface;
use App\Domain\RecruitmentBatch\Interfaces\RecruitmentBatchRepositoryInterface;
use App\Domain\RecruitmentBatch\Interfaces\RecruitmentBatchServiceInterface;
use App\Domain\RecruitmentBatch\Models\RecruitmentBatch;
use App\Domain\RecruitmentBatch\Requests\CreateRecruitmentBatchRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class RecruitmentBatchService implements RecruitmentBatchServiceInterface
{
    public function __construct(
        protected RecruitmentBatchRepositoryInterface $recruitmentBatchRepository,
        protected PositionRepositoryInterface $positionRepository
    ){}

    public function createRecruitmentBatch(CreateRecruitmentBatchRequest $request, int $positionID): int
    {

        if(!$this->positionRepository->positionExists(positionID: $positionID)){
            throw new PositionNotFoundException(positionId: $positionID);
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
