<?php
use Acclimate\Container\ContainerAcclimator;
use EventIO\InterOp\EventInterface as Event;
use Pimple\Container;
use Shrikeh\Bounce\Examples\Mock\Listener\Foo;
use Shrikeh\Bounce\Listener\Acceptor;
use Shrikeh\Bounce\Listener\Psr11Container;
use Shrikeh\Bounce\ServiceProvider\Bounce;

require_once __DIR__.'/../vendor/autoload.php';

define('FOO_LISTENER', 'FooListener');
define('BAR_LISTENER', 'BarListener');
define('BAZ_LISTENER', 'BazListener');

$pimple     = new Container();
$pimple->register(new Bounce());

$acclimator = new ContainerAcclimator();
$container  = $acclimator->acclimate($pimple);

$emitter = $container->get(Bounce::EMITTER);

$pimple[FOO_LISTENER] = function() {
    return new Foo('this ran');
};

$pimple[BAR_LISTENER] = function(Container $con) {
    $emitter = $con[Bounce::EMITTER];

    return function(Event $event) use ($emitter) {
        echo $event->name() . "\n";
        $emitter->emit('flibble');
    };
};

$pimple[BAZ_LISTENER] = function() {
    return function(Event $event) {
        echo $event->name() . "\n";
    };
};

$fooListener = new Psr11Container($container, FOO_LISTENER);
$barListener = new Psr11Container($container, BAR_LISTENER);
$bazListener = new Psr11Container($container, BAZ_LISTENER);


$emitter->addListener('foo.*', $fooListener, Acceptor::PRIORITY_LOW);
$emitter->addListener('*.bar', $barListener, Acceptor::PRIORITY_HIGH);
$emitter->addListener('flibble', $bazListener, Acceptor::PRIORITY_NORMAL);

$emitter->emit('foo.bar', 'foo.baz', 'baz.bar', 'foo.bar');

$emitter->emit('foo.baz');
