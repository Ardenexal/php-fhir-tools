<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\HttpClient\Tests\Unit\OAuth;

use Psr\Clock\ClockInterface;

/**
 * A PSR-20 clock for tests: `now()` returns the Unix time held in the public `$now`, which a test moves
 * forward to simulate a long-running process.
 */
final class MutableClock implements ClockInterface
{
    public function __construct(public int $now)
    {
    }

    public function now(): \DateTimeImmutable
    {
        return (new \DateTimeImmutable())->setTimestamp($this->now);
    }
}
