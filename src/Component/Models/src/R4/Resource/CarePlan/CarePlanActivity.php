<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\CarePlan;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;

/**
 * @description Identifies a planned action to occur as part of the plan.  For example, a medication to be used, lab tests to perform, self-monitoring, education, etc.
 */
#[FHIRBackboneElement(parentResource: 'CarePlan', elementPath: 'CarePlan.activity', fhirVersion: 'R4')]
class CarePlanActivity extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<CodeableConcept> outcomeCodeableConcept Results of the activity */
        public array $outcomeCodeableConcept = [],
        /** @var array<Reference> outcomeReference Appointment, Encounter, Procedure, etc. */
        public array $outcomeReference = [],
        /** @var array<Annotation> progress Comments about the activity status/progress */
        public array $progress = [],
        /** @var Reference|null reference Activity details defined in specific resource */
        public ?Reference $reference = null,
        /** @var CarePlanActivityDetail|null detail In-line definition of activity */
        public ?CarePlanActivityDetail $detail = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
