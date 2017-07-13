<?php

namespace Shrikeh\Bounce\Examples\Mock\Event;

use EventIO\InterOp\EventInterface;
use EventIO\InterOp\EventTrait;

/**
 * Class Bar
 * @package Shrikeh\Bounce\Examples\Mock\Event
 */
class Bar implements EventInterface
{
    use EventTrait;

    /**
     * Bar constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }
}