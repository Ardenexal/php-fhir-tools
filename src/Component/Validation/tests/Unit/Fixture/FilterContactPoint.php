<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture;

/**
 * A minimal contact point for {@see FilteredObligationFixture}: just enough for a filter on `use`.
 */
final class FilterContactPoint
{
    public function __construct(
        public readonly ?string $use = null,
        public readonly ?string $value = null,
    ) {
    }
}
