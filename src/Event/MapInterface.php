<?php
namespace Shrikeh\Bounce\Event;

use Shrikeh\Bounce\EventInterface;

interface MapInterface
{
    /**
     * {@inheritdoc}
     */
    public function isMatch(EventInterface $event): bool;

    public function index(): string;
}