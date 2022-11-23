<?php

namespace System\Log;

use System\AppContext;
use System\Config\App\AppConfig;
use System\Log\Abstraction\Logger;

class PipelineLogger extends Logger
{

    public function __construct(AppContext $context, AppConfig $config)
    {
        parent::__construct($context, $config, 'pipeline');
    }
}