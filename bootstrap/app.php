<?php

use App\Shared\ApiResponderService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {


        $exceptions->report(function (Throwable $e){
            if ($e instanceof NotFoundHttpException){
                return false;
            }

            if ($e instanceof ValidationException){
                return false;
            }

            Log::error('Exception occured', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
        });

        $exceptions->render(function (AuthorizationException $e, Request $request) {
            if ($request->is(config('constants.ROUTE_API_WILDCARD'))) {
                return (new ApiResponderService)->failResponse(message: $e->getMessage(), statusCode: Response::HTTP_FORBIDDEN);
            }
        });



        $exceptions->render(function(QueryException $e, Request $request){
            if ($request->is(config('constants.ROUTE_API_WILDCARD'))){
                return (new ApiResponderService)->failResponse(message: $e->getMessage(), statusCode: Response::HTTP_BAD_REQUEST);
            }
        });

        $exceptions->render(function(MassAssignmentException $e, Request $request){
            if ($request->is(config('constants.ROUTE_API_WILDCARD'))){
                return (new ApiResponderService)->failResponse(message: $e->getMessage(), statusCode: Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        });



        $exceptions->render(function(ValidationException $e, Request $request){
            if($request->is(config('constants.ROUTE_API_WILDCARD'))){
                return (new ApiResponderService)->failResponse(message: $e->getMessage(), statusCode: Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        });

        $exceptions->render(function(AccessDeniedHttpException $e, Request $request){
            if($request->is(config('constants.ROUTE_API_WILDCARD'))){
                return (new ApiResponderService)->failResponse(message: $e->getMessage(), statusCode: Response::HTTP_FORBIDDEN);
            }
        });


        $exceptions->render(function(\Exception $e, Request $request){
            if($request->is(config('constants.ROUTE_API_WILDCARD'))){
                return (new ApiResponderService)->failResponse(message: $e->getMessage(), statusCode: $e->getCode() ?: RESPONSE::HTTP_INTERNAL_SERVER_ERROR, errors: ['context' => $e->getTrace()]);
            }
        });

    })->create();
