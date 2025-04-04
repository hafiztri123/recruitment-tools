<?php

namespace App\Domain\RecruitmentStage\Services;

use App\Domain\RecruitmentStage\Interfaces\RecruitmentStageRepositoryInterface;
use App\Domain\RecruitmentStage\Interfaces\RecruitmentStageServiceInterface;
use App\Domain\RecruitmentStage\Models\RecruitmentStage;
use App\Domain\RecruitmentStage\Requests\CreateRecruitmentStageRequest;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class RecruitmentStageService implements RecruitmentStageServiceInterface
{
    public function __construct(
        protected RecruitmentStageRepositoryInterface $recruitmentStageRepository
    ){}

    public function createRecruitmentStage(CreateRecruitmentStageRequest $request): void
    {
        if($this->recruitmentStageRepository->existsByOrderAndActive($request->order)){
            throw new ConflictHttpException('Recruitment stage already exists', null, 409);
        }

        $recruitmentStage = RecruitmentStage::make([
            'name' => $request->name,
            'order' => $request->order,
            'is_active' => true
        ]);

        $this->recruitmentStageRepository->create($recruitmentStage);
    }
}
