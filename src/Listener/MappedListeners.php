<?php
namespace Shrikeh\Bounce\Listener;

use ArrayObject;
use EventIO\InterOp\EventInterface;
use EventIO\InterOp\ListenerAcceptorInterface;
use EventIO\InterOp\ListenerInterface;
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

    private $queue;

    /**
     * @param SplObjectStorage|null $mappedListeners Existing storage if any
     * @param PriorityQueue|null    $queue           A queue to use
     * @return MappedListeners
     */
    public static function create(
        SplObjectStorage $mappedListeners = null,
        PriorityQueue $queue = null
    ): self {
        if (null === $mappedListeners) {
            $mappedListeners = new SplObjectStorage();
        }

        if (null === $queue) {
            $queue = PriorityQueue::create();
        }


        return new self($mappedListeners, $queue);
    }

    /**
     * MappedListeners constructor.
     * @param SplObjectStorage $mappedListeners Storage for the mapped listeners
     * @param PriorityQueue|null $queue A queue to use
     */
    private function __construct(
        SplObjectStorage $mappedListeners,
        PriorityQueue $queue
    ) {
        $this->mappedListeners = $mappedListeners;
        $this->queue = $queue;
    }

    /**
     * @param MapInterface      $map      A map for events
     * @param ListenerInterface $listener A listener to map
     * @param int               $priority The priority of the listener in the queue
     */
    public function mapListener(
        MapInterface $map,
        ListenerInterface $listener,
        $priority = ListenerAcceptorInterface::PRIORITY_NORMAL
    ) {
        $mappedListeners = $this->listenersForMap($map);

        if (!$mappedListeners->contains($listener)) {
            $mappedListeners->attach($listener, new ArrayObject());
        }

        $mappedListeners->offsetGet($listener)->append($priority);
    }

    /**
     * @param EventInterface $event The event to return listeners for
     * @return Generator
     */
    public function listenersFor(EventInterface $event): Generator
    {
        foreach ($this->maps() as $map) {
            if ($map->isMatch($event)) {
                $listeners = $this->mappedListeners->offsetGet($map);
                foreach ($listeners as $listener) {
                    foreach ($listeners->offsetGet($listener) as $priority) {
                        $this->queue->queue($listener, $priority);
                    }
                }
            }
        }

        yield from $this->queue->listeners();
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
     * @param MapInterface $map A map to look up listeners for
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
