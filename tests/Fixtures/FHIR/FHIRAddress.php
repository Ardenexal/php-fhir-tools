<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Fixtures\FHIR;

use Ardenexal\FHIRTools\Attributes\FHIRComplexType;

/**
 * Test FHIR Address complex type for testing serialization
 *
 * @author Kiro AI Assistant
 */
#[FHIRComplexType(
    typeName: 'Address',
    fhirVersion: 'R4B',
)]
class FHIRAddress
{
    public function __construct(
        public ?string $use = null,
        public ?string $type = null,
        public ?string $text = null,
        /** @var array<string>|null */
        public ?array $line = null,
        public ?string $city = null,
        public ?string $district = null,
        public ?string $state = null,
        public ?string $postalCode = null,
        public ?string $country = null,
        /** @var array<mixed>|null */
        public ?array $extension = null
    ) {
    }
}
