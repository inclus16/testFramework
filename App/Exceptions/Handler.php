<?php


namespace App\Exceptions;


use System\Http\Responses\Response;

class Handler extends \System\Exceptions\Handler
{
    public function handle(\Exception $exception):Response
    {
        // make your own exception handler logic
       return parent::handle($exception);
    }

}