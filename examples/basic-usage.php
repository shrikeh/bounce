<?php
require_once __DIR__.'/../vendor/autoload.php';

use Shrikeh\Bounce\Emitter;

$emitter = Emitter::create();

$firstListener = function($event) {
    echo sprintf("firstListener: %s\n", $event->name());
};

$secondListener = function($event) {
    echo sprintf("secondListener: %s\n", $event->name());
};

$emitter->addListener('event.*', $firstListener);
$emitter->addListener('*.first', $secondListener);


$emitter->emit('event.first', 'event.second', 'event.third');