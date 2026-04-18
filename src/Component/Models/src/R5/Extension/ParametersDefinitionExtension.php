<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\ParameterDefinition;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/parameters-definition
 *
 * @description This specifies the definition for a parameter if needed. This is useful for communicating the type of a parameter when the parameter has no value.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/parameters-definition', fhirVersion: 'R5')]
class ParametersDefinitionExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/parameters-definition',
            value: $this->valueParameterDefinition,
        );
    }
}
