<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Fixtures\FHIR;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;

/**
 * Test FHIR Patient resource for testing serialization
 *
 * @author Ardenexal
 */
#[FhirResource(
    type: 'Patient',
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/Patient',
    fhirVersion: 'R4B',
)]
class FHIRPatient
{
    public function __construct(
        public ?string $resourceType = 'Patient',
        public ?string $id = null,
        public ?array $identifier = null,
        public ?array $name = null,
        public ?string $gender = null,
        public ?string $birthDate = null,
        public ?array $extension = null,
        public ?array $modifierExtension = null
    ) {
    }
}
