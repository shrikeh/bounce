<?php
namespace Shrikeh\Bounce\Listener;

use EventIO\InterOp\EventInterface;
use EventIO\InterOp\ListenerInterface;

/**
 * Class CallableListener
 * @package Shrikeh\Bounce\Listener
 */
class CallableListener implements ListenerInterface
{
    /**
     * @var callable
     */
    private $listener;

    /**
     * CallableListener constructor.
     * @param callable $listener A lambda or invokable object to handle the event.
     */
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
