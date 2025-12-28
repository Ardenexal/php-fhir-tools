<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @description The significant Conditions (or condition) that the family member had. This is a repeating section to allow a system to represent more than one condition per resource, though there is nothing stopping multiple resources - one per condition.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'FamilyMemberHistory', elementPath: 'FamilyMemberHistory.condition', fhirVersion: 'R4B')]
class FHIRFamilyMemberHistoryCondition extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept code Condition suffered by relation */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept outcome deceased | permanent disability | etc. */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept $outcome = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean contributedToDeath Whether the condition contributed to the cause of death */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean $contributedToDeath = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAge|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string onsetX When condition first manifested */
		public \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAge|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $onsetX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAnnotation> note Extra information about condition */
		public array $note = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
