<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use \Illuminate\Auth\AuthenticationException;
use \Illuminate\Validation\ValidationException;
use \Illuminate\Database\QueryException;
use \Symfony\Component\HttpKernel\Exception\HttpException;

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
        if($exception instanceof ModelNotFoundException){
            return response()->json([
                'error' => 'Error de modelo '.$exception->getMessage(),
                'code' =>400
            ]);
        }
        if($exception instanceof ValidationException){
            return response()->json([
                'error' => $exception->validator->errors(),
                'code' =>400
            ]);
        }
        if($exception instanceof QueryException){
            return response()->json([
                'error' => 'Error de consulta '. $exception->getMessage(),
                'code' => 400
            ]);
        }

        if($exception instanceof HttpException){
            return response()->json([
                'error' => 'Error de ruta',
                'code' => 404
            ]);
        }
        if($exception instanceof AuthenticationException){
            return response()->json([
                'error' => 'Error de autenticación',
                'code' => 401
            ]);
        }

        return parent::render($request, $exception);
    }
}
