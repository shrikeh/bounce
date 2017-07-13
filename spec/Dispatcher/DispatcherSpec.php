<?php

namespace spec\Shrikeh\Bounce\Dispatcher;

use ArrayIterator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Shrikeh\Bounce\Dispatcher\Dispatcher;
use Shrikeh\Bounce\Event\Named;
use Shrikeh\Bounce\Event\Queue\EventQueue;
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

        $this->dispatch($event, $acceptor);
    }
}
