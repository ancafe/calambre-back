<?php

namespace App\Exceptions;

use App\Exceptions\Type\ApiError;
use App\Exceptions\Type\ApiNotFoundError;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
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

        $this->renderable(function (Throwable $e) {

            $msgError = $this->isJson($e->getMessage()) ? json_decode($e->getMessage()) : $e->getMessage();

            if ($e instanceof NotFoundHttpException) {
                throw new ApiNotFoundError([ErrorDtoFactory::routeNotFound()]);
            }

            if ($e->getCode() >= 100 && $e->getCode()<600) {
                return response([
                    'code' => $e->getCode(),
                    'success' => false,
                    'msg' => $msgError
                ], $e->getCode() ?: 400);
            }


        });

    }

    private function isJson($string): bool
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

}
