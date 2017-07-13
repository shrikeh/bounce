<?php
use Acclimate\Container\ContainerAcclimator;
use EventIO\InterOp\EventInterface as Event;
use Pimple\Container;
use Shrikeh\Bounce\Examples\Mock\Listener\Foo;
use Shrikeh\Bounce\Listener\Acceptor;
use Shrikeh\Bounce\Listener\PsrContainer;
use Shrikeh\Bounce\ServiceProvider\Bounce;

require_once __DIR__.'/../vendor/autoload.php';

define('FOO_LISTENER', 'FooListener');
define('BAR_LISTENER', 'BarListener');

$pimple     = new Container();
$pimple->register(new Bounce());


$acclimator = new ContainerAcclimator();
$container  = $acclimator->acclimate($pimple);


$pimple[FOO_LISTENER] = function() {
    return new Foo('this ran');
};

$pimple[BAR_LISTENER] = function() {
    return function(Event $event) {
        echo $event->name();
    };
};

$fooListener = new PsrContainer($container, FOO_LISTENER);
$barListener = new PsrContainer($container, BAR_LISTENER);

$emitter = $pimple[Bounce::EMITTER];

$emitter->addListener('foo.*', $fooListener, Acceptor::PRIORITY_LOW);
$emitter->addListener('foo.bar', $barListener, Acceptor::PRIORITY_HIGH);

$emitter->emit('foo.bar');
