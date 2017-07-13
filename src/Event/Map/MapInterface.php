<?php
namespace Shrikeh\Bounce\Event\Map;

use EventIO\InterOp\EventInterface;

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