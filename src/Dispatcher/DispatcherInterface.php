<?php
namespace Shrikeh\Bounce\Dispatcher;

use EventIO\InterOp\EventInterface as Event;
use Shrikeh\Bounce\Listener\ListenerAcceptorInterface;

/**
 * Interface DispatcherInterface
 * @package Shrikeh\Bounce\Dispatcher
 */
interface DispatcherInterface
{
    public function dispatch(Event $event, ListenerAcceptorInterface $acceptor);
}