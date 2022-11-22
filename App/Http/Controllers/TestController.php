<?php


namespace App\Http\Controllers;


use App\Http\Requests\TestRequest;
use System\Http\Responses\JsonResponse;

class TestController
{

    public function __construct()
    {
    }

    public function testGet(TestRequest $request)
    {
        return new JsonResponse(['test'=>2]);
    }

    public function testGett(TestRequest $request)
    {
        return new JsonResponse(['test'=>2]);
    }
}