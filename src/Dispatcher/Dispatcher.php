<?php
namespace Shrikeh\Bounce\Dispatcher;

use EventIO\InterOp\EventInterface;
use EventIO\InterOp\ListenerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Shrikeh\Bounce\Event\Queue\EventQueue;
use Shrikeh\Bounce\Event\Queue\EventQueueInterface;
use Shrikeh\Bounce\Listener\ListenerAcceptorInterface;
use Shrikeh\Bounce\Traits\PsrLoggerTrait;

/**
 * Class Dispatcher
 * @package Shrikeh\Bounce\Dispatcher
 */
class Dispatcher implements DispatcherInterface
{
    use PsrLoggerTrait;

    const LOG_DISPATCH_LOOP_STARTING = 'Dispatch loop starting';
    const LOG_DISPATCH_LOOP_COMPLETE = 'Dispatch loop complete';

    /**
     * @var EventQueueInterface
     */
    private $queue;

    /**
     * @var bool
     */
    private $dispatching  = false;

    /**
     * @param EventQueueInterface|null $queue  A queue of events
     * @param LoggerInterface|null     $logger An optional logger
     * @return Dispatcher
     */
    public static function create(
        EventQueueInterface $queue = null,
        LoggerInterface $logger = null
    ): self {
        if (null === $queue) {
            $queue = EventQueue::create();
        }

        return new self($queue, $logger);
    }

    /**
     * Dispatcher constructor.
     * @param EventQueueInterface $queue The event queue
     * @param LoggerInterface|null $logger An optional logger
     */
    private function __construct(
        EventQueueInterface $queue,
        LoggerInterface $logger = null
    ) {
        $this->queue = $queue;
        $this->logger($logger);
    }

    /**
     * {@inheritdoc}
     */
    public function isDispatching(): bool
    {
        return $this->dispatching;
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(ListenerAcceptorInterface $acceptor)
    {
        $this->setDispatching();
        foreach ($this->queue->events() as $event) {
            $this->log(
                LogLevel::INFO,
                \sprintf(
                    'Dispatching event %s',
                    $event->name()
                )
            );
            $this->dispatchEvent($event, $acceptor);
        }
        $this->clearDispatching();
    }

    /**
     * {@inheritdoc}
     */
    public function enqueue(EventInterface ...$events)
    {
        foreach ($events as $event) {
            $this->queue->queue($event);
        }
    }


    /**
     * Set the dispatching flag to true.
     */
    public function setDispatching()
    {
        $this->dispatching = true;
        $this->log(LogLevel::INFO, self::LOG_DISPATCH_LOOP_STARTING);
    }

    /**
     * @param EventInterface $event The event to dispatch through listeners
     * @param ListenerAcceptorInterface $acceptor
     */
    private function dispatchEvent(
        EventInterface $event,
        ListenerAcceptorInterface $acceptor
    ) {
        if (!$event->isPropagationStopped()) {
            foreach ($acceptor->listenersFor($event) as $listener) {
                if ($event->isPropagationStopped()) {
                    $this->log(
                        LogLevel::INFO,
                        \sprintf(
                            'Event "%s" propagation stopped, halting propagation',
                            $event->name()
                        )
                    );

                    return;
                }
                $this->handleEvent($event, $listener);
            }
        }
    }

    /**
     * @param EventInterface $event
     * @param ListenerInterface $listener
     */
    private function handleEvent(
        EventInterface $event,
        ListenerInterface $listener
    ) {
        $this->log(
            LogLevel::INFO,
            \sprintf(
                'Passing event "%s" to Listener "%s"',
                $event->name(),
                get_class($listener)
            )
        );
        $listener->handle($event);
    }


    /**
     * Set the dispatching flag to false.
     */
    private function clearDispatching()
    {
        $this->dispatching = false;
        $this->log(LogLevel::INFO, self::LOG_DISPATCH_LOOP_COMPLETE);
    }
}
