<?php

namespace App\Helper;


use Psr\Log\LoggerInterface;

class LoggingHelper
{
    /**
     * @var LoggerInterface|null
     */
    private static $logger = null;

    /**
     * @param LoggerInterface $logger
     */
    public static function initialize(LoggerInterface $logger)
    {
        self::$logger = $logger;
    }

    /**
     * @return LoggerInterface|null
     */
    public static function getInstance()
    {
        return self::$logger;
    }
}
