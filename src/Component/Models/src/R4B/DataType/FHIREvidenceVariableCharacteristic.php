<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element EvidenceVariable.characteristic
 * @description A characteristic that defines the members of the evidence element. Multiple characteristics are applied with "and" semantics.
 */
class FHIREvidenceVariableCharacteristic extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string description Natural language description of the characteristic */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCanonical|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExpression definitionX What code or expression defines members? */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCanonical|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExpression|null $definitionX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept method Method used for describing characteristic */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $method = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference device Device used for determining characteristic */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference $device = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean exclude Whether the characteristic includes or excludes members */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean $exclude = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIREvidenceVariableCharacteristicTimeFromStart timeFromStart Observation time from study start */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIREvidenceVariableCharacteristicTimeFromStart $timeFromStart = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRGroupMeasureType groupMeasure mean | median | mean-of-mean | mean-of-median | median-of-mean | median-of-median */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRGroupMeasureType $groupMeasure = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
