# Bounce
An event bus that allows for multiple events to be fired, and for listeners to listen on multiple events.

## Overview

There is an issue with many event buses, in that they allow only one event to occur within a cycle, and that events are not queued.

This means that an event generated during a cycle will occur immediately, even if that is not appropriate.

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