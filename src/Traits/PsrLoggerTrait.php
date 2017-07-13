<?php
namespace Shrikeh\Bounce\Traits;

use Psr\Log\LoggerInterface as Logger;

/**
 * Trait PsrLoggerTrait
 * @package Shrikeh\Bounce\Traits
 */
trait PsrLoggerTrait
{
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @param int $level
     * @param string $message
     * @param array $context
     */
    private function log(string $level, string $message, array $context = array())
    {
        if ($this->logger instanceof Logger) {
            $this->logger->log($level, $message, $context);
        }
    }

    private function logger(Logger $logger = null)
    {
        if ($logger instanceof Logger) {
            $this->logger = $logger;
        }
    }
}