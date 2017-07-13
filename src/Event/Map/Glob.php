<?php
namespace Shrikeh\Bounce\Event\Map;

use EventIO\InterOp\EventInterface;

/**
 * Class Glob
 * @package Shrikeh\Bounce\Event\Map
 */
final class Glob implements MapInterface
{
    /**
     * @var string
     */
    private $pattern;

    /**
     * Glob constructor.
     * @param string $globPattern A glob pattern
     */
    public function __construct(string $globPattern)
    {
        $this->pattern = $globPattern;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->pattern;
    }

    /**
     * @param EventInterface $event The event to test
     * @return bool
     */
    public function isMatch(EventInterface $event): bool
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
