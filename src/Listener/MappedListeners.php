<?php
namespace Shrikeh\Bounce\Listener;

use ArrayObject;
use EventIO\InterOp\EventInterface;
use EventIO\InterOp\ListenerAcceptorInterface;
use Generator;
use Shrikeh\Bounce\Event\Map\MapInterface;
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

    /**
     * @param SplObjectStorage|null $mappedListeners
     * @return MappedListeners
     */
    public static function create(
        SplObjectStorage $mappedListeners = null
    ): self {
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
     * @param MapInterface $map
     * @param $listener
     * @param int $priority
     */
    public function mapListener(
        MapInterface $map,
        $listener,
        $priority = ListenerAcceptorInterface::PRIORITY_NORMAL
    ) {
        $mappedListeners = $this->listenersForMap($map);

        if (!$mappedListeners->contains($listener)) {
            $mappedListeners->attach($listener, new ArrayObject());
        }

        $mappedListeners->offsetGet($listener)->append($priority);
    }

    /**
     * @param EventInterface $event
     * @return Generator
     */
    public function listenersFor(EventInterface $event): Generator
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
     * @param MapInterface $map
     * @return SplObjectStorage
     */
    private function listenersForMap(MapInterface $map): SplObjectStorage
    {
        if (!$this->mappedListeners->contains($map)) {
            $this->mappedListeners->attach($map, new SplObjectStorage());
        }

        return $this->mappedListeners->offsetGet($map);
    }
}


