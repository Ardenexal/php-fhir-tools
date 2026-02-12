<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Population
 *
 * @description A populatioof people with some set of grouping criteria.
 */
#[FHIRBackboneElement(parentResource: 'Population', elementPath: 'Population', fhirVersion: 'R4')]
class Population extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var Range|CodeableConcept|null ageX The age of the specific population */
        public Range|CodeableConcept|null $ageX = null,
        /** @var CodeableConcept|null gender The gender of the specific population */
        public ?CodeableConcept $gender = null,
        /** @var CodeableConcept|null race Race of the specific population */
        public ?CodeableConcept $race = null,
        /** @var CodeableConcept|null physiologicalCondition The existing physiological conditions of the specific population to which this applies */
        public ?CodeableConcept $physiologicalCondition = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
