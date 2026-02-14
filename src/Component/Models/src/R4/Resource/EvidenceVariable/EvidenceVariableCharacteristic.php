<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\EvidenceVariable;

/**
 * @description A characteristic that defines the members of the evidence element. Multiple characteristics are applied with "and" semantics.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'EvidenceVariable', elementPath: 'EvidenceVariable.characteristic', fhirVersion: 'R4')]
class EvidenceVariableCharacteristic extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string description Natural language description of the characteristic */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Expression|\Ardenexal\FHIRTools\Component\Models\R4\DataType\DataRequirement|\Ardenexal\FHIRTools\Component\Models\R4\DataType\TriggerDefinition definitionX What code or expression defines members? */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Expression|\Ardenexal\FHIRTools\Component\Models\R4\DataType\DataRequirement|\Ardenexal\FHIRTools\Component\Models\R4\DataType\TriggerDefinition|null $definitionX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\UsageContext> usageContext What code/value pairs define members? */
		public array $usageContext = [],
		/** @var null|bool exclude Whether the characteristic includes or excludes members */
		public ?bool $exclude = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing participantEffectiveX What time period do participants cover */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing|null $participantEffectiveX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration timeFromStart Observation time from study start */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration $timeFromStart = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\GroupMeasureType groupMeasure mean | median | mean-of-mean | mean-of-median | median-of-mean | median-of-median */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\GroupMeasureType $groupMeasure = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
