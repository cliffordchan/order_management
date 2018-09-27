<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as LaravelExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class Handler
 * @package App\Exceptions
 */
class Handler extends LaravelExceptionHandler
{
    const ERROR_DESCRIPTION = 'error';
    const MESSAGE_SERVER_ERROR = 'Internal Server Error';

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param Exception $exception
     * @return JsonResponse|\Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof OrderAlreadyBeenTakenException) {
            $exception = new HttpException(
                Response::HTTP_CONFLICT,
                $exception->getMessage()
            );
        }

        if ($exception instanceof InvalidCoordinateException) {
            $exception = new HttpException(
                Response::HTTP_BAD_REQUEST,
                $exception->getMessage()
            );
        }

        if ($exception instanceof OrderNotFoundException) {
            $exception = new HttpException(
                Response::HTTP_NOT_FOUND,
                $exception->getMessage()
            );
        }

        if ($exception instanceof \ErrorException) {
            $exception = new HttpException(
                Response::HTTP_BAD_REQUEST,
                $exception->getMessage()
            );
        }

        $parentRender = parent::render($request, $exception);

        if ($parentRender instanceof JsonResponse) {
            return $parentRender;
        }

        return new JsonResponse(
            [static::ERROR_DESCRIPTION => $exception->getMessage()],
            $parentRender->status()
        );
    }
}
