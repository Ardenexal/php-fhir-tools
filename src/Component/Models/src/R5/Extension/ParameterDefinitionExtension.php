<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\ParameterDefinition;

/**
 * @author HL7 International / Clinical Decision Support
 *
 * @see http://hl7.org/fhir/StructureDefinition/cqf-parameterDefinition
 *
 * @description The definition of a parameter that is expected to be provided as part of the invocation of the trigger.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/cqf-parameterDefinition', fhirVersion: 'R5')]
class ParameterDefinitionExtension extends Extension
{
    public function __construct(
        /** @var ParameterDefinition|null valueParameterDefinition Value of extension */
        #[FhirProperty(fhirType: 'ParameterDefinition', propertyKind: 'complex')]
        public ?ParameterDefinition $valueParameterDefinition = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/cqf-parameterDefinition',
            value: $this->valueParameterDefinition,
        );
    }
}
