<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Fixtures\Models\R4B\DataType;

/**
 * Mock FHIR Element base class for testing.
 */
abstract class FHIRElement
{
    public function __construct(
        public ?string $id = null,
        public array $extension = [],
    ) {
    }
}
