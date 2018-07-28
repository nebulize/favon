<?php

namespace Favon\Application\Logging;

use Illuminate\Log\Logger;
use Monolog\Handler\RotatingFileHandler;

class LogSourceSplitter
{
    /**
     * Use separate log files for CLI and server application calls.
     *
     * @param Logger $logger
     */
    public function __invoke(Logger $logger)
    {
        $logger->popHandler();
        $processUser = posix_getpwuid(posix_geteuid());
        $processName = $processUser['name'];
        $filename = storage_path('logs/laravel-'.php_sapi_name().'-'.$processName.'.log');
        $handler = new RotatingFileHandler($filename);
        $logger->pushHandler($handler);
    }
}
