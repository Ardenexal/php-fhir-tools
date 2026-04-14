<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author Health Level Seven, Inc. - FHIR WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/workflow-reasonReference
 *
 * @description Indicates another resource whose existence justifies this event.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/workflow-reasonReference', fhirVersion: 'R4B')]
class ReasonReferenceExtension extends Extension
{
    public function __construct(
        /** @var Reference|null valueReference Value of extension */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference $valueReference = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/workflow-reasonReference',
            value: $this->valueReference,
        );
    }
}
