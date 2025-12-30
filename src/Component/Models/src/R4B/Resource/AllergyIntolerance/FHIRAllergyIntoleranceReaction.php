<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAllergyIntoleranceSeverityType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;

/**
 * @description Details about each adverse reaction event linked to exposure to the identified substance.
 */
#[FHIRBackboneElement(parentResource: 'AllergyIntolerance', elementPath: 'AllergyIntolerance.reaction', fhirVersion: 'R4B')]
class FHIRAllergyIntoleranceReaction extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null substance Specific substance or pharmaceutical product considered to be responsible for event */
        public ?FHIRCodeableConcept $substance = null,
        /** @var array<FHIRCodeableConcept> manifestation Clinical symptoms/signs associated with the Event */
        public array $manifestation = [],
        /** @var FHIRString|string|null description Description of the event as a whole */
        public FHIRString|string|null $description = null,
        /** @var FHIRDateTime|null onset Date(/time) when manifestations showed */
        public ?FHIRDateTime $onset = null,
        /** @var FHIRAllergyIntoleranceSeverityType|null severity mild | moderate | severe (of event as a whole) */
        public ?FHIRAllergyIntoleranceSeverityType $severity = null,
        /** @var FHIRCodeableConcept|null exposureRoute How the subject was exposed to the substance */
        public ?FHIRCodeableConcept $exposureRoute = null,
        /** @var array<FHIRAnnotation> note Text about event not captured in other fields */
        public array $note = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
