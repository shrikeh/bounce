<?php

namespace Shrikeh\Bounce\Examples\Mock\Listener;

use EventIO\InterOp\EventInterface;
use EventIO\InterOp\ListenerInterface;

class Foo implements ListenerInterface
{
    private $string;

    public function __construct(string $string)
    {
        $this->string = $string;
    }

    /**
     * Handle an event.
     *
     * @param EventInterface $event The event being emitted
     *
     * @return string
     */
    public function handle(EventInterface $event)
    {
        echo $this->string . "\n";
    }
}