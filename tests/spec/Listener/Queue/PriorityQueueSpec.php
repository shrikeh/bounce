<?php

namespace spec\Shrikeh\Bounce\Listener\Queue;

use PhpSpec\ObjectBehavior;
use Shrikeh\Bounce\Listener\Acceptor;
use EventIO\InterOp\ListenerInterface;

class PriorityQueueSpec extends ObjectBehavior
{


    function it_accepts_a_listener_with_a_priority(
        ListenerInterface $listener
    ) {
        $this->beConstructedThroughCreate();
        $this->queue($listener, Acceptor::PRIORITY_NORMAL);
    }
}
