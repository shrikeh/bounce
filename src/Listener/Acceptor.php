<?php
namespace Shrikeh\Bounce\Listener;

use EventIO\InterOp\EventInterface;
use EventIO\InterOp\ListenerInterface;
use Iterator;
use Shrikeh\Bounce\Event\Map\MapInterface;
use Shrikeh\Bounce\EventMapFactory;

/**
 * Class Acceptor
 * @package Shrikeh\Bounce\Listener
 */
final class Acceptor implements ListenerAcceptorInterface
{
    /**
     * @var EventMapFactory
     */
    private $mapFactory;

    /**
     * @var MappedListeners
     */
    private $listeners;

    /**
     * @param EventMapFactory|null $mapFactory A map factory to create maps from strings
     * @param MappedListeners|null $listeners  A mapped listener storage engine
     * @return self
     */
    public static function create(
        EventMapFactory $mapFactory = null,
        MappedListeners $listeners = null
    ): self {
        if (null === $mapFactory) {
            $mapFactory = new EventMapFactory();
        }

        if (null === $listeners) {
            $listeners =  MappedListeners::create();
        }

        return new self($mapFactory, $listeners);
    }

    /**
     * Acceptor constructor.
     * @param EventMapFactory $mapFactory
     * @param MappedListeners $listeners
     */
    private function __construct(
        EventMapFactory $mapFactory,
        MappedListeners $listeners
    ) {
        $this->mapFactory   = $mapFactory;
        $this->listeners    = $listeners;
    }

    /**
     * @param EventInterface $event The triggered event
     * @return Iterator
     */
    public function listenersFor(EventInterface $event): Iterator
    {
        yield from $this->listeners->listenersFor($event);
    }

    /**
     * {@inheritdoc}
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
     * @return MapInterface
     */
    private function createMap($eventMap): MapInterface
    {
        if (!$eventMap instanceof MapInterface) {
            $eventMap = $this->mapFactory->map($eventMap);
        }

        return $eventMap;
    }

    /**
     * @param MapInterface $map The map for the listener
     * @param $listener A listener
     * @param $priority
     */
    private function mapListener(MapInterface $map, $listener, $priority)
    {
        if (!$listener instanceof ListenerInterface) {
            $listener = new CallableListener($listener);
        }

        $this->listeners->mapListener($map, $listener, $priority);
    }
}
