<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @description A characteristic that defines the members of the evidence element. Multiple characteristics are applied with "and" semantics.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'EvidenceVariable', elementPath: 'EvidenceVariable.characteristic', fhirVersion: 'R4')]
class FHIREvidenceVariableCharacteristic extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string description Natural language description of the characteristic */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCanonical|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExpression|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRDataRequirement|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRTriggerDefinition definitionX What code or expression defines members? */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCanonical|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExpression|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRDataRequirement|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRTriggerDefinition|null $definitionX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRUsageContext> usageContext What code/value pairs define members? */
		public array $usageContext = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean exclude Whether the characteristic includes or excludes members */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean $exclude = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRDuration|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRTiming participantEffectiveX What time period do participants cover */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRDuration|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRTiming|null $participantEffectiveX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRDuration timeFromStart Observation time from study start */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRDuration $timeFromStart = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRGroupMeasureType groupMeasure mean | median | mean-of-mean | mean-of-median | median-of-mean | median-of-median */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRGroupMeasureType $groupMeasure = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
