<?php
namespace Shrikeh\Bounce\Listener;

use Shrikeh\Bounce\Event\MapInterface as Map;
use Shrikeh\Bounce\EventMapFactory;
use SplObjectStorage;
use SplPriorityQueue;

final class Acceptor
{
    const PRIORITY_NORMAL = 1;

    /**
     * @var EventMapFactory
     */
    private $mapFactory;

    /**
     * @var SplObjectStorage
     */
    private $listeners;

    /**
     * Acceptor constructor.
     * @param EventMapFactory $mapFactory
     * @param SplObjectStorage $listeners
     */
    public function __construct(
        EventMapFactory $mapFactory,
        SplObjectStorage $listeners
    ) {
        $this->mapFactory   = $mapFactory;
        $this->listeners    = $listeners;
    }


    /**
     * @param $eventMap
     * @param $listener
     * @param int $priority
     */
    public function addListener(
        $eventMap,
        $listener,
        $priority = self::PRIORITY_NORMAL
    ) {
        $this->mapListener(
            $this->createMap($eventMap),
            $listener,
            $priority
        );
    }

    /**
     * @param $eventMap
     * @return mixed
     */
    private function createMap($eventMap)
    {
        if (!$eventMap instanceof Map) {
            $eventMap = $this->mapFactory->map($eventMap);
        }

        return $eventMap;
    }

    /**
     * @param MapInterface $map
     * @param $listener
     * @param $priority
     */
    private function mapListener(Map $map, $listener, $priority)
    {
        $this->collectionFor($map)->insert($listener, $priority);
    }

    /**
     * @param MapInterface $map
     * @return SplPriorityQueue
     */
    private function collectionFor(Map $map): SplPriorityQueue
    {
        if (!$this->listeners->contains($map)) {
            $this->listeners->attach($map, new SplPriorityQueue());
        }

        return $this->listeners->offsetGet($map);
    }
}