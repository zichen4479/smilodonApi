<?php

namespace App\Exceptions;

use Illuminate\Foundation\Bootstrap\HandleExceptions;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;
use Illuminate\Support\Facades\Log;
use \App\Exceptions\ESUException as ESUException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    protected $statusCode;

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param Throwable $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ESUException) {
            $this->statusCode = 200;
            $code = $exception->getCode();
            $msg = $exception->getMessage();
            if ($code != 1) {
                Log::error($exception->getMessage());
            }
        } else {
            if ($exception instanceof UnauthorizedHttpException) {
                if ($exception->getPrevious() instanceof TokenExpiredException) {
                    $this->statusCode = 401;
                    $code = 40003;
                    $msg = trans('auth.tokenExpired');
                    Log::error($exception->getMessage());
                } else if ($exception->getPrevious() instanceof TokenInvalidException) {
                    $code = 40004;
                    $this->statusCode = 403;
                    $msg = trans('auth.tokenInvalid');
                    Log::error($exception->getMessage());
                } else if ($exception->getPrevious() instanceof TokenBlacklistedException) {
                    $this->statusCode = 403;
                    $code = 40005;
                    $msg = trans('auth.tokenBlacklisted');
                    Log::error($exception->getMessage());
                } else {
                    $this->statusCode = 403;
                    $code = 49997;
                    $msg = trans('auth.unauthorized');
                    Log::error($exception->getMessage());
                }
            } elseif ($exception instanceof ApiRequestExcept) {
                $code = $exception->getCode();
                $msg = $exception->getMessage();
                $this->statusCode = 406;
                Log::error($exception->getMessage());
            } elseif ($exception instanceof SystemErrorExcept) {
                $code = $exception->getCode();
                $msg = $exception->getMessage();
                $this->statusCode = 200;
                Log::error($exception->getMessage());
            } else {
                $this->statusCode = 500;
                $code = 99999;
                $msg = trans('server.ERROR');
                Log::error($exception);
            }

        }
        return response()->eshopsunion($code, null, $msg)->setStatusCode($this->statusCode);
    }
}
