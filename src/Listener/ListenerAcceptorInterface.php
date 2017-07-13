<?php
namespace Shrikeh\Bounce\Listener;

use EventIO\InterOp\EventInterface;

/**
 * Interface ListenerAcceptorInterface
 * @package Shrikeh\Bounce\Listener
 */
interface ListenerAcceptorInterface extends \EventIO\InterOp\ListenerAcceptorInterface
{
    /**
     * @param EventInterface $event The event to find listeners for.
     * @return mixed
     */
    public function listenersFor(EventInterface $event);
}
