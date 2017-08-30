<?php

namespace spec\Shrikeh\Bounce\Dispatcher;

use ArrayIterator;
use EventIO\InterOp\EventInterface;
use Generator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Shrikeh\Bounce\Dispatcher\Dispatcher;
use Shrikeh\Bounce\Event\Map\Glob;
use Shrikeh\Bounce\Event\Named;
use Shrikeh\Bounce\Listener\Acceptor;
use Shrikeh\Bounce\Listener\CallableListener;
use Shrikeh\Bounce\Listener\ListenerAcceptorInterface;

class DispatcherSpec extends ObjectBehavior
{
    function it_logs_the_event_cycle_to_a_logger(
        LoggerInterface $logger,
        ListenerAcceptorInterface $acceptor
    ) {
        $eventName = 'event1';
        $this->beConstructedThroughCreate(null, $logger);
        $event = Named::create($eventName);

        $acceptor->listenersFor($event)->willReturn(new ArrayIterator());

        $logger->log(LogLevel::INFO, Dispatcher::LOG_DISPATCH_LOOP_STARTING, [])->shouldBeCalled();
        $logger->log(LogLevel::INFO, Argument::containingString($eventName), [])->shouldBeCalled();
        $logger->log(LogLevel::INFO, Dispatcher::LOG_DISPATCH_LOOP_COMPLETE, [])->shouldBeCalled();
        $this->enqueue($event);
        $this->dispatch($acceptor);
    }

    function it_dispatches_queued_events(
        ListenerAcceptorInterface $acceptor
    ) {
        $this->beConstructedThroughCreate();
        $event1 = Named::create('event1');
        $event2 = Named::create('event2');

        $this->enqueue($event1, $event2);
        $acceptor->listenersFor(Argument::type(EventInterface::class))->willReturn(new ArrayIterator());
        $this->dispatch($acceptor);
        $acceptor->listenersFor($event1)->shouldHaveBeenCalled();
        $acceptor->listenersFor($event2)->shouldHaveBeenCalled();
    }

    function it_does_not_propogate_stopped_events(
        ListenerAcceptorInterface $acceptor
    ) {
        $this->beConstructedThroughCreate();
        $event = Named::create('event.foo');
        $event->stopPropagation();

        $this->enqueue($event);
        $this->dispatch($acceptor);
        $acceptor->listenersFor($event)->shouldNotHaveBeenCalled();

    }

    function it_checks_if_an_event_has_been_stopped(
    ) {
        $this->beConstructedThroughCreate();
        $event = Named::create('event.foo');
        $this->enqueue($event);

        $acceptor = Acceptor::create();
        $map = new Glob('event.foo');
        $listener1 = new CallableListener(function(EventInterface $event) {
            $event->stopPropagation();
        });

        $listener2 = new CallableListener(function(EventInterface $event) {
            die('we should never get to this');
        });

        $acceptor->addListener($map, $listener1);
        $acceptor->addListener($map, $listener2);

        $this->dispatch($acceptor);
    }


}
