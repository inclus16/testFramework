<?php


namespace System\Http;


use System\Http\Dto\RequestController;
use System\Http\Requests\AppRequest;
use System\Http\Responses\Response;
use System\Http\Routing\RouterService;
use System\Http\Validation\ValidationProvider;
use System\InversionOfControl\ServiceCollection;
use System\InversionOfControl\ServiceProvider;

class Pipeline
{
    private RouterService $router;

    private ValidationProvider $validation;

    private ServiceProvider $services;

    public function __construct(RouterService $router, ValidationProvider $validation)
    {
        $this->router = $router;
        $this->validation = $validation;
    }

    public function setServiceProvider(ServiceProvider $services): self
    {
        $this->services = $services;
        return $this;
    }

    public function execute(): Response
    {
        $requestController = $this->router->getController();
        $controllerObject = $this->services->getService($requestController->getClass());
        $controllerAction = $requestController->getAction();
        return $this->resolveAction($controllerObject, $requestController->getClass(), $controllerAction);
    }

    private function resolveAction(object $obj, string $class, string $action): Response
    {
        $reflectionClass = new \ReflectionClass($class);
        /** @var $parameters \ReflectionParameter[] * */
        $parameters = $reflectionClass->getMethod($action)->getParameters();
        $resolvedParameters = [];
        foreach ($parameters as $parameter) {
            $reflectionParameterClass = $parameter->getClass();
            $parent = $reflectionParameterClass->getParentClass();
            if ($parent !== false && $parent->getName() === AppRequest::class) {
                $appRequest = $this->resolveCustomRequest($reflectionParameterClass);
                $this->invokeCustomRequest($appRequest);
                $resolvedParameters[] = $appRequest;
            } else {
                $resolvedParameters[] = $this->services->getService($parameter->getClass()->getName());
            }
        }
        $method = new \ReflectionMethod($class, $action);
        return $method->invokeArgs($obj, $resolvedParameters);
    }

    private function invokeCustomRequest(AppRequest $request)
    {

    }

    private function resolveCustomRequest(\ReflectionClass $customRequest): AppRequest
    {
        $constructor = $customRequest->getConstructor();
        if ($constructor === null) {
            return $customRequest->newInstance();
        }
        $parameters = $constructor->getParameters();
        $resolvedParameters = [];
        foreach ($parameters as $parameter) {
            $resolvedParameters[] = $this->services->getService($parameter->getClass()->getName());
        }
        $request = $customRequest->newInstanceArgs($resolvedParameters);
        $this->validation->validateCustomRequest($request);
        return $request;
    }
}