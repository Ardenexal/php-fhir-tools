<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element CarePlan.activity
 * @description Identifies an action that has occurred or is a planned action to occur as part of the plan. For example, a medication to be used, lab tests to perform, self-monitoring that has occurred, education etc.
 */
class FHIRCarePlanActivity extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference> performedActivity Results of the activity (concept, or Appointment, Encounter, Procedure, etc.) */
		public array $performedActivity = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAnnotation> progress Comments about the activity status/progress */
		public array $progress = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference plannedActivityReference Activity that is intended to be part of the care plan */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $plannedActivityReference = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
