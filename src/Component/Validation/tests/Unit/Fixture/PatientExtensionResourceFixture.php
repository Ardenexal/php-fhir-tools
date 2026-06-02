<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Traits\FHIRExtensionsTrait;

#[FhirResource(type: 'Patient', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Patient', fhirVersion: 'R4')]
final class PatientExtensionResourceFixture
{
    use FHIRExtensionsTrait;

    /** @param list<FHIRExtensionInterface> $extension */
    public function __construct(
        private readonly array $extension = [],
    ) {
    }
}
