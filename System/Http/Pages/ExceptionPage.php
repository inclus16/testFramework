<?php

namespace System\Http\Pages;

use System\Http\Pages\Abstractions\PageInterface;

class ExceptionPage implements PageInterface
{


    public function __construct(private readonly \Throwable $exception)
    {
    }

    public function render(): string
    {
        $trace = '';
        foreach ($this->exception->getTrace() as $stackItem) {
            $trace .= "<p>$stackItem</p>";
        }
        $html = <<< LINCOLN
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Exception</title>    
    <style>
    body{
    background-color: black;
    color: darkred;
    }
</style>
</head>
<body>
<h1>{{$this->exception->getMessage()}}</h1>
<br>
{{$trace}}
</body>
</html>
LINCOLN;
        return $html;

    }
}