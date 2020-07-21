<?php

namespace App\Exceptions;

use App\Http\Utilities\JsonUtility;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

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
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof NotFoundHttpException) {
            return JsonUtility::respond(false, 'Resource not found', Response::HTTP_NOT_FOUND);
        }

        if ($exception instanceof AuthenticationException) {
            return JsonUtility::respond(false, 'Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        if ($exception instanceof WarpgException) {
            return JsonUtility::respond(false, $exception->getMessage(), $exception->getHttpCode());
        }

        return JsonUtility::respond(false, 'Unknown exception: ' . $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
