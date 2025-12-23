<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element ResearchElementDefinition.characteristic
 * @description A characteristic that defines the members of the research element. Multiple characteristics are applied with "and" semantics.
 */
class FHIRResearchElementDefinitionCharacteristic extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExpression|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDataRequirement definitionX What code or expression defines members? */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExpression|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDataRequirement|null $definitionX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUsageContext> usageContext What code/value pairs define members? */
		public array $usageContext = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean exclude Whether the characteristic includes or excludes members */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean $exclude = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept unitOfMeasure What unit is the outcome described in? */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $unitOfMeasure = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string studyEffectiveDescription What time period does the study cover */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $studyEffectiveDescription = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDuration|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTiming studyEffectiveX What time period does the study cover */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDuration|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTiming|null $studyEffectiveX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDuration studyEffectiveTimeFromStart Observation time from study start */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDuration $studyEffectiveTimeFromStart = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRGroupMeasureType studyEffectiveGroupMeasure mean | median | mean-of-mean | mean-of-median | median-of-mean | median-of-median */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRGroupMeasureType $studyEffectiveGroupMeasure = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string participantEffectiveDescription What time period do participants cover */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $participantEffectiveDescription = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDuration|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTiming participantEffectiveX What time period do participants cover */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDuration|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTiming|null $participantEffectiveX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDuration participantEffectiveTimeFromStart Observation time from study start */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDuration $participantEffectiveTimeFromStart = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRGroupMeasureType participantEffectiveGroupMeasure mean | median | mean-of-mean | mean-of-median | median-of-mean | median-of-median */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRGroupMeasureType $participantEffectiveGroupMeasure = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
