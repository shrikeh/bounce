<?php

namespace Shrikeh\Bounce\Listener\Queue;

use ArrayObject;
use EventIO\InterOp\ListenerAcceptorInterface;
use EventIO\InterOp\ListenerInterface;
use Generator;
use SplPriorityQueue;
use SplQueue;

/**
 * Class PriorityQueue
 * @package Shrikeh\Bounce\Listener\Queue
 */
class PriorityQueue implements ListenerQueueInterface
{
    /**
     * @var ArrayObject
     */
    private $prioritizedListeners;

    const QUEUE_DATA        = 'data';
    const QUEUE_PRIORITY    = 'priority';

    /**
     * @param ArrayObject|null $prioritizedListeners An existing queue
     * @return self
     */
    public static function create(ArrayObject $prioritizedListeners = null)
    {
        if (null === $prioritizedListeners) {
            $prioritizedListeners = new ArrayObject();
        }

        return new self($prioritizedListeners);
    }

    /**
     * ListenerQueue constructor.
     * @param ArrayObject $prioritizedListeners
     */
    private function __construct(ArrayObject $prioritizedListeners)
    {
        $this->prioritizedListeners = $prioritizedListeners;
    }

    /**
     * {@inheritdoc}
     */
    public function listeners(): Generator
    {
        $prioritizedQueue = new SplPriorityQueue();

        foreach ($this->prioritizedListeners as $priority => $queue) {
            $prioritizedQueue->insert($queue, $priority);
        }

        while (!$prioritizedQueue->isEmpty()) {
            $listeners = $prioritizedQueue->extract();
            while (!$listeners->isEmpty()) {
                yield $listeners->dequeue();
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function queue(
        ListenerInterface $listener,
        $priority = ListenerAcceptorInterface::PRIORITY_NORMAL
    ) {

        if (!$this->prioritizedListeners->offsetExists($priority)) {
            $this->prioritizedListeners->offsetSet($priority, $this->createQueue());
        }

        $this->prioritizedListeners->offsetGet($priority)->enqueue($listener);
    }

    /**
     * @return SplQueue
     */
    private function createQueue(): SplQueue
    {
        $queue = new SplQueue();
        $queue->setIteratorMode(SplQueue::IT_MODE_FIFO | SplQueue::IT_MODE_DELETE);

        return $queue;
    }
}
