<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;

/**
 * @author HL7 International / Patient Administration
 *
 * @see http://hl7.org/fhir/StructureDefinition/subject-locationClassification
 *
 * @description The classification of the location of the subject in an Encounter.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/subject-locationClassification', fhirVersion: 'R5')]
#[FHIRExtensionContext(type: 'element', expression: 'Encounter.location')]
#[FHIRExtensionContext(type: 'element', expression: 'EncounterHistory.location')]
class EncounterSubjectLocationClassificationExtension extends Extension
{
    /**
     * @param list<Extension> $extension
     */
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
            url: 'http://hl7.org/fhir/StructureDefinition/subject-locationClassification',
            value: $this->valueCodeableConcept,
        );
    }
}
