<?php

namespace App\Http\Controllers;

use App\ApiResponder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
Use App\Constant;

class HeadOfHRController extends Controller
{

    use ApiResponder;

    public function AssignHR(Request $request)
    {
        Gate::authorize('AssignRoles', $request->user());

        $userID = $request->route('user_id');

        $HRID = DB::table('roles')
            ->where('slug', 'hr')
            ->value('id');

        $userAlreadyHR = DB::table('role_user')
            ->where('user_id', $userID)
            ->where('role_id', $HRID)
            ->exists();

        if($userAlreadyHR){
            return $this->errorResponse('User already an HR', 'CONFLICT', 409);
        }

        try{
            DB::transaction(function () use ($userID, $HRID) {


                DB::table('role_user')
                    ->insert([
                        'user_id' => $userID,
                        'role_id' => $HRID,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);

            });

            return $this->successResponseWithoutData('Assign HR Success', 200);
        } catch (\Exception $e){
            return $this->errorResponse('Assign HR Failed', 'INTERNAL_SERVER_ERROR', 500, [
                'error' => $e->getMessage()
            ]);
        }
    }
}
