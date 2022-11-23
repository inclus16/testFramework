<?php

namespace System\Log;

use System\AppContext;
use System\Log\Abstraction\Logger;

class PipelineLogger extends Logger
{

    public function __construct(AppContext $context)
    {
        parent::__construct($context,'pipeline');
    }
}