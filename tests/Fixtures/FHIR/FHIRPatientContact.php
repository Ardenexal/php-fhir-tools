<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Fixtures\FHIR;

use Ardenexal\FHIRTools\Attributes\FHIRBackboneElement;

/**
 * Test FHIR Patient.contact backbone element for testing serialization
 *
 * @author Kiro AI Assistant
 */
#[FHIRBackboneElement(
    parentResource: 'Patient',
    elementPath: 'Patient.contact',
    fhirVersion: 'R4B',
)]
class FHIRPatientContact
{
    public function __construct(
        /** @var array<mixed>|null */
        public ?array $relationship = null,
        public ?FHIRHumanName $name = null,
        /** @var array<mixed>|null */
        public ?array $telecom = null,
        public ?FHIRAddress $address = null,
        public ?string $gender = null,
        public ?FHIRReference $organization = null,
        /** @var array<mixed>|null */
        public ?array $extension = null,
        /** @var array<mixed>|null */
        public ?array $modifierExtension = null
    ) {
    }
}
