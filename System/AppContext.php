<?php


namespace System;


class AppContext
{
    public function getBaseDirectory():string
    {
        return realpath(__DIR__.'/../');
    }
}