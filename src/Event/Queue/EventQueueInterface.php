<?php

namespace Shrikeh\Bounce\Event\Queue;

use EventIO\InterOp\EventInterface as Event;
use Iterator;

interface EventQueueInterface
{
    /**
     * @param Event[] ...$events
     * @return mixed
     */
    public function queue(Event ...$events);

    /**
     * @return Iterator
     */
    public function events(): Iterator;
}
