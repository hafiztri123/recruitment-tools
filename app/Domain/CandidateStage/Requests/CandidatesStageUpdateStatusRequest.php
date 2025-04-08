<?php

namespace App\Domain\CandidateStage\Requests;

use App\Domain\CandidateStage\Interfaces\CandidateStageServiceInterface;
use App\Shared\Exceptions\ResourceNotFoundException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class CandidatesStageUpdateStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */






    /***************************************************
     *
     *
     *
     * * Purpose:
     *
     *
     *
     **************************************************/

    public function rules(): array
    {
        return [
            'candidates' => 'required|array|min:1',
            'candidates.*' => 'required|integer|exists:candidates,id'
        ];
    }


    /***************************************************
     *
     *
     *
     * * Purpose:
     *
     *
     *
     **************************************************/


    public function after(): array
    {
        return [
            function(Validator $validator){
                $candidateStageService = app(CandidateStageServiceInterface::class);
                $batchID = $this->route('recruitment_batch_id');

                foreach($this->candidates as $candidateID){
                    try{
                        $currentStage = $candidateStageService->getCurrentCandidateStage(
                            candidateID: $candidateID,
                            batchID: $batchID
                        );

                        if($currentStage->status === 'completed'){
                            $validator->errors()->add(
                                'candidates',
                                "Candidate ID $candidateID is already in a completed stage and cannot be moved"
                            );
                        }
                    } catch (ResourceNotFoundException $e) {
                        $validator->errors()->add(
                            'candidates',
                            "Candidate ID $candidateID is not in a valid stage for this batch"
                        );
                    }
                }
            }
        ];
    }
}
