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
        $traceArray = $this->exception->getTrace();
        for ($i = 0; $i < count($traceArray); $i++) {
            if (!empty($traceArray[$i]['class']))
                $trace .= "<p>#$i {$traceArray[$i]['class']}({$traceArray[$i]['line']})</p>";
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
<h1>There is an exception was thrown: {$this->exception->getMessage()}</h1>
<h2> {$this->exception->getFile()}({$this->exception->getLine()})</h2>
<br>
{$trace}
</body>
</html>
LINCOLN;
        return $html;

    }
}