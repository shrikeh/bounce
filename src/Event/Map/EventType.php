<?php
namespace Shrikeh\Bounce\Event\Map;

use \EventIO\InterOp\EventInterface as Event;
use \Shrikeh\Bounce\Event\Map\MapInterface as Map;

final class EventType implements Map
{
    /**
     * @var string
     */
    private $eventType;

    /**
     * EventType constructor.
     * @param string $eventType
     */
    public function __construct(string $eventType)
    {
        $this->eventType = $eventType;
    }

    public function __toString()
    {
        return $this->eventType;
    }

    /**
     * {@inheritdoc}
     */
    public function isMatch(Event $event): bool
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
