<?php

namespace spec\Shrikeh\Bounce;

use Shrikeh\Bounce\Event\Map\Glob;
use Shrikeh\Bounce\EventMapFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EventMapFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(EventMapFactory::class);
    }

    function it_returns_a_glob_from_a_string()
    {
        $this->glob('foo')->shouldBeAnInstanceOf(Glob::class);
    }
}
