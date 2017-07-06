<?php
namespace Shrikeh\Bounce\Event\Map;

use \Shrikeh\Bounce\EventInterface as Event;
use \Shrikeh\Bounce\Event\MapInterface;

final class Glob implements MapInterface
{
    /**
     * @var string
     */
    private $pattern;

    /**
     * Glob constructor.
     * @param string $globPattern
     */
    public function __construct(string $globPattern)
    {
        $this->pattern = $globPattern;
    }

    /**
     *
     */
    public function __toString(): string
    {
        return $this->pattern;
    }

    /**
     * @param Event $event
     * @return bool
     */
    public function isMatch(Event $event): bool
    {
        // see https://veewee.github.io/blog/optimizing-php-performance-by-fq-function-calls/
        return \fnmatch($this->pattern, $event->name());
    }

    /**
     * {@inheritdoc}
     */
    public function index(): string
    {
        return (string) (self::class . '|' . $this);
    }
}
