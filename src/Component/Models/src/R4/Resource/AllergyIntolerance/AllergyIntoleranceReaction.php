<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\AllergyIntolerance;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\AllergyIntoleranceSeverityType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description Details about each adverse reaction event linked to exposure to the identified substance.
 */
#[FHIRBackboneElement(parentResource: 'AllergyIntolerance', elementPath: 'AllergyIntolerance.reaction', fhirVersion: 'R4')]
class AllergyIntoleranceReaction extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null substance Specific substance or pharmaceutical product considered to be responsible for event */
        public ?CodeableConcept $substance = null,
        /** @var array<CodeableConcept> manifestation Clinical symptoms/signs associated with the Event */
        public array $manifestation = [],
        /** @var StringPrimitive|string|null description Description of the event as a whole */
        public StringPrimitive|string|null $description = null,
        /** @var DateTimePrimitive|null onset Date(/time) when manifestations showed */
        public ?DateTimePrimitive $onset = null,
        /** @var AllergyIntoleranceSeverityType|null severity mild | moderate | severe (of event as a whole) */
        public ?AllergyIntoleranceSeverityType $severity = null,
        /** @var CodeableConcept|null exposureRoute How the subject was exposed to the substance */
        public ?CodeableConcept $exposureRoute = null,
        /** @var array<Annotation> note Text about event not captured in other fields */
        public array $note = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
