<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle;

/**
 * How our error count relates to the Java reference validator's for one case.
 *
 * ABOVE is the only class that blocks landing the cascade: reporting violations Java does not
 * report means telling a caller their valid resource is invalid. BELOW records a check we have
 * never implemented and is handled separately (M03).
 */
enum Classification: string
{
    case Above = 'ABOVE';
    case Equal = 'EQUAL';
    case Below = 'BELOW';

    public static function compare(int $ourErrorCount, int $javaErrorCount): self
    {
        return match (true) {
            $ourErrorCount > $javaErrorCount => self::Above,
            $ourErrorCount < $javaErrorCount => self::Below,
            default                          => self::Equal,
        };
    }
}
