<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
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
     * @throws \Throwable
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
        if($request->expectsJson())
        {
            if($exception instanceof \Illuminate\Auth\Access\AuthorizationException)
            {
                return response()->json([
                    'error' => 'Otorisasi gagal.'
                ], 401);
            }

            if($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException)
            {
                return response()->json([
                    'error' => 'Tidak ditemukan.'
                ], 404);
            }

            if($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException)
            {
                // dd($exception->getModel());  //mengambil model
                $modelClass = explode('\\', $exception->getModel());

                // dd(end($modelClass));    //Mengambil nama model pada namespace
                return response()->json([
                    'error' => end($modelClass).' tidak ditemukan.'
                ], 404);
            }
        }
        return parent::render($request, $exception);
    }
}
