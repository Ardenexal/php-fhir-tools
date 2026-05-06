<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://fhir-registry.smarthealthit.org/StructureDefinition/capabilities
 *
 * @description A set of codes that defines what the server is capable of.
 */
#[FHIRExtensionDefinition(url: 'http://fhir-registry.smarthealthit.org/StructureDefinition/capabilities', fhirVersion: 'R4')]
class CapabilitiesExtension extends Extension
{
    public function __construct(
        /** @var CodePrimitive|null valueCode Value of extension */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?CodePrimitive $valueCode = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://fhir-registry.smarthealthit.org/StructureDefinition/capabilities',
            value: $this->valueCode,
        );
    }
}
