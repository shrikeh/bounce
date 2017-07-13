<?php

namespace Shrikeh\Bounce\Event\Queue;

use EventIO\InterOp\EventInterface;
use Iterator;

interface EventQueueInterface
{
    /**
     * @param EventInterface[] ...$events
     * @return mixed
     */
    public function queue(EventInterface ...$events);

    /**
     * @return Iterator
     */
    public function events(): Iterator;
}
