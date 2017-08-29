<?php

namespace spec\Shrikeh\Bounce;

use EventIO\InterOp\EventInterface;
use Shrikeh\Bounce\Dispatcher\DispatcherInterface;
use Shrikeh\Bounce\Emitter;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Shrikeh\Bounce\Listener\ListenerAcceptorInterface;

class EmitterSpec extends ObjectBehavior
{
    function let(
        ListenerAcceptorInterface $acceptor,
        DispatcherInterface $dispatcher
    ) {
        $this->beConstructedThroughCreate($acceptor, $dispatcher);
    }

    function it_emits_events(
        EventInterface $event,
        ListenerAcceptorInterface $acceptor,
        DispatcherInterface $dispatcher
    ) {
        $dispatcher->isDispatching()->willReturn(false);

        $dispatcher->setDispatching()->shouldBeCalled();
        $dispatcher->enqueue($event)->shouldBeCalled();
        $dispatcher->dispatch($acceptor)->shouldBeCalled();

        $this->emit($event);
    }
}
