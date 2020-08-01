<?php


namespace System\Http\Responses;


class JsonResponse extends Response
{
    public function __construct()
    {
        parent::__construct();
        $this->setHeader('Content-Type', 'application/json');
    }


    public function setArray(iterable $array): self
    {
        return $this->setBody(json_encode($array));
    }
}