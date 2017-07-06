<?php
namespace Shrikeh\Bounce;

use Shrikeh\Bounce\EventInterface as Event;
use Generator;
use Iterator;
use SplQueue;

final class EventQueue implements EventQueueInterface
{
    /**
     * @var SplQueue
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
     * @param SplQueue $queue
     */
    private function __construct(SplQueue $queue)
    {
        $this->queue = $queue;
    }


    /**
     * Add an event to the event queue
     * @param Event $event The event to add
     * @return Generator
     */
    public function queue(Event ...$events)
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
