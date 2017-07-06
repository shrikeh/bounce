<?php

namespace Shrikeh\Bounce\Listener\Queue;

use Generator;
use SplPriorityQueue;
use SplQueue;

class PriorityQueue
{
    /**
     * @var SplPriorityQueue
     */
    private $prioritizedQueue;

    /**
     * @param SplPriorityQueue|null $priorityQueue
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
     * @return Generator
     */
    public function listeners(): Generator
    {
        $this->prioritizedQueue->setExtractFlags(SplPriorityQueue::EXTR_DATA);
        $this->prioritizedQueue->rewind();
        foreach ($this->prioritizedQueue as $listeners) {
            while (!$listeners->isEmpty()) {
                yield $listeners->dequeue();
            }
        }
    }

    /**
     * @param $listener
     * @param $priority
     */
    public function queue($listener, $priority)
    {
        $prioritizedQueue   = new SplPriorityQueue();
        $queue              = $this->createQueue();

        $prioritizedQueue->insert($queue, $priority);

        $this->prioritizedQueue->setExtractFlags(SplPriorityQueue::EXTR_BOTH);
        $this->prioritizedQueue->rewind();
        foreach($this->prioritizedQueue as $listenerQueue) {
            $listeners = $listenerQueue['data'];
            if ($this->compare($listenerQueue['priority'], $priority)) {
                $this->addToQueue($queue, $listeners);

                continue;
            }

            $prioritizedQueue->insert($listeners, $priority);
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
