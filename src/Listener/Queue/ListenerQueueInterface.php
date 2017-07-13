<?php

namespace Shrikeh\Bounce\Listener\Queue;

use \EventIO\InterOp\ListenerInterface;
use \Generator;

interface ListenerQueueInterface
{
    /**
     * @param ListenerInterface $listener
     * @param $priority
     * @return mixed
     */
    public function queue(ListenerInterface $listener, $priority);

    /**
     * @return Generator
     */
    public function listeners(): Generator;
}