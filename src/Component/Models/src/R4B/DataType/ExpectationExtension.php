<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author Health Level Seven, Inc. - [WG Name] WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/capabilitystatement-expectation
 *
 * @description Defines the level of expectation associated with a given system capability.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/capabilitystatement-expectation', fhirVersion: 'R4B')]
class ExpectationExtension extends Extension
{
    public function __construct(
        /** @var CodePrimitive|null valueCode Value of extension */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive $valueCode = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/capabilitystatement-expectation',
            value: $this->valueCode,
        );
    }
}
