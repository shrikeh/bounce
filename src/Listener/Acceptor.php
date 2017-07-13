<?php
namespace Shrikeh\Bounce\Listener;

use EventIO\InterOp\EventInterface as Event;
use Shrikeh\Bounce\Listener\ListenerAcceptorInterface as ListenerAcceptor;
use Iterator;
use Shrikeh\Bounce\Event\Map\MapInterface as Map;
use Shrikeh\Bounce\EventMapFactory;

final class Acceptor implements ListenerAcceptor
{
    /**
     * @var EventMapFactory
     */
    private $mapFactory;

    /**
     * @var MappedListeners
     */
    private $listeners;

    public static function create(
        EventMapFactory $mapFactory = null,
        MappedListeners $listeners = null
    ) {
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
     * @param Event $event
     * @return Iterator
     */
    public function listenersFor(Event $event): Iterator
    {
        return $this->listeners->listenersFor($event);
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
     * @return Map
     */
    private function createMap($eventMap): Map
    {
        if (!$eventMap instanceof Map) {
            $eventMap = $this->mapFactory->map($eventMap);
        }

        return $eventMap;
    }

    /**
     * @param Map $map
     * @param $listener
     * @param $priority
     */
    private function mapListener(Map $map, $listener, $priority)
    {
        $this->listeners->mapListener($map, $listener, $priority);
    }
}