<?php

namespace Shrikeh\Bounce\Listener\Queue;

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
     * @var SplPriorityQueue
     */
    private $prioritizedQueue;

    /**
     * @param SplPriorityQueue|null $priorityQueue An existing queue
     * @return self
     */
    public static function create(SplPriorityQueue $priorityQueue = null)
    {
        if (null === $priorityQueue) {
            $priorityQueue = new SplPriorityQueue();
        }

        return new self($priorityQueue);
    }

    /**
     * ListenerQueue constructor.
     * @param SplPriorityQueue $prioritizedQueue
     */
    private function __construct(SplPriorityQueue $prioritizedQueue)
    {
        $this->prioritizedQueue = $prioritizedQueue;
    }

    /**
     * {@inheritdoc}
     */
    public function listeners(): Generator
    {
        $this->prioritizedQueue->setExtractFlags(SplPriorityQueue::EXTR_DATA);

        while ($this->prioritizedQueue->valid()) {
            $listeners = $this->prioritizedQueue->extract();
            while (!$listeners->isEmpty()) {
                yield $listeners->dequeue();
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function queue(ListenerInterface $listener, $priority = ListenerAcceptorInterface::PRIORITY_NORMAL)
    {
        $prioritizedQueue   = new SplPriorityQueue();
        $queue              = $this->createQueue();

        $prioritizedQueue->insert($queue, $priority);


        $this->prioritizedQueue->setExtractFlags(SplPriorityQueue::EXTR_BOTH);

        while (!$this->prioritizedQueue->isEmpty()) {
            $listenerQueue = $this->prioritizedQueue->extract();

            $listeners      = $listenerQueue['data'];
            $queuePriority  = $listenerQueue['priority'];
            if ($this->compare($queuePriority, $priority)) {
                $this->addToQueue($queue, $listeners);
                continue;
            }

            $prioritizedQueue->insert($listeners, $queuePriority);
        }

        $queue->enqueue($listener);

        $this->prioritizedQueue = $prioritizedQueue;
    }

    /**
     * @param $queue
     * @param $listeners
     * @return SplQueue
     */
    private function addToQueue($queue, $listeners): SplQueue
    {
        foreach ($listeners as $listener) {
            $queue->enqueue($listener);
        }

        return $queue;
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

    /**
     * @param $listenerPriority
     * @param $priority
     * @return bool
     */
    private function compare($listenerPriority, $priority): bool
    {
        return ($this->prioritizedQueue->compare($listenerPriority, $priority) === 0);
    }
}
