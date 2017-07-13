<?php
namespace Shrikeh\Bounce\Event\Queue;

use \EventIO\InterOp\EventInterface;
use \Generator;
use \Iterator;
use \SplQueue;

final class EventQueue implements EventQueueInterface
{
    /**
     * @var EventQueue
     */
    private $queue;


    /**
     * @param Iterator|null $events
     * @return EventQueue
     */
    public static function create(Iterator $events = null)
    {
        $queue = new SplQueue();
        $queue->setIteratorMode(SplQueue::IT_MODE_DELETE);

        $eventQueue = new self($queue);
        if ($events) {
            foreach ($events as $event) {
                $eventQueue->queue($event);
            }
        }

        return $eventQueue;
    }

    /**
     * EventQueue constructor.
     * @param EventQueue $queue
     */
    private function __construct(SplQueue $queue)
    {
        $this->queue = $queue;
    }


    /**
     * Add an event to the event queue
     * @param EventInterface $event The event to add
     * @return Generator
     */
    public function queue(EventInterface ...$events)
    {
        foreach ($events as $event) {
            $this->queue->enqueue($event);
        }

        return $this->generate();
    }


    /**
     * @return Iterator
     */
    public function events(): Iterator
    {
        return $this->generate();
    }

    /**
     * @return Generator
     */
    private function generate(): Generator
    {
        while (!$this->queue->isEmpty()) {
            yield $this->queue->dequeue();
        }
    }
}
