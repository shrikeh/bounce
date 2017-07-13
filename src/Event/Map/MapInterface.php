<?php
namespace Shrikeh\Bounce\Event\Map;

use EventIO\InterOp\EventInterface as Event;

interface MapInterface
{
    /**
     * {@inheritdoc}
     */
    public function isMatch(Event $event): bool;

    /**
     * @return string
     */
    public function index(): string;
}