<?php
namespace Shrikeh\Bounce\Event\Map;

use EventIO\InterOp\EventInterface;

/**
 * Interface MapInterface
 * @package Shrikeh\Bounce\Event\Map
 */
interface MapInterface
{
    /**
     * {@inheritdoc}
     */
    public function isMatch(EventInterface $event): bool;

    /**
     * @return string
     */
    public function index(): string;
}
