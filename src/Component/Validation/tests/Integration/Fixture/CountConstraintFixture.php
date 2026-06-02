<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Fixture;

use Symfony\Component\Validator\Constraints\Count;

/**
 * Fixture with a direct #[Count(min:1)] attribute on a property.
 * Used to verify FHIRValidationService maps a Symfony built-in violation to the error bucket.
 */
final class CountConstraintFixture
{
    /**
     * @param list<mixed> $identifier
     */
    public function __construct(
        #[Count(min: 1)]
        public readonly array $identifier = [],
    ) {
    }
}
