<?php
namespace Shrikeh\Bounce\Collection;

use Shrikeh\Bounce\Listener\ListenerInterface as Listener;

final class Listeners implements \OuterIterator
{
    use \Shrikeh\Collection\NamedConstructorsTrait;   # Give it named constructors
    use \Shrikeh\Collection\ImmutableCollectionTrait; # Give it read-only array access
    use \Shrikeh\Collection\ClosedOuterIteratorTrait; # Close off access to the inner iterator
    use \Shrikeh\Collection\OuterIteratorTrait;       # Give it all the standard read access methods
    use \Shrikeh\Collection\ObjectStorageTrait;       # Set inner storage to SplObjectStorage


    # Append method is called by ObjectStorageTrait during construction, so we
    # type hint the relevant class/interface we need...
    protected function append(Listener $object, $key)
    {
        $this->getStorage()->attach($object);
    }
}