<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/valueset-parameterSource
 *
 * @description Declares what the source of this parameter is.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/valueset-parameterSource', fhirVersion: 'R4B')]
class ParameterSourceExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/valueset-parameterSource',
            value: $this->valueCode,
        );
    }
}
