<?php
namespace Shrikeh\Bounce\Listener;

use ArrayObject;
use Generator;
use Shrikeh\Bounce\Event\MapInterface;
use Shrikeh\Bounce\EventInterface;
use Shrikeh\Bounce\Listener\Queue\PriorityQueue;
use SplObjectStorage;

class MappedListeners
{
    /**
     * @var SplObjectStorage
     */
    private $mappedListeners;

    /**
     * @param SplObjectStorage|null $mappedListeners
     * @return self
     */
    public static function create(SplObjectStorage $mappedListeners = null)
    {
        if (null === $mappedListeners) {
            $mappedListeners = new SplObjectStorage();
        }

        return new self($mappedListeners);
    }

    /**
     * MappedListeners constructor.
     * @param SplObjectStorage $mappedListeners
     */
    private function __construct(SplObjectStorage $mappedListeners)
    {
        $this->mappedListeners = $mappedListeners;
        $this->queue            = PriorityQueue::create();
    }

    /**
     * @param MapInterface $map
     * @param $listener
     * @param int $priority
     */
    public function mapListener(MapInterface $map, $listener, $priority = 1)
    {
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
        foreach ($this->maps() as $map) {
            if ($map->isMatch($event)) {
                yield from $this->listeners($map);
            }
        }
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
     * @return Generator
     */
    private function listeners(MapInterface $map)
    {
        $listeners = $this->mappedListeners->offsetGet($map);

        foreach ($listeners as $listener) {
            foreach ($listeners->offsetGet($listener) as $priority) {
                yield $listener => $priority;
            }
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


