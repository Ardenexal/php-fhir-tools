<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element EvidenceVariable.characteristic
 * @description A characteristic that defines the members of the evidence element. Multiple characteristics are applied with "and" semantics.
 */
class FHIREvidenceVariableCharacteristic extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string description Natural language description of the characteristic */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCanonical|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExpression|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDataRequirement|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRTriggerDefinition definitionX What code or expression defines members? */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCanonical|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExpression|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDataRequirement|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRTriggerDefinition|null $definitionX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUsageContext> usageContext What code/value pairs define members? */
		public array $usageContext = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBoolean exclude Whether the characteristic includes or excludes members */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBoolean $exclude = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDuration|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRTiming participantEffectiveX What time period do participants cover */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDuration|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRTiming|null $participantEffectiveX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDuration timeFromStart Observation time from study start */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDuration $timeFromStart = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRGroupMeasureType groupMeasure mean | median | mean-of-mean | mean-of-median | median-of-mean | median-of-median */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRGroupMeasureType $groupMeasure = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
