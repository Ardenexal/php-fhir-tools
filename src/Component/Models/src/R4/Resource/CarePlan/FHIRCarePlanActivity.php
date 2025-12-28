<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Identifies a planned action to occur as part of the plan.  For example, a medication to be used, lab tests to perform, self-monitoring, education, etc.
 */
#[FHIRBackboneElement(parentResource: 'CarePlan', elementPath: 'CarePlan.activity', fhirVersion: 'R4')]
class FHIRCarePlanActivity extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRCodeableConcept> outcomeCodeableConcept Results of the activity */
        public array $outcomeCodeableConcept = [],
        /** @var array<FHIRReference> outcomeReference Appointment, Encounter, Procedure, etc. */
        public array $outcomeReference = [],
        /** @var array<FHIRAnnotation> progress Comments about the activity status/progress */
        public array $progress = [],
        /** @var FHIRReference|null reference Activity details defined in specific resource */
        public ?\FHIRReference $reference = null,
        /** @var FHIRCarePlanActivityDetail|null detail In-line definition of activity */
        public ?\FHIRCarePlanActivityDetail $detail = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
