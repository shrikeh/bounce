<?php

namespace Shrikeh\Bounce;

use EventInterface as Event;

interface EmitterInterface
{
    public function emit(EventInterface $event);
}
