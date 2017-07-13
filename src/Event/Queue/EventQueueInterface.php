<?php

namespace Shrikeh\Bounce\Event\Queue;

use EventIO\InterOp\EventInterface;
use Iterator;

/**
 * Interface EventQueueInterface
 * @package Shrikeh\Bounce\Event\Queue
 */
interface EventQueueInterface
{
    /**
     * @param EventInterface[] ...$events The events to queue
     * @return mixed
     */
    public function queue(EventInterface ...$events);

    /**
     * @return Iterator
     */
    public function events(): Iterator;
}
