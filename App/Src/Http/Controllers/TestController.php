<?php


namespace App\Src\Http\Controllers;


use App\Src\Http\Requests\TestRequest;
use System\Http\Responses\JsonResponse;

class TestController
{
    public function testGet(TestRequest $request)
    {
        return JsonResponse::create()
            ->setArray(["test" => "222"]);
    }
}