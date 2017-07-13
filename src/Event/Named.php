<?php

namespace Shrikeh\Bounce\Event;

use \EventIO\InterOp\EventInterface as Event;

/**
 * Class NamedEvent
 * @package Shrikeh\Bounce\Event
 */
final class Named implements Event
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var boolean
     */
    private $propogationStopped = false;

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
        $this->name                 = $name;
    }

    /**
     * The name of the event
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Check whether propagation was stopped.
     *
     * @return bool
     */
    public function isPropagationStopped(): bool
    {
        return ($this->propogationStopped);
    }

    /**
     * @return self
     */
    public function stopPropagation(): self
    {
        $this->propogationStopped = true;

        return $this;
    }
}
