<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Traits\FHIRExtensionsTrait;

/**
 * Minimal resource fixture with a $name property whose FhirProperty declares fhirType: 'HumanName'.
 * Used to test bare-type extension context resolution without depending on generated models.
 */
#[FhirResource(type: 'Patient', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Patient', fhirVersion: 'R4')]
final class ResourceWithTypedNamePropertyFixture
{
    use FHIRExtensionsTrait;

    /**
     * @param list<FHIRExtensionInterface>             $extension
     * @param list<NestedContactWithExtensionsFixture> $name
     */
    public function __construct(
        #[FhirProperty(fhirType: 'HumanName', propertyKind: 'complex', isArray: true)]
        public readonly array $name = [],
        private readonly array $extension = [],
    ) {
    }
}
