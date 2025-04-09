<?php

namespace App\Domain\RecruitmentStage\Services;

use App\Domain\RecruitmentStage\Exceptions\RecruitmentStageConflictException;
use App\Domain\RecruitmentStage\Interfaces\RecruitmentStageRepositoryInterface;
use App\Domain\RecruitmentStage\Interfaces\RecruitmentStageServiceInterface;
use App\Domain\RecruitmentStage\Models\RecruitmentStage;
use App\Domain\RecruitmentStage\Requests\CreateRecruitmentStageRequest;
use Illuminate\Support\Facades\DB;

class RecruitmentStageService implements RecruitmentStageServiceInterface
{
    public function __construct(
        protected RecruitmentStageRepositoryInterface $recruitmentStageRepository
    ) {}

    public function createRecruitmentStage(CreateRecruitmentStageRequest $request): void
    {
        DB::transaction(function () use ($request) {
            if ($this->recruitmentStageRepository->existsByOrderAndActive($request->order)) {
                throw new RecruitmentStageConflictException(customMessage: 'Request with same order already exists');
            }

            $recruitmentStage = RecruitmentStage::make([
                'name' => $request->name,
                'order' => $request->order,
                'is_active' => true
            ]);

            $this->recruitmentStageRepository->create($recruitmentStage);
        });
    }
}
