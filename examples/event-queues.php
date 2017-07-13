<?php
use Shrikeh\Bounce\Emitter;
use EventIO\InterOp\EventInterface;

require_once __DIR__.'/../vendor/autoload.php';

$listener = function(EventInterface $event) {
    echo sprintf("event: %s\n", $event->name());
};

$emitter = Emitter::create();

$emitter->addListener('foo.*', $listener);

$emitter->emit('foo.bar', 'foo.baz', 'foo.foo');