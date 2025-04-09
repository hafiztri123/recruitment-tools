<?php

namespace App\Domain\RecruitmentBatch\Services;

use App\Domain\Position\Exceptions\PositionNotFoundException;
use App\Domain\Position\Interfaces\PositionRepositoryInterface;
use App\Domain\RecruitmentBatch\Interfaces\RecruitmentBatchRepositoryInterface;
use App\Domain\RecruitmentBatch\Interfaces\RecruitmentBatchServiceInterface;
use App\Domain\RecruitmentBatch\Models\RecruitmentBatch;
use App\Domain\RecruitmentBatch\Requests\CreateRecruitmentBatchRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RecruitmentBatchService implements RecruitmentBatchServiceInterface
{
    public function __construct(
        protected RecruitmentBatchRepositoryInterface $recruitmentBatchRepository,
        protected PositionRepositoryInterface $positionRepository
    ) {}

    public function createRecruitmentBatch(CreateRecruitmentBatchRequest $request, int $positionID): int
    {
        return DB::transaction(function () use ($request, $positionID) {
            $this->validatePositionExists($positionID);

            $recruitmentBatch = $this->makeRecruitmentBatch($request, $positionID);
            return $this->recruitmentBatchRepository->create($recruitmentBatch);
        });
    }


    private function validatePositionExists(int $positionID): void
    {
        if (!$this->positionRepository->positionExists(positionID: $positionID)) {
            throw new PositionNotFoundException(positionId: $positionID);
        }
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
