<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
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
        $this->renderable(function (NotFoundHttpException $e) {
            if($e instanceof NotFoundHttpException) {
                return response()->json(['message' => 'Wrong Url']);
            }
        });

        $this->renderable(function (ModelNotFoundException $e) {
            if($e instanceof ModelNotFoundException) {
                return response()->json(['message' => 'No instance of this model has been found.']);
            }
        });

        $this->renderable(function (MethodNotAllowedHttpException $e) {
            if($e instanceof MethodNotAllowedHttpException) {
                return response()->json(['message' => 'Invalid method.']);
            }
        });
    }
}
