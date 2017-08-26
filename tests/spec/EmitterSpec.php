<?php

namespace spec\Shrikeh\Bounce;

use Shrikeh\Bounce\Emitter;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EmitterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Emitter::class);
    }
}
