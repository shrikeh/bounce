<?php
namespace Shrikeh\Bounce\Listener;

use EventIO\InterOp\EventInterface;

interface ListenerAcceptorInterface extends \EventIO\InterOp\ListenerAcceptorInterface
{
    public function listenersFor(EventInterface $event);
}