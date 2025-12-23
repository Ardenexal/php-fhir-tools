<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element FamilyMemberHistory.condition
 * @description The significant Conditions (or condition) that the family member had. This is a repeating section to allow a system to represent more than one condition per resource, though there is nothing stopping multiple resources - one per condition.
 */
class FHIRFamilyMemberHistoryCondition extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept code Condition suffered by relation */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept outcome deceased | permanent disability | etc */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $outcome = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean contributedToDeath Whether the condition contributed to the cause of death */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean $contributedToDeath = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAge|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string onsetX When condition first manifested */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAge|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $onsetX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAnnotation> note Extra information about condition */
		public array $note = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
