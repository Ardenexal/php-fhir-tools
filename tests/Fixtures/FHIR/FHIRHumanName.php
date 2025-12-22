<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Fixtures\FHIR;

use Ardenexal\FHIRTools\Attributes\FHIRComplexType;

/**
 * Test FHIR HumanName complex type for testing serialization
 *
 * @author Kiro AI Assistant
 */
#[FHIRComplexType(
    typeName: 'HumanName',
    fhirVersion: 'R4B',
)]
class FHIRHumanName
{
    public function __construct(
        public ?string $use = null,
        public ?string $text = null,
        public ?string $family = null,
        /** @var array<string>|null */
        public ?array $given = null,
        /** @var array<string>|null */
        public ?array $prefix = null,
        /** @var array<string>|null */
        public ?array $suffix = null,
        /** @var array<mixed>|null */
        public ?array $extension = null
    ) {
    }
}
