<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Traits\FHIRExtensionsTrait;

/**
 * Bearing element for fhirpath context tests: carries a public `type` property so a
 * fhirpath context expression such as `type.exists() and type = 'composed-of'` (the
 * real ArtifactIsOwnedExtension shape) can be evaluated against it in-process.
 */
#[FhirResource(type: 'Basic', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Basic', fhirVersion: 'R4')]
final class TypeBearingExtensionResourceFixture
{
    use FHIRExtensionsTrait;

    /** @param list<FHIRExtensionInterface> $extension */
    public function __construct(
        private readonly array $extension = [],
        public ?string $type = null,
    ) {
    }
}
