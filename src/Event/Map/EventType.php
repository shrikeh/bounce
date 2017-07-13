<?php
namespace Shrikeh\Bounce\Event\Map;

use EventIO\InterOp\EventInterface;

/**
 * Class EventType
 * @package Shrikeh\Bounce\Event\Map
 */
final class EventType implements MapInterface
{
    /**
     * @var string
     */
    private $eventType;

    /**
     * EventType constructor.
     * @param string $eventType A class type to look for
     */
    public function __construct(string $eventType)
    {
        if (!(interface_exists($eventType) || class_exists($eventType))) {
            $msg = 'No such interface or class as %s exists';
            throw new \RuntimeException(sprintf($msg, $eventType));
        }
        $this->eventType = $eventType;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->eventType;
    }

    /**
     * {@inheritdoc}
     */
    public function isMatch(EventInterface $event): bool
    {
        // see https://veewee.github.io/blog/optimizing-php-performance-by-fq-function-calls/
        return (\is_a($event, $this->eventType));
    }

    /**
     * {@inheritdoc}
     */
    public function index(): string
    {
        return (string) (self::class . '|' . $this);
    }
}
