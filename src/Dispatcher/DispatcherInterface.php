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
     * @param EventInterface $event
     * @param ListenerAcceptorInterface $acceptor
     * @return mixed
     */
    public function dispatch(EventInterface $event, ListenerAcceptorInterface $acceptor);
}