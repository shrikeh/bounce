<?php
namespace Shrikeh\Bounce\ServiceProvider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Shrikeh\Bounce\Dispatcher\Dispatcher;
use Shrikeh\Bounce\Emitter;
use Shrikeh\Bounce\Event\Queue\EventQueue;
use Shrikeh\Bounce\EventMapFactory;
use Shrikeh\Bounce\Listener\Acceptor;
use Shrikeh\Bounce\Listener\MappedListeners;

/**
 * Class Bounce
 * @package Shrikeh\Bounce\ServiceProvider
 */
final class Bounce implements ServiceProviderInterface
{
    const EMITTER           = 'bounce.emitter';
    const DISPATCHER        = 'bounce.dispatcher';
    const ACCEPTOR          = 'bounce.acceptor';
    const EVENT_MAP_FACTORY = 'bounce.event_map_factory';
    const MAPPED_LISTENERS  = 'bounce.mapped_listeners';
    const EVENT_QUEUE       = 'bounce.event_queue';

    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $pimple A container instance
     */
    public function register(Container $pimple)
    {
        $pimple[self::EVENT_MAP_FACTORY] = function (): EventMapFactory {
            return new EventMapFactory();
        };

        $pimple[self::EVENT_QUEUE] = function (): EventQueue {
            return EventQueue::create();
        };

        $pimple[self::MAPPED_LISTENERS] = function (): MappedListeners {
            return MappedListeners::create();
        };

        $pimple[self::ACCEPTOR] = function (Container $con): Acceptor {
            return Acceptor::create(
                $con[self::EVENT_MAP_FACTORY],
                $con[self::MAPPED_LISTENERS]
            );
        };

        $pimple[self::DISPATCHER] = function (Container $con): Dispatcher {
            return Dispatcher::create($con[self::EVENT_QUEUE]);
        };

        $pimple[self::EMITTER] = function (Container $con): Emitter {
            return Emitter::create(
                $con[self::ACCEPTOR],
                $con[self::DISPATCHER]
            );
        };
    }
}
