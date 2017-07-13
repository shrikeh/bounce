<?php
namespace Shrikeh\Bounce\Listener;

use EventIO\InterOp\EventInterface as Event;
use EventIO\InterOp\ListenerInterface as Listener;
use Psr\Container\ContainerInterface as Container;

/**
 * Class PsrContainer
 * @package Shrikeh\Bounce\Listener
 */
class PsrContainer implements Listener
{
    /**
     * @var string
     */
    private $entryId;

    /**
     * @var Container
     */
    private $container;

    public function __construct(Container $container, $entryId)
    {
        $this->container    = $container;
        $this->entryId      = $entryId;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Event $event)
    {
        $this->listener()->handle($event);
    }

    /**
     * @return Listener
     */
    private function listener(): Listener
    {
        if (!$this->container->has($this->entryId)) {

        }
        $listener = $this->container->get($this->entryId);

        if (!$listener instanceof Listener) {
            $listener = new CallableListener($listener);
        }

        return $listener;
    }
}