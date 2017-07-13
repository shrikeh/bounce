<?php
namespace Shrikeh\Bounce;

use Shrikeh\Bounce\Event\Map\Glob;
use Shrikeh\Bounce\Event\Map\MapInterface as Map;

/**
 * Class EventMapFactory
 * @package Shrikeh\Bounce
 */
class EventMapFactory
{
    const MAP_GLOB          = 'Glob';
    const MAP_EVENT_TYPE    = 'EventType';

    private $maps;

    public function __construct()
    {
        $this->maps = new \ArrayObject();
    }

    /**
     * @param string $string
     * @return Glob
     */
    public function glob(string $string): Glob
    {
        return new Glob($string);
    }

    /**
     * @param $map
     * @return Glob
     */
    public function map($map, $type = self::MAP_GLOB)
    {
        if (!$map instanceof Map) {
            switch ($type) {
                case self::MAP_GLOB:
                    $map = $this->glob($map);
                    break;
            }

        }

        return $map;
    }
}