<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/questionnaire-unit
 *
 * @description Provides a computable unit of measure associated with numeric questions to support subsequent computation on responses. This is for use on items of type integer and decimal, and its purpose is to support converting the integer or decimal answer into a Quantity when extracting the data into a resource.      If a 'display' value is provided for valueCoding of this extension and the associated question item does not have a child 'display' item with an itemControl extension of 'unit', then form fillers SHOULD take the display value of this extension and use it as as the unit display.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/questionnaire-unit', fhirVersion: 'R5')]
class QUnitExtension extends Extension
{
    public function __construct(
        /** @var Coding|null valueCoding Value of extension */
        #[FhirProperty(fhirType: 'Coding', propertyKind: 'complex')]
        public ?Coding $valueCoding = null,
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
