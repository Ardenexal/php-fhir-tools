<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRRange;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Population
 *
 * @description A populatioof people with some set of grouping criteria.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Population', elementPath: 'Population', fhirVersion: 'R4')]
class FHIRPopulation extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRRange|FHIRCodeableConcept|null ageX The age of the specific population */
        public FHIRRange|FHIRCodeableConcept|null $ageX = null,
        /** @var FHIRCodeableConcept|null gender The gender of the specific population */
        public ?FHIRCodeableConcept $gender = null,
        /** @var FHIRCodeableConcept|null race Race of the specific population */
        public ?FHIRCodeableConcept $race = null,
        /** @var FHIRCodeableConcept|null physiologicalCondition The existing physiological conditions of the specific population to which this applies */
        public ?FHIRCodeableConcept $physiologicalCondition = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
