<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ResearchElementDefinition;

/**
 * @description A characteristic that defines the members of the research element. Multiple characteristics are applied with "and" semantics.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ResearchElementDefinition', elementPath: 'ResearchElementDefinition.characteristic', fhirVersion: 'R4')]
class ResearchElementDefinitionCharacteristic extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Expression|\Ardenexal\FHIRTools\Component\Models\R4\DataType\DataRequirement definitionX What code or expression defines members? */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Expression|\Ardenexal\FHIRTools\Component\Models\R4\DataType\DataRequirement|null $definitionX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\UsageContext> usageContext What code/value pairs define members? */
		public array $usageContext = [],
		/** @var null|bool exclude Whether the characteristic includes or excludes members */
		public ?bool $exclude = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept unitOfMeasure What unit is the outcome described in? */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $unitOfMeasure = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string studyEffectiveDescription What time period does the study cover */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $studyEffectiveDescription = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing studyEffectiveX What time period does the study cover */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing|null $studyEffectiveX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration studyEffectiveTimeFromStart Observation time from study start */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration $studyEffectiveTimeFromStart = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\GroupMeasureType studyEffectiveGroupMeasure mean | median | mean-of-mean | mean-of-median | median-of-mean | median-of-median */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\GroupMeasureType $studyEffectiveGroupMeasure = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string participantEffectiveDescription What time period do participants cover */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $participantEffectiveDescription = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing participantEffectiveX What time period do participants cover */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing|null $participantEffectiveX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration participantEffectiveTimeFromStart Observation time from study start */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration $participantEffectiveTimeFromStart = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\GroupMeasureType participantEffectiveGroupMeasure mean | median | mean-of-mean | mean-of-median | median-of-mean | median-of-median */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\GroupMeasureType $participantEffectiveGroupMeasure = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
