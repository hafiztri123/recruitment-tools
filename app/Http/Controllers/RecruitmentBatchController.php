<?php

namespace App\Http\Controllers;

use App\ApiResponder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class RecruitmentBatchController extends Controller
{
    use ApiResponder;

    public function CreateRecruitmentBatches(Request $request)
    {
        Gate::authorize('CreateRecruitmentBatches'); //TODO = gate inspect for proper error message

        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:recruitment_batches,name'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['sometimes', 'date'],
            'status' => ['required', 'string', 'in:active,completed,cancelled'], //TODO = default active di database for robust fallback
            'description' => ['sometimes', 'string'],
            'position_title' => ['required', 'string', 'exists:positions,title']
        ]);

        try{
            DB::transaction(function () use ($request) {
                $positionID = DB::table('positions')
                    ->whereRaw('LOWER(title) = ?', [strtolower($request->position_title)])
                    ->value('id');

                DB::table('recruitment_batches')
                    ->insert([
                        'name' => $request->name,
                        'start_date' => $request->start_date,
                        'end_date' => $request->end_date,
                        'status' => $request->status,
                        'description' => $request->description,
                        'created_by' => $request->user()->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                        'position_id' => $positionID
                    ]);
            });

            return $this->successResponseWithoutData('recruitment batch created', 201);
        } catch (\Exception $e){
            return $this->errorResponse(
                'Fail creating recruitment batch',
                'INTERNAL_SERVER_ERROR',
                500,
                ['data' => $e->getMessage()]
            );
        }


    }
}
