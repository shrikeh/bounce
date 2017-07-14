<?php
namespace Shrikeh\Bounce;

use ArrayObject;
use Shrikeh\Bounce\Event\Map\Glob;
use Shrikeh\Bounce\Event\Map\MapInterface;

/**
 * Class EventMapFactory
 * @package Shrikeh\Bounce
 */
class EventMapFactory
{
    const MAP_GLOB          = 'Glob';
    const MAP_EVENT_TYPE    = 'EventType';

    /**
     * @var ArrayObject
     */
    private $maps;

    /**
     * EventMapFactory constructor.
     */
    public function __construct()
    {
        $this->maps = new ArrayObject();
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
            $map = $this->mapByIndex($map, $type);
        }

        return $map;
    }

    /**
     * @param mixed  $map  A map to create/store
     * @param string $type A type of map to return
     * @return MapInterface
     */
    private function mapByIndex($map, $type): MapInterface
    {
        $index = \sprintf('%s:%s', $map, $type);
        if (!$this->maps->offsetExists($index)) {
            switch ($type) {
                case self::MAP_GLOB:
                    $map = $this->glob($map);
                    break;
            }
            $this->maps->offsetSet($index, $map);
        }

        return $this->maps->offsetGet($index);
    }
}
