<?php

namespace Shrikeh\Bounce\Listener\Queue;

use EventIO\InterOp\ListenerAcceptorInterface;
use EventIO\InterOp\ListenerInterface;
use Generator;

/**
 * Interface ListenerQueueInterface
 * @package Shrikeh\Bounce\Listener\Queue
 */
interface ListenerQueueInterface
{
    /**
     * @param ListenerInterface $listener A listener to add to the queue
     * @param int               $priority A priority to queue the listener at
     * @return mixed
     */
    public function queue(
        ListenerInterface $listener,
        $priority = ListenerAcceptorInterface::PRIORITY_NORMAL
    );

    /**
     * @return Generator
     */
    public function listeners(): Generator;
}
