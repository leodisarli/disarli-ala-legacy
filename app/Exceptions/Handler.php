<?php

namespace App\Exceptions;

use App\Exceptions\Custom;
use App\Exceptions\Custom\InputValidationException;
use App\Exceptions\Custom\DuplicatedDataException;
use App\Helpers\JsonResponse;
use App\Helpers\ResponseJsonHelper;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    protected $mapException = [
        'App\Exceptions\Custom\DataNotFoundException' => 'DATA_NOT_FOUND',
        'App\Exceptions\Custom\EmptyCredentialsException' => 'EMPTY_CREDENTIALS',
        'App\Exceptions\Custom\ExternalApiException' => 'EXTERNAL_API',
        'App\Exceptions\Custom\ForbiddenAccessException' => 'FORBIDDEN_ACCESS',
        'App\Exceptions\Custom\InvalidCredentialsException' => 'INVALID_CREDENTIALS',
        'App\Exceptions\Custom\InvalidOrderArrayException' => 'INVALID_ORDER_ARRAY_STRUCTURE',
        'App\Exceptions\Custom\InvalidOrderOperatorException' => 'INVALID_ORDER_OPERATOR',
        'App\Exceptions\Custom\InvalidRefineArrayException' => 'INVALID_REFINE_ARRAY_STRUCTURE',
        'App\Exceptions\Custom\InvalidRefineOperatorException' => 'INVALID_REFINE_OPERATOR',
        'App\Exceptions\Custom\InvalidRefineParamsException' => 'INVALID_REFINE_PARAMS',
        'App\Exceptions\Custom\MissingRouteAliasException' => 'MISSING_ROUTE_ALIAS',
        'App\Exceptions\Custom\MissingRouteValidateException' => 'MISSING_ROUTE_VALIDATE',
        'App\Exceptions\Custom\NothingToSaveException' => 'NOTHING_TO_SAVE',
        'App\Exceptions\Custom\NotSaveDataException' => 'NOT_SAVE_DATA',
        'App\Exceptions\Custom\PaginateNotFoundException' => 'PAGINATE_NOT_FOUND',
        'Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException' => 'METHOD_NOT_ALLOWED',
        'Symfony\Component\HttpKernel\Exception\NotFoundHttpException' => 'METHOD_NOT_ALLOWED',
    ];

    /**
     * A list of the exception types that should not be reported.
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        InputValidationException::class,
        MethodNotAllowedHttpException::class,
        MethodNotAllowedHttpException::class,
        ModelNotFoundException::class,
        NotFoundHttpException::class,
        NotFoundHttpException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     * @param Exception $e
     * @return void
     */
    public function report(
        Exception $exception
    ) {
        if ($this->shouldntReport($exception)) {
            return;
        }

        try {
            $logger = app('Psr\Log\LoggerInterface');
        } catch (Exception $ex) {
            throw $exception;
        }

        $logger->error(
            $exception,
            [
                'request_id' => ResponseJsonHelper::$requestId,
                'exception' => $exception
            ]
        );
    }

    /**
     * Render an exception into an HTTP response.
     * @param Request $request
     * @param Exception $exception
     * @return Response
     */
    public function render(
        $request,
        Exception $exception
    ) {
        return $this->renderJson($request, $exception);
    }
    
    /**
     * Create response json depends on exception
     * @param Request $request
     * @param Exception $exception - exception
     */
    private function renderJson(
        $request,
        Exception $exception
    ) {
        $exClass = get_class($exception);

        if (array_key_exists($exClass, $this->mapException)) {
            $reflection = new \ReflectionClass('App\Exceptions\ErrorCodes');
            $exception = $this->mapException[$exClass];
            $data = $reflection->getConstant($exception);
            return ResponseJsonHelper::error(
                $data['code'],
                $data['message'],
                $data['header']
            );
        }

        if ($exception instanceof HttpResponseException) {
            return $exception->getResponse();
        }
        
        if ($exception instanceof ModelNotFoundException) {
            $exception = new NotFoundHttpException($exception->getMessage(), $exception);
        }
        
        if ($exception instanceof AuthorizationException) {
            $exception = new HttpException(403, $exception->getMessage());
        }
        
        if ($exception instanceof ValidationException && $exception->getResponse()) {
            return $exception->getResponse();
        }

        if ($exception instanceof InputValidationException) {
            return ResponseJsonHelper::error(
                ErrorCodes::INPUT_VALIDATION_ERROR["code"],
                $exception->getMessages(),
                400
            );
        }

        if ($exception instanceof DuplicatedDataException) {
            return ResponseJsonHelper::error(
                ErrorCodes::DUPLICATED_DATA["code"],
                $exception->getMessages(),
                409
            );
        }

        $flatten = FlattenException::create($exception);
        $stringError = 'An generic error occours';
        $showRequest = 'You have no power here';
        if (env("APP_DEBUG")) {
            $stringError = $exception->__toString();
            $showRequest = $request;
        }
        $json = ResponseJsonHelper::error(
            ErrorCodes::GENERIC_ERROR["code"],
            [
                "message" => ErrorCodes::GENERIC_ERROR["message"],
                "exception" => $stringError,
                "request" => $showRequest,
            ],
            $flatten->getStatusCode()
        );
        
        return $json;
    }
}
