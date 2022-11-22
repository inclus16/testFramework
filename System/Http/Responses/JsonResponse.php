<?php


namespace System\Http\Responses;


class JsonResponse extends Response
{
    public function __construct(iterable $data, int $status = 200)
    {
        parent::__construct();
        $this->body = json_encode($data);
        $this->setHeader('Content-Type', 'application/json');
    }
}