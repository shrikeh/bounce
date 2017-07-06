<?php

namespace Shrikeh\Bounce;

use EventInterface as Event;
use EventQueueInterface as EventQueue;

final class Emitter
{
    private $eventQueue;

    private $listeners;

    /**
     * @var boolean
     */
    private $dispatching;

    public function __construct(
        EventQueue $eventQueue,
        ListenerAcceptor $listeners
    ) {
      $this->eventQueue = $eventQueue;
      $this->listeners  = $listeners;
    }


    public function emit(Event $event)
    {
        $this->eventQueue->queue($event);
        $this->emitEvents();
    }

    public function emitEvents()
    {
        if (!$this->isDispatching()) {
            $this->setDispatching();
            foreach ($this->eventQueue->events() as $event) {
                $this->dispatchEvent($event);
            }
            $this->clearDispatching();
        }
    }

    /**
     * Whether we are in a dispatch loop
     * @return bool
     */
    public function isDispatching(): bool
    {
        return $this->dispatching;
    }

    /**
     * Set the dispatching flag to true.
     */
    private function setDispatching()
    {
        $this->dispatching = true;
    }

    /**
     * Set the dispatching flag to false.
     */
    private function clearDispatching()
    {
        $this->dispatching = false;
    }

    /**
     * @param Event $event
     */
    private function dispatchEvent(Event $event)
    {
        //$this->listeners->for($event)
    }

}
