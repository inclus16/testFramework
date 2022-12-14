<?php


namespace System\Http\Dto;


class Header
{
    private string $key;

    private string $value;

    public function __construct(string $key,string $value)
    {
        $this->key=$key;
        $this->value=$value;
    }


    public function getKey(): string
    {
        return $this->key;
    }


    public function getValue(): string
    {
        return $this->value;
    }


}