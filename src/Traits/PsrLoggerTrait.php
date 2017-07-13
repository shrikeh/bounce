<?php
namespace Shrikeh\Bounce\Traits;

use Psr\Log\LoggerInterface;

/**
 * Trait PsrLoggerTrait
 * @package Shrikeh\Bounce\Traits
 */
trait PsrLoggerTrait
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param int $level
     * @param string $message
     * @param array $context
     */
    private function log(string $level, string $message, array $context = array())
    {
        if ($this->logger instanceof LoggerInterface) {
            $this->logger->log($level, $message, $context);
        }
    }

    /**
     * @param Logger|null $logger
     */
    private function logger(LoggerInterface $logger = null)
    {
        if ($logger instanceof LoggerInterface) {
            $this->logger = $logger;
        }
    }
}
