<?php
namespace Shrikeh\Bounce\Event\Map;

use EventIO\InterOp\EventInterface;

final class EventType implements MapInterface
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
