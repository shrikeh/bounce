<?php
namespace Shrikeh\Bounce;

use Acclimate\Container\ContainerAcclimator;
use Pimple\Container;
use Psr\Container\ContainerInterface;
use Shrikeh\Bounce\ServiceProvider\Bounce as BounceServiceProvider;

/**
 * Class Bounce
 * @package Shrikeh\Bounce
 */
class Bounce
{
    /**,
     * @return ContainerInterface
     */
    public static function container(): ContainerInterface
    {
        $pimple     = new Container();
        $pimple->register(new BounceServiceProvider());
        $acclimator = new ContainerAcclimator();

        return $acclimator->acclimate($pimple);
    }
}
