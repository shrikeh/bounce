<?php
use EventIO\InterOp\EventInterface;
use Shrikeh\Bounce\Emitter;
use Shrikeh\Bounce\Event\Map\EventType;
use Shrikeh\Bounce\Event\Map\Glob;
use Shrikeh\Bounce\Examples\Mock\Event\Bar;

require_once __DIR__.'/../vendor/autoload.php';

$emitter = Emitter::create();

$firstListener = function(EventInterface $event) {
    echo sprintf("firstListener: %s\n", $event->name());
};

$secondListener = function(Bar $event) {
    echo sprintf("secondListener: %s\n", $event->name());
};

$firstMap   = new Glob('foo.*');
$secondMap  = new EventType(Bar::class);

$emitter->addListener($firstMap, $firstListener);
$emitter->addListener($secondMap, $secondListener);

$event = new Bar('foo.bar');

$emitter->emitEvent($event);