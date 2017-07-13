<?php

namespace Shrikeh\Bounce\Event;

use EventIO\InterOp\EventInterface;
use EventIO\InterOp\EventTrait;

/**
 * Class NamedEvent
 * @package Shrikeh\Bounce\Event
 */
final class Named implements EventInterface
{
    use EventTrait;

    /**
     * @param string $name The name of the event
     * @return Named
     */
    public static function create(string $name): self
    {
        return new self($name);
    }

    /**
     * NamedEvent constructor.
     * @param string $name
     */
    private function __construct(string $name)
    {
        $this->name = $name;
    }
}
