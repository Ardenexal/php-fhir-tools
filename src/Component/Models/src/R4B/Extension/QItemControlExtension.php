<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/questionnaire-itemControl
 *
 * @description The type of data entry control or structure that should be used to render the item.  Certain item controls only make sense for certain types of items.  For example, a radio button doesn't make sense for a question where repeats=true; few systems will know how to implement a 'slider' control if the question type is 'Attachment', etc.  Form fillers are allowed to ignore item controls that don't make sense (or that they don't know how to handle) for the type of item the extension appears on.  If the extension is ignored, the form filler will use whatever display control it supports that works best for the type of item present.  Similarly, form authoring tools may raise validation issues or prohibit the selection of certain control types based on the characteristics of the item.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/questionnaire-itemControl', fhirVersion: 'R4B')]
class QItemControlExtension extends Extension
{
    public function __construct(
        /** @var CodeableConcept|null valueCodeableConcept Value of extension */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $valueCodeableConcept = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/questionnaire-itemControl',
            value: $this->valueCodeableConcept,
        );
    }
}
