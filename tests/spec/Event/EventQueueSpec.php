<?php

namespace spec\Shrikeh\Bounce\Event\Queue;

use EventIO\InterOp\EventInterface;
use Generator;
use PhpSpec\ObjectBehavior;

class EventQueueSpec extends ObjectBehavior
{
    function it_returns_an_iterator_of_events(
        EventInterface $event
    ) {
        $this->beConstructedThroughCreate();
        $this->queue($event);
        $this->events()->shouldBeAnInstanceOf(Generator::class);
        $this->events()->shouldIterateAs([$event]);
    }

    function it_allows_queueing_multiple_events(
        EventInterface $event1,
        EventInterface $event2,
        EventInterface $event3
    ) {
        $this->beConstructedThroughCreate();
        $this->queue($event1, $event2)->shouldIterateAs([$event1, $event2]);
        $this->queue($event3);
        $this->events()->shouldIterateAs([$event3]);
    }
}
