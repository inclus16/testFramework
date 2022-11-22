<?php


namespace App\Http\Controllers;


use App\Http\Requests\TestRequest;
use System\Http\Responses\JsonResponse;

class TestController
{

    public function __construct()
    {
    }

    public function testGet(int $id, TestRequest $request)
    {
        return new JsonResponse(['id' => $id, 'field' => $request->getBasicRequest()->getFieldValue('field')]);
    }

    public function testGett(TestRequest $request)
    {
        return new JsonResponse(['test' => 2]);
    }
}