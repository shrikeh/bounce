<?php
namespace Shrikeh\Bounce\Listener;

use ArrayObject;
use EventIO\InterOp\EventInterface as Event;
use Generator;
use Shrikeh\Bounce\Event\Map\MapInterface as Map;
use Shrikeh\Bounce\Listener\Queue\PriorityQueue;
use SplObjectStorage;

/**
 * Class MappedListeners
 * @package Shrikeh\Bounce\Listener
 */
class MappedListeners
{
    /**
     * @var SplObjectStorage
     */
    private $mappedListeners;


    public static function create(
        SplObjectStorage $mappedListeners = null
    ) {
        if (null === $mappedListeners) {
            $mappedListeners = new SplObjectStorage();
        }

        return new self($mappedListeners);
    }

    /**
     * MappedListeners constructor.
     * @param SplObjectStorage $mappedListeners
     */
    private function __construct(
        SplObjectStorage $mappedListeners
    ) {
        $this->mappedListeners = $mappedListeners;
    }

    /**
     * @param Map $map
     * @param $listener
     * @param int $priority
     */
    public function mapListener(Map $map, $listener, $priority = 1)
    {
        $mappedListeners = $this->listenersForMap($map);

        if (!$mappedListeners->contains($listener)) {
            $mappedListeners->attach($listener, new ArrayObject());
        }

        $mappedListeners->offsetGet($listener)->append($priority);
    }

    /**
     * @param Event $event
     * @return Generator
     */
    public function listenersFor(Event $event): Generator
    {
        $queue = PriorityQueue::create();

        foreach ($this->maps() as $map) {
            if ($map->isMatch($event)) {
                $listeners = $this->mappedListeners->offsetGet($map);
                foreach ($listeners as $listener) {
                    foreach ($listeners->offsetGet($listener) as $priority) {
                        $queue->queue($listener, $priority);
                    }
                }
            }
        }
        yield from $queue->listeners();
    }

    /**
     * @return Generator
     */
    private function maps(): Generator
    {
        foreach ($this->mappedListeners as $map) {
            yield $map;
        }
    }


    /**
     * @param Map $map
     * @return SplObjectStorage
     */
    private function listenersForMap(Map $map): SplObjectStorage
    {
        if (!$this->mappedListeners->contains($map)) {
            $this->mappedListeners->attach($map, new SplObjectStorage());
        }

        return $this->mappedListeners->offsetGet($map);
    }
}


