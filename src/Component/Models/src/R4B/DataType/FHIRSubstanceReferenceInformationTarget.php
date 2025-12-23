<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element SubstanceReferenceInformation.target
 * @description Todo.
 */
class FHIRSubstanceReferenceInformationTarget extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRIdentifier target Todo */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRIdentifier $target = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept type Todo */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept interaction Todo */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $interaction = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept organism Todo */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $organism = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept organismType Todo */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $organismType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string amountX Todo */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $amountX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept amountType Todo */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $amountType = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference> source Todo */
		public array $source = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
