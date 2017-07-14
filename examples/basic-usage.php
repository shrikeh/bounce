<?php
require_once __DIR__.'/../vendor/autoload.php';

use Shrikeh\Bounce\Emitter;
use Shrikeh\Bounce\Listener\Acceptor;

$emitter = Emitter::create();

$firstListener = function($event) {
    echo sprintf("firstListener: %s\n", $event->name());
};

$secondListener = function($event) {
    echo sprintf("secondListener: %s\n", $event->name());
};

$emitter->addListener('event.*', $firstListener, Acceptor::PRIORITY_NORMAL);
$emitter->addListener('*.first', $secondListener, Acceptor::PRIORITY_HIGH);


$emitter->emit('event.first', 'event.second', 'event.third');