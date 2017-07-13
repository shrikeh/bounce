<?php
namespace Shrikeh\Bounce;

use Shrikeh\Bounce\Event\Map\Glob;
use Shrikeh\Bounce\Event\Map\MapInterface;
use SplObjectStorage;

/**
 * Class EventMapFactory
 * @package Shrikeh\Bounce
 */
class EventMapFactory
{
    const MAP_GLOB          = 'Glob';
    const MAP_EVENT_TYPE    = 'EventType';

    /**
     * @var SplObjectStorage
     */
    private $maps;

    /**
     * EventMapFactory constructor.
     */
    public function __construct()
    {
        $this->maps = new SplObjectStorage();
    }

    /**
     * @param string $string A string to turn into a Glob
     * @return Glob
     */
    public function glob(string $string): Glob
    {
        return new Glob($string);
    }

    /**
     * @param mixed  $map  A map to create/store
     * @param string $type A type of map to return
     * @return MapInterface
     */
    public function map($map, $type = self::MAP_GLOB): MapInterface
    {
        if (!$map instanceof MapInterface) {
            switch ($type) {
                case self::MAP_GLOB:
                    $map = $this->glob($map);
                    break;
            }
        }

        if (!$this->maps->contains($map)) {
            $this->maps->attach($map);
        }

        return $map;
    }
}
