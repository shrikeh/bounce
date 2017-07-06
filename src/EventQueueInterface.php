<?php

namespace Shrikeh\Bounce;

use Shrikeh\Bounce\EventInterface;
use Iterator;

interface EventQueueInterface
{
    public function queue(EventInterface ...$events);

    public function events(): Iterator;
}
