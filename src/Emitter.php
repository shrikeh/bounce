<?php

namespace Shrikeh\Bounce;

use EventIO\InterOp\EmitterInterface;
use EventIO\InterOp\EmitterTrait;
use EventIO\InterOp\EventInterface;
use EventIO\InterOp\ListenerAcceptorInterface;
use Shrikeh\Bounce\Dispatcher\Dispatcher;
use Shrikeh\Bounce\Dispatcher\DispatcherInterface;
use Shrikeh\Bounce\Event\Named;
use Shrikeh\Bounce\Listener\Acceptor;

/**
 * Class Emitter
 * @package Shrikeh\Bounce
 */
final class Emitter implements EmitterInterface, ListenerAcceptorInterface
{
    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * @var ListenerAcceptorInterface
     */
    private $listeners;

    /**
     * @param ListenerAcceptorInterface|null $listeners  A listener acceptor
     * @param DispatcherInterface|null       $dispatcher A dispatcher
     * @return Emitter
     */
    public static function create(
        ListenerAcceptorInterface $listeners = null,
        DispatcherInterface $dispatcher = null
    ): self {
        if (null === $listeners) {
            $listeners = Acceptor::create();
        }
        if (null === $dispatcher) {
            $dispatcher = Dispatcher::create();
        }

        return new self($listeners, $dispatcher);
    }

    /**
     * Emitter constructor.
     * @param DispatcherInterface       $dispatcher An event dispatcher
     * @param ListenerAcceptorInterface $listeners A listener acceptor
     */
    private function __construct(
        ListenerAcceptorInterface $listeners,
        DispatcherInterface $dispatcher
    ) {
        $this->listeners    = $listeners;
        $this->dispatcher   = $dispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function emit(...$events)
    {
        $this->dispatcher->setDispatching();

        foreach ($events as $event) {
            $this->parseEvent($event);
        }

        $this->dispatchListeners();
    }

    /**
     * {@inheritdoc}
     */
    public function emitEvent(EventInterface $event)
    {
        $this->dispatcher->enqueue($event);
        $this->dispatchEvents();
    }


    /**
     * {@inheritdoc}
     */
    public function emitName($event)
    {
        return $this->emitEvent($this->createNamedEvent($event));
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
            $listener,
            $priority
        );
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
     * Run the dispatcher if we aren't emitting
     */
    private function dispatchEvents()
    {
        if (!$this->dispatcher->isDispatching()) {
            $this->dispatchListeners();
        }
    }

    /**
     *
     */
    private function dispatchListeners()
    {
        $this->dispatcher->dispatch($this->listeners);
    }

    /**
     * @param mixed $event
     * @return mixed
     */
    private function parseEvent($event)
    {
        if ($event instanceof EventInterface) {
            return $this->emitEvent($event);
        }

        return $this->emitName($event);
    }
}
