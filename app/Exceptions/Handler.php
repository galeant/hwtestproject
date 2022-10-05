<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
        });
        $this->renderable(function (Throwable $e) {
            $return = [
                'code' => 500,
                'message' => $e->getMessage(),
                'result' => NULL
            ];

            if ($e instanceof \Illuminate\Validation\ValidationException) {
                $return['code'] = 422;
                $return['message'] = $e->errors();
            } else if ($e instanceof \Illuminate\Validation\UnauthorizedException) {
                $return['code'] = 401;
                $return['message'] = 'Unauthorize';
            } else if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException || $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                $return['code'] = 404;
                $return['message'] = 'Route not found';
            }
            return response()->json($return, $return['code']);
        });
    }
}
