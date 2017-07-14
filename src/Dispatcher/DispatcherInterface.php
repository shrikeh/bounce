<?php
namespace Shrikeh\Bounce\Dispatcher;

use EventIO\InterOp\EventInterface;
use Shrikeh\Bounce\Listener\ListenerAcceptorInterface;

/**
 * Interface DispatcherInterface
 * @package Shrikeh\Bounce\Dispatcher
 */
interface DispatcherInterface
{
    /**
     * @param ListenerAcceptorInterface $acceptor The acceptor of listeners
     * @return mixed
     */
    public function dispatch(ListenerAcceptorInterface $acceptor);

    /**
     * @param EventInterface[] ...$events One or more events to queue
     * @return mixed
     */
    public function enqueue(EventInterface ...$events);
}
