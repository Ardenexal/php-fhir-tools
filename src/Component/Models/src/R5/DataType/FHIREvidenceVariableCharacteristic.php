<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element EvidenceVariable.characteristic
 * @description A defining factor of the EvidenceVariable. Multiple characteristics are applied with "and" semantics.
 */
class FHIREvidenceVariableCharacteristic extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRId linkId Label for internal linking */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRId $linkId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown description Natural language description of the characteristic */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAnnotation> note Used for footnotes or explanatory notes */
		public array $note = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean exclude Whether the characteristic is an inclusion criterion or exclusion criterion */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean $exclude = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference definitionReference Defines the characteristic (without using type and value) by a Reference */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $definitionReference = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical definitionCanonical Defines the characteristic (without using type and value) by a Canonical */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical $definitionCanonical = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept definitionCodeableConcept Defines the characteristic (without using type and value) by a CodeableConcept */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $definitionCodeableConcept = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExpression definitionExpression Defines the characteristic (without using type and value) by an expression */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExpression $definitionExpression = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRId definitionId Defines the characteristic (without using type and value) by an id */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRId $definitionId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIREvidenceVariableCharacteristicDefinitionByTypeAndValue definitionByTypeAndValue Defines the characteristic using type and value */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIREvidenceVariableCharacteristicDefinitionByTypeAndValue $definitionByTypeAndValue = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIREvidenceVariableCharacteristicDefinitionByCombination definitionByCombination Used to specify how two or more characteristics are combined */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIREvidenceVariableCharacteristicDefinitionByCombination $definitionByCombination = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRange instancesX Number of occurrences meeting the characteristic */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRange|null $instancesX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRange durationX Length of time in which the characteristic is met */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRange|null $durationX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIREvidenceVariableCharacteristicTimeFromEvent> timeFromEvent Timing in which the characteristic is determined */
		public array $timeFromEvent = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
