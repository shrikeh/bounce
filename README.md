# Bounce
An event bus that allows for multiple events to be fired, and for listeners to listen on multiple events.

## Overview

There is an issue with many event buses, in that they allow only one event to occur within a cycle, and that events are not queued.

This means that an event generated during a cycle will occur immediately, even if that is not appropriate.

Bounce takes a different approach, in which listeners can fuzzy match on events by either name or type, and that further events can be triggered and queued without interrupting the current event cycle. Under the hood,it uses [SPLPriorityQueue], [SPLQueue] and [SPLObjectStorage] to efficiently store and maintain listeners and events during a cycle.

## Features

Bounce has several key changes over standard implementations:

### Event matching
Bounce has the concept of Event _maps_, whereby a map states whether it matches a given event. Two implementations are currently included: Glob-based, and on Event type. This allows for complex matching on events:

```php
<?php
# examples/maps.php

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
```

The above event will output:
```bash
firstListener: foo.bar
secondListener: foo.bar
```

### Event Queueing
Bounce allow for multiple events to be emitted. Events are queued in FIFO order and dispatched in order.
```php
<?php
# examples/event-queues.php

use Shrikeh\Bounce\Emitter;
use EventIO\InterOp\EventInterface;

require_once __DIR__.'/../vendor/autoload.php';
 
$listener = function(EventInterface $event) {
    echo sprintf("event: %s\n", $event->name());
};

$emitter = Emitter::create();
 
$emitter->addListener('foo.*', $listener);
 
$emitter->emit('foo.bar', 'foo.baz', 'foo.foo');

```
The above will output:
```bash
event: foo.bar
event: foo.baz
event: foo.foo
```

### PSR-11 Container-based listeners

Some listeners may be heavy to create (requiring database access, file handling, etc) - none of which are necessary if the listener is never actually called. Instead, Bounce uses a `Psr11Container` that allows the listener to be created lazily, and only when the listener is triggered. Here is an example using the awesome [Pimple] DI container with the [Acclimate] adapter:


```php
<?php
use Acclimate\Container\ContainerAcclimator;
use Pimple\Container;
use Shrikeh\Bounce\Emitter;
use Shrikeh\Bounce\Listener\Psr11Container;

require_once __DIR__.'/../vendor/autoload.php';

$pimple     = new Container();

$pimple['some_heavy_listener'] = function() {
    return function() {
        echo 'has this run?';
    };
};

$acclimator = new ContainerAcclimator();
$container  = $acclimator->acclimate($pimple);

$lazyListener = new Psr11Container($container, 'some_heavy_listener');

$emitter = Emitter::create();
 
$emitter->addListener('foo.*', $lazyListener);

$emitter->emit('foo.bar');

```
The above will output `has this run?`.

## Requirements
Bounce requires PHP 7.1 or above.

## Installation

Bounce is installed using [composer]:

```bash
composer require shrikeh/bounce
```

## Basic Usage

```php
<?php
# examples/basic-usage.php

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

```

The above example will output:

```bash
firstListener: event.first
secondListener: event.first
firstListener: event.second
firstListener: event.third
```
[Acclimate]: https://github.com/AcclimateContainer/acclimate-container
[composer]: https://getcomposer.org
[Pimple]: https://pimple.symfony.com/
[SPLPriorityQueue]: https://secure.php.net/manual/en/class.splpriorityqueue.php
[SPLQueue]: https://secure.php.net/manual/en/class.splqueue.php
[SPLObjectStorage]: https://secure.php.net/manual/en/class.splobjectstorage.php