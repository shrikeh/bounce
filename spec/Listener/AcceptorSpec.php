<?php

namespace spec\Shrikeh\Bounce\Listener;

use EventIO\InterOp\ListenerInterface;
use PhpSpec\ObjectBehavior;
use Shrikeh\Bounce\Event\Map\MapInterface;
use Shrikeh\Bounce\EventMapFactory;
use Shrikeh\Bounce\Listener\Acceptor;
use Shrikeh\Bounce\Listener\MappedListeners;
use SplPriorityQueue;

class AcceptorSpec extends ObjectBehavior
{
    function let(
        EventMapFactory $factory,
        MappedListeners $storage
    ) {
        $this->beConstructedThroughCreate($factory, $storage);
    }

    function it_creates_a_map_for_an_event_id(
        ListenerInterface $listener,
        MapInterface $map,
        SplPriorityQueue $listeners,
        $factory,
        $storage
    ) {
        $mapId = 'foo.bar';
        $factory->map($mapId)->willReturn($map);
        $storage->contains($map)->willReturn(true);
        $storage->offsetGet($map)->willReturn($listeners);

        $this->addListener(
            $mapId,
            $listener
        );

        $listeners->insert($listener, Acceptor::PRIORITY_NORMAL)->shouldBeCalled();
    }


}
