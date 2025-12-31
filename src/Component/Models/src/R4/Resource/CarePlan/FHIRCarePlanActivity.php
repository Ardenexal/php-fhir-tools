<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @description Identifies a planned action to occur as part of the plan.  For example, a medication to be used, lab tests to perform, self-monitoring, education, etc.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'CarePlan', elementPath: 'CarePlan.activity', fhirVersion: 'R4')]
class FHIRCarePlanActivity extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept> outcomeCodeableConcept Results of the activity */
		public array $outcomeCodeableConcept = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference> outcomeReference Appointment, Encounter, Procedure, etc. */
		public array $outcomeReference = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAnnotation> progress Comments about the activity status/progress */
		public array $progress = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference reference Activity details defined in specific resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $reference = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCarePlanActivityDetail detail In-line definition of activity */
		public ?FHIRCarePlanActivityDetail $detail = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
