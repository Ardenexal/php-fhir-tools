<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\CarePlan;

/**
 * @description Identifies a planned action to occur as part of the plan.  For example, a medication to be used, lab tests to perform, self-monitoring, education, etc.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'CarePlan', elementPath: 'CarePlan.activity', fhirVersion: 'R4')]
class CarePlanActivity extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> outcomeCodeableConcept Results of the activity */
		public array $outcomeCodeableConcept = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> outcomeReference Appointment, Encounter, Procedure, etc. */
		public array $outcomeReference = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation> progress Comments about the activity status/progress */
		public array $progress = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference reference Activity details defined in specific resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $reference = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\CarePlan\CarePlanActivityDetail detail In-line definition of activity */
		public ?CarePlanActivityDetail $detail = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
