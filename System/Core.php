<?php


namespace System;


use App\Src\Exceptions\Handler;

class Core
{
    public function invoke()
    {
        $services = new \App\Providers\AppServiceProvider();
        $sp = $services->boot();
        try {
            $sp->getService(\System\Http\Pipeline::class)
                ->setServiceProvider($sp)
                ->execute()
                ->sendResponse();
        } catch (\Exception $exception) {
            $sp->getService(Handler::class)
                ->handle($exception)
                ->sendResponse();
        }
    }
}