<?php
namespace Shrikeh\Bounce\Listener;

use EventIO\InterOp\EventInterface;
use EventIO\InterOp\ListenerInterface as Listener;

class CallableListener implements Listener
{
    /**
     * @var callable
     */
    private $listener;

    public function __construct(callable $listener)
    {
        $this->listener = $listener;
    }

    /**
     * Handle an event.
     *
     * @param EventInterface $event The event being emitted
     *
     * @return void
     */
    public function handle(EventInterface $event)
    {
        $listener = $this->listener;

        $listener($event);
    }
}