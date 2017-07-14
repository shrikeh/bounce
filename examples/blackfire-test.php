<?php
use Acclimate\Container\ContainerAcclimator;
use EventIO\InterOp\EventInterface;
use Shrikeh\Bounce\ServiceProvider\Bounce;

require_once __DIR__.'/../vendor/autoload.php';


$pimple = new Pimple\Container();

$pimple->register(new Bounce());
$acclimator = new ContainerAcclimator();
$container  = $acclimator->acclimate($pimple);

$emitter = $container->get(Bounce::EMITTER);

for ($i=0; $i<1000; $i++) {
    $listener = function(EventInterface $event) use ($i) {
        echo sprintf(
            "Iteration: %s %d\n",
            $event->name(),
            $i
        );
    };
    $emitter->addListener('foo.*', $listener);
}

$emitter->emit('foo.bar', 'foo.baz', 'foo.bib', 'foo.bub');