<?php
namespace Shrikeh\Bounce;

use Shrikeh\Bounce\Event\Map\Glob;
use Shrikeh\Bounce\Event\MapInterface as Map;

class EventMapFactory
{
    private $maps;

    public function __construct()
    {
        $this->maps = new \ArrayObject();
    }

    public function glob(string $string): Glob
    {
        return new Glob($string);
    }


    public function map($map)
    {
        if ($map instanceof Map) {

        }

        if (!$this->maps->offsetExists($map)) {
            $newMap = new Glob($map);

        }


        return $this->maps->offsetGet($map);
    }

    private function getMap($map)
    {

        if (!$this->maps->offsetExists($map)) {
            $this->maps->offsetSet($map, new Glob($map));
        }

        return $this->maps->offsetGet($map);
    }

    /**
     * @param Map $map
     * @param null $identifier
     */
    private function addMap(Map $map, $identifier = null)
    {
        if (null === $identifier) {
            $identifier = $map;
        }
        $this->maps->offsetSet($identifier, $map);
    }
}