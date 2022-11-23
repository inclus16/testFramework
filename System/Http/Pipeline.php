<?php


namespace System\Http;


use App\Http\Requests\TestRequest;
use Swoole\Http\Request;
use Swoole\Http\Response;
use System\Exceptions\Handler;
use System\Http\Middleware\Abstractions\MiddlewareInterface;
use System\Http\Requests\AppRequest;
use System\Http\Responses\JsonResponse;
use System\Http\Routing\ControllerParameterResolver;
use System\Http\Routing\RouteParametersValidator;
use System\Http\Routing\RouterResolver;
use System\Http\Validation\ValidationProvider;
use System\InversionOfControl\ControllersDescriptor;
use System\InversionOfControl\ScopedServices;
use System\Log\PipelineLogger;

class Pipeline
{

    public function __construct(private readonly RouterResolver              $router,
                                private readonly ValidationProvider          $validation,
                                private readonly ControllersDescriptor       $controllersDescriptor,
                                private readonly RouteParametersValidator    $routeParametersValidator,
                                private readonly ControllerParameterResolver $controllerParameterResolver,
                                private readonly PipelineLogger              $logger,
                                private readonly Handler                     $handler)
    {
    }

    public function invoke(Request $request, Response $response, ScopedServices $scopedServices): void
    {
        try {
            $this->logger->debug('Incoming request', [
                'fd' => $request->fd
            ]);
            $routeData = $this->router->resolveRoute($request->server['path_info'], $request->getMethod());
            if ($routeData === null) {
                $this->logger->warning('Route not found: ' . $request->server['path_info'], [
                    'fd' => $request->fd
                ]);
                $this->writeNotFound($response);
                return;
            }
            $this->logger->debug('Route resolved.', [
                'fd' => $request->fd
            ]);
            $controllerActionParameters = $this->controllersDescriptor->getActionParameters($routeData->getRouteConfigItem()->getController(),
                $routeData->getRouteConfigItem()->getControllerAction());
            if (!$this->routeParametersValidator->validate($controllerActionParameters,
                $routeData->getParameters())) {
                $this->logger->warning('Invalid route parameters.', [
                    'fd' => $request->fd
                ]);
                $this->writeNotFound($response);
                return;
            }
            foreach ($routeData->getRouteConfigItem()->getMiddlewares() as $middleware) {
                /** @var MiddlewareInterface $middlewareInstance */
                $middlewareInstance = $scopedServices->getService($middleware);
                if (!$middlewareInstance->invoke($request)) {
                    $this->logger->warning('Middleware ' . get_class($middlewareInstance) . ' broke pipeline', [
                        'fd' => $request->fd
                    ]);
                    $this->writeResponse($response, $middlewareInstance->getResponse());
                    return;
                }
            }
            $resolvedActionParameters = $this->controllerParameterResolver->resolve($controllerActionParameters, $routeData->getParameters(), $request);
            foreach ($resolvedActionParameters as $resolvedActionParameter) {
                if ($resolvedActionParameter instanceof AppRequest) {
                    $validationResult = $this->validation->validateRequest($resolvedActionParameter);
                    if ($validationResult !== null) {
                        $this->logger->warning('Request validation fails', [
                            'fd' => $request->fd
                        ]);
                        $this->writeResponse($response, new JsonResponse($validationResult->getErrors(), $validationResult->getStatusCode()));
                        return;
                    }
                }
            }
            $controller = $scopedServices->getService($routeData->getRouteConfigItem()->getController());
            $this->logger->debug('Dispatching to controller', [
                'fd' => $request->fd
            ]);
            $controllerMethod = $routeData->getRouteConfigItem()->getControllerAction();
            $controllerResult = $controller->$controllerMethod(...$resolvedActionParameters);
            $this->writeResponse($response, $controllerResult);
            $this->logger->debug('Response ended', [
                'fd' => $request->fd
            ]);
        } catch (\Throwable $exception) {
            $this->writeResponse($response, $this->handler->handle($exception, $request->fd));
        }
    }


    private function writeResponse(Response $response, Responses\Response $controllerResponse): void
    {
        $response->setStatusCode($controllerResponse->status);
        foreach ($controllerResponse->headers as $header) {
            $response->setHeader($header->getKey(), $header->getValue());
        }
        foreach ($controllerResponse->cookies as $cookie) {
            $response->setCookie($cookie->getName(),
                $cookie->getValue(),
                $cookie->getExpires(),
                $cookie->getPath(),
                $cookie->getDomain(),
                $cookie->isSecure(),
                $cookie->isHttpOnly(),
                $cookie->getSamesite(),
                $cookie->getPriority()
            );
        }
        $response->end($controllerResponse->body);
    }

    private function writeNotFound(Response $response): void
    {
        $response->setStatusCode(404);
        $response->end();
    }
}