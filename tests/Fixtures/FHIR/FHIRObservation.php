<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Fixtures\FHIR;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;

/**
 * Test FHIR Observation resource for testing serialization
 *
 * @author Ardenexal
 */
#[FhirResource(
    type: 'Observation',
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/Observation',
    fhirVersion: 'R4B',
)]
class FHIRObservation
{
    public function __construct(
        public ?string $resourceType = 'Observation',
        public ?string $id = null,
        public ?string $status = null,
        public ?array $code = null,
        public ?array $subject = null,
        public ?string $valueString = null,
        public ?int $valueInteger = null,
        public ?array $extension = null,
        public ?array $modifierExtension = null
    ) {
    }
}
