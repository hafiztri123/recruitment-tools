<?php

namespace App\Services\Implementation;

use App\Http\Requests\CreateRecruitmentStageRequest;
use App\Models\RecruitmentStage;
use App\Repositories\RecruitmentStageRepository;
use App\Services\RecruitmentStageService;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class RecruitmentStageServiceImpl implements RecruitmentStageService
{
    public function __construct(
        protected RecruitmentStageRepository $recruitmentStageRepository
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
