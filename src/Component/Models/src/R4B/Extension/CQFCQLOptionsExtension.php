<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference;

/**
 * @author HL7 International / Clinical Decision Support
 *
 * @see http://hl7.org/fhir/StructureDefinition/cqf-cqlOptions
 *
 * @description Specifies a parameters (contained) resource that identifies the options for the CQL-to-ELM translator associated with the CQL content.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/cqf-cqlOptions', fhirVersion: 'R4B')]
#[FHIRExtensionContext(type: 'element', expression: 'Library')]
class CQFCQLOptionsExtension extends Extension
{
    /**
     * @param list<Extension> $extension
     */
    public function __construct(
        /** @var Reference|null valueReference Value of extension */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $valueReference = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/cqf-cqlOptions',
            value: $this->valueReference,
        );
    }
}
