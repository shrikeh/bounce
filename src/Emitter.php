<?php

namespace Shrikeh\Bounce;

use EventIO\InterOp\EmitterInterface;
use EventIO\InterOp\EmitterTrait;
use EventIO\InterOp\EventInterface as Event;
use EventIO\InterOp\ListenerAcceptorInterface as ListenerAcceptor;
use EventIO\InterOp\ListenerInterface as Listener;
use Shrikeh\Bounce\Dispatcher\DispatcherInterface as Dispatcher;
use Shrikeh\Bounce\Event\Named;
use Shrikeh\Bounce\Listener\CallableListener;

/**
 * Class Emitter
 * @package Shrikeh\Bounce
 */
final class Emitter implements EmitterInterface, ListenerAcceptor
{

    use EmitterTrait;
    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * @var ListenerAcceptor
     */
    private $listeners;

    /**
     * Emitter constructor.
     * @param Dispatcher $dispatcher
     * @param ListenerAcceptor $listeners
     */
    public function __construct(
        ListenerAcceptor $listeners,
        Dispatcher $dispatcher
    ) {
        $this->listeners    = $listeners;
        $this->dispatcher   = $dispatcher;
    }


    /**
     * {@inheritdoc}
     */
    public function emitEvent(Event $event)
    {
        $this->dispatcher->dispatch($event, $this->listeners);
    }

    /**
     * {@inheritdoc}
     */
    public function addListener(
        $eventMap,
        $listener,
        $priority = self::PRIORITY_NORMAL
    ) {

        $this->listeners->addListener(
            $eventMap,
            $this->listener($listener),
            $priority
        );
    }

    /**
     * {@inheritdoc}
     */
    public function emitName($event)
    {
        return $this->emitEvent($this->createNamedEvent($event));
    }

    /**
     * @param string $event
     * @return Named
     */
    private function createNamedEvent(string $event): Named
    {
        return Named::create($event);
    }

    /**
     * @param $listener
     * @return Listener
     */
    private function listener($listener): Listener
    {
        if (!$listener instanceof Listener) {
            $listener = new CallableListener($listener);

        }

        return $listener;
    }

}
