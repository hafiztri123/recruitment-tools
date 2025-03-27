<?php

namespace App\Repositories\Implementation;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class UserRepositoryImpl implements UserRepository
{
    public function create(User $user) :void
    {
        try {
            $user->save();
            return;
        } catch (QueryException $e) {
            Log::error('Database error', ['context' => $e]);
            throw new \Exception('Database constaint violation', 400);
        } catch (MassAssignmentException $e) {
            Log::error('Mass assignment error', ['context' => $e]);
            throw new \Exception('Invalid attributes', 422);
        } catch (ModelNotFoundException $e) {
            Log::error('Model not found', ['context' => $e]);
            throw new \Exception('Resource not found', 404);
        } catch (\Exception $e) {
            Log::error('General error', ['context' => $e]);
            throw new \Exception('Something went wrong', 500 );
        }
    }

    public function findByEmail(string $email): User
    {
        try {
            return User::where('email', $email)->firstOrFail();

        } catch (\Exception $e){
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }
}
