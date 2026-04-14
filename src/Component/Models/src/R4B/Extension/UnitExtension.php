<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/questionnaire-unit
 *
 * @description Provides a computable unit of measure associated with numeric questions to support subsequent computation on responses. This is for use on items of type integer and decimal, and it's purpose is to support converting the integer or decimal answer into a Quantity when extracting the data into a resource.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/questionnaire-unit', fhirVersion: 'R4B')]
class UnitExtension extends Extension
{
    public function __construct(
        /** @var Coding|null valueCoding Value of extension */
        #[FhirProperty(fhirType: 'Coding', propertyKind: 'complex')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Coding $valueCoding = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/questionnaire-unit',
            value: $this->valueCoding,
        );
    }
}
