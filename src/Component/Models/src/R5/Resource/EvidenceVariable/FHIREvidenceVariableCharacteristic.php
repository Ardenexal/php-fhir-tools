<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description A defining factor of the EvidenceVariable. Multiple characteristics are applied with "and" semantics.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'EvidenceVariable', elementPath: 'EvidenceVariable.characteristic', fhirVersion: 'R5')]
class FHIREvidenceVariableCharacteristic extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId linkId Label for internal linking */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId $linkId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown description Natural language description of the characteristic */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation> note Used for footnotes or explanatory notes */
		public array $note = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean exclude Whether the characteristic is an inclusion criterion or exclusion criterion */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean $exclude = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference definitionReference Defines the characteristic (without using type and value) by a Reference */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $definitionReference = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical definitionCanonical Defines the characteristic (without using type and value) by a Canonical */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical $definitionCanonical = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept definitionCodeableConcept Defines the characteristic (without using type and value) by a CodeableConcept */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $definitionCodeableConcept = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExpression definitionExpression Defines the characteristic (without using type and value) by an expression */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExpression $definitionExpression = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId definitionId Defines the characteristic (without using type and value) by an id */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId $definitionId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIREvidenceVariableCharacteristicDefinitionByTypeAndValue definitionByTypeAndValue Defines the characteristic using type and value */
		public ?FHIREvidenceVariableCharacteristicDefinitionByTypeAndValue $definitionByTypeAndValue = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIREvidenceVariableCharacteristicDefinitionByCombination definitionByCombination Used to specify how two or more characteristics are combined */
		public ?FHIREvidenceVariableCharacteristicDefinitionByCombination $definitionByCombination = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRange instancesX Number of occurrences meeting the characteristic */
		public \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRange|null $instancesX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRange durationX Length of time in which the characteristic is met */
		public \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRange|null $durationX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIREvidenceVariableCharacteristicTimeFromEvent> timeFromEvent Timing in which the characteristic is determined */
		public array $timeFromEvent = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
