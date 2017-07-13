<?php
namespace Shrikeh\Bounce\Listener;

use EventIO\InterOp\EventInterface;
use EventIO\InterOp\ListenerInterface;
use Psr\Container\ContainerInterface;

/**
 * Class PsrContainer
 * @package Shrikeh\Bounce\Listener
 */
class PsrContainer implements ListenerInterface
{
    /**
     * @var string
     */
    private $entryId;

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container, $entryId)
    {
        $this->container    = $container;
        $this->entryId      = $entryId;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(EventInterface $event)
    {
        $this->listener()->handle($event);
    }

    /**
     * @return ListenerInterface
     */
    private function listener(): ListenerInterface
    {
        if (!$this->container->has($this->entryId)) {

        }
        $listener = $this->container->get($this->entryId);

        if (!$listener instanceof ListenerInterface) {
            $listener = new CallableListener($listener);
        }

        return $listener;
    }
}