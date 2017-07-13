<?php

namespace Shrikeh\Bounce\Listener\Queue;

use \EventIO\InterOp\ListenerInterface as Listener;
use \Generator;

interface ListenerQueueInterface
{
    /**
     * @param ListenerInterface $listener
     * @param $priority
     * @return mixed
     */
    public function queue(Listener $listener, $priority);

    /**
     * @return Generator
     */
    public function listeners(): Generator;
}