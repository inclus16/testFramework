<?php


namespace System\Http;


use App\Http\Requests\TestRequest;
use Swoole\Http\Request;
use Swoole\Http\Response;
use System\Http\Dto\RequestController;
use System\Http\Requests\BasicRequest;
use System\Http\Routing\RouterService;
use System\Http\Validation\ValidationProvider;
use System\InversionOfControl\ScopedServices;

class Pipeline
{
    private RouterService $router;

    private ValidationProvider $validation;

    public function __construct(RouterService $router, ValidationProvider $validation)
    {
        $this->router = $router;
        $this->validation = $validation;
    }

    public function invoke(Request $request, Response $response, ScopedServices $scopedServices)
    {
        $route = $this->router->getConfig($request->server['path_info'], $request->getMethod());
        $controller = $scopedServices->getService($route->getController());
        $controllerMethod = $route->getControllerAction();
        $controllerResult = $controller->$controllerMethod(new TestRequest(BasicRequest::createFromSwooleRequest($request)));
        $this->writeResponse($response,$controllerResult);
    }

    private function writeResponse(Response $response, \System\Http\Responses\Response $controllerResponse)
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
}